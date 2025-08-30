<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has access to dashboard (only vets and receptionists)
        if (!$user->isVet() && !$user->isReceptionist()) {
            // Redirect assistants to pets page
            return redirect()->route('pets.index')->with('error', 'You do not have access to the dashboard.');
        }
        
        // Dashboard logic here
        return view('dashboard');
    }
}