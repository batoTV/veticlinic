<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Pet;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalPets = Pet::count();
        $totalOwners = Owner::count();
        $todaysAppointments = Appointment::with('pet.owner')
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_date', 'asc')
            ->paginate(15);

        return view('dashboard', [
            'totalPets' => $totalPets,
            'totalOwners' => $totalOwners,
            'todaysAppointments' => $todaysAppointments,
        ]);
    }
}
