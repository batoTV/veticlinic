<?php

// routes/web.php
// Updated routes to work with Owner model instead of Client

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OwnerController;  // Changed from ClientController
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (using Laravel Breeze or similar)
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    
    // Resource routes for CRUD operations
    Route::resource('owners', OwnerController::class);  // Changed from clients to owners
    Route::resource('pets', PetController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('treatments', TreatmentController::class);
    Route::resource('vaccinations', VaccinationController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('inventory', InventoryController::class);
    
    // Additional custom routes
    Route::post('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::get('pets/{pet}/medical-history', [PetController::class, 'medicalHistory'])->name('pets.medical-history');
    Route::get('owners/{owner}/pets', [OwnerController::class, 'pets'])->name('owners.pets');  // Changed
    Route::post('invoices/{invoice}/payment', [InvoiceController::class, 'recordPayment'])->name('invoices.payment');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/appointments', [ReportController::class, 'appointments'])->name('appointments');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
    });
    
    // API routes for AJAX calls
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/owners/{owner}/pets', [OwnerController::class, 'getPets'])->name('owner.pets');  // Changed
        Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    });
});