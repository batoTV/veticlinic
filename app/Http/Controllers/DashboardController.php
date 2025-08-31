<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Owner;  // Changed from Client to Owner
use App\Models\Pet;
use App\Models\Invoice;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's statistics
        $todayAppointments = Appointment::whereDate('appointment_date', Carbon::today())->count();
        $yesterdayAppointments = Appointment::whereDate('appointment_date', Carbon::yesterday())->count();
        $appointmentChange = $todayAppointments - $yesterdayAppointments;
        
        // Owner statistics (changed from Client)
        $totalOwners = Owner::count();
        $newOwnersThisMonth = Owner::whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year)
                                   ->count();
        
        // Pet statistics
        $activePets = Pet::where('status', 'active')->count();
        $newPetsThisMonth = Pet::whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->count();
        
        // Revenue statistics - check if Invoice model exists
        $todayRevenue = 0;
        if (class_exists('App\Models\Invoice')) {
            $todayRevenue = Invoice::whereDate('created_at', Carbon::today())
                                   ->where('status', 'paid')
                                   ->sum('total_amount');
        }
        
        // Today's schedule with eager loading
        $todaySchedule = Appointment::with(['pet', 'pet.owner', 'staff'])
                                    ->whereDate('appointment_date', Carbon::today())
                                    ->orderBy('appointment_date')
                                    ->get()
                                    ->map(function ($appointment) {
                                        return (object)[
                                            'time' => $appointment->appointment_date->format('H:i'),
                                            'pet_name' => $appointment->pet->name,
                                            'owner_name' => $appointment->pet->owner->name ?? 'Unknown',
                                            'type' => $appointment->type,
                                            'type_color' => $this->getTypeColor($appointment->type),
                                            'status' => $appointment->status
                                        ];
                                    });
        
        // Recent appointments
        $recentAppointments = Appointment::with(['pet', 'pet.owner', 'staff'])
                                        ->orderBy('appointment_date', 'desc')
                                        ->limit(10)
                                        ->get();
        
        // Alerts
        $alerts = $this->generateAlerts();
        
        return view('dashboard', [
            'todayAppointments' => $todayAppointments,
            'appointmentChange' => $appointmentChange,
            'totalClients' => $totalOwners,  // Using totalOwners but keeping variable name for view compatibility
            'newClientsThisMonth' => $newOwnersThisMonth,
            'activePets' => $activePets,
            'newPetsThisMonth' => $newPetsThisMonth,
            'todayRevenue' => $todayRevenue,
            'todaySchedule' => $todaySchedule,
            'recentAppointments' => $recentAppointments,
            'alerts' => $alerts
        ]);
    }
    
    private function getTypeColor($type)
    {
        $colors = [
            'checkup' => 'primary',
            'vaccination' => 'success',
            'surgery' => 'warning',
            'grooming' => 'info',
            'emergency' => 'error',
            'follow_up' => 'secondary',
            'other' => 'neutral'
        ];
        
        return $colors[$type] ?? 'neutral';
    }
    
    private function generateAlerts()
    {
        $alerts = collect();
        
        // Check for low inventory (only if table exists)
        if (DB::getSchemaBuilder()->hasTable('inventory')) {
            $lowInventory = DB::table('inventory')
                             ->whereRaw('quantity_in_stock <= reorder_level')
                             ->count();
            
            if ($lowInventory > 0) {
                $alerts->push((object)[
                    'type' => 'warning',
                    'icon' => 'exclamation-triangle',
                    'message' => "$lowInventory items low in stock"
                ]);
            }
        }
        
        // Check for upcoming vaccinations (only if table exists)
        if (DB::getSchemaBuilder()->hasTable('vaccinations')) {
            $upcomingVaccinations = DB::table('vaccinations')
                                     ->whereDate('next_due_date', '<=', Carbon::now()->addDays(7))
                                     ->count();
            
            if ($upcomingVaccinations > 0) {
                $alerts->push((object)[
                    'type' => 'info',
                    'icon' => 'bell',
                    'message' => "$upcomingVaccinations vaccination reminders to send"
                ]);
            }
        }
        
        // Check for overdue invoices (only if Invoice model exists)
        if (class_exists('App\Models\Invoice')) {
            $overdueInvoices = Invoice::where('status', '!=', 'paid')
                                      ->whereDate('due_date', '<', Carbon::today())
                                      ->count();
            
            if ($overdueInvoices > 0) {
                $alerts->push((object)[
                    'type' => 'error',
                    'icon' => 'exclamation-circle',
                    'message' => "$overdueInvoices overdue invoices"
                ]);
            }
        }
        
        // Success message if all morning appointments are completed
        $morningComplete = Appointment::whereDate('appointment_date', Carbon::today())
                                     ->whereTime('appointment_date', '<', '12:00:00')
                                     ->where('status', '!=', 'completed')
                                     ->count() == 0;
        
        if ($morningComplete && Carbon::now()->hour >= 12) {
            $alerts->push((object)[
                'type' => 'success',
                'icon' => 'check-circle',
                'message' => 'All morning appointments completed!'
            ]);
        }
        
        // Default alert if no other alerts
        if ($alerts->isEmpty()) {
            $alerts->push((object)[
                'type' => 'info',
                'icon' => 'info-circle',
                'message' => 'Everything is running smoothly!'
            ]);
        }
        
        return $alerts;
    }
    
    public function stats()
    {
        // API endpoint for real-time dashboard updates
        return response()->json([
            'appointments_today' => Appointment::whereDate('appointment_date', Carbon::today())->count(),
            'owners_total' => Owner::count(),
            'pets_active' => Pet::where('status', 'active')->count(),
            'revenue_today' => class_exists('App\Models\Invoice') 
                ? Invoice::whereDate('created_at', Carbon::today())
                         ->where('status', 'paid')
                         ->sum('total_amount')
                : 0
        ]);
    }
}