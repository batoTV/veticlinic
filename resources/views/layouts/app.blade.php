<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="emerald">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'VetClinic') }} - @yield('title')</title>
    
    {{-- Vite for Laravel asset compilation --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Alpine.js for interactivity --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-base-200">
    <div class="drawer lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        <div class="drawer-content">
            {{-- Top Navigation Bar --}}
            <div class="navbar bg-base-100 shadow-lg lg:hidden">
                <div class="flex-none">
                    <label for="drawer-toggle" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>
                <div class="flex-1">
                    <a class="btn btn-ghost normal-case text-xl">
                        <i class="fas fa-clinic-medical text-primary"></i> VetClinic
                    </a>
                </div>
            </div>
            
            {{-- Page Header --}}
            <div class="bg-gradient-to-r from-primary to-secondary text-primary-content p-4 shadow-lg">
                <div class="container mx-auto">
                    <h1 class="text-2xl font-bold">@yield('header', 'Dashboard')</h1>
                    <div class="text-sm breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home') }}">Home</a></li>
                            @yield('breadcrumbs')
                        </ul>
                    </div>
                </div>
            </div>
            
            {{-- Flash Messages --}}
            <div class="container mx-auto px-4 mt-4">
                @if(session('success'))
                <div class="alert alert-success shadow-lg mb-4" x-data="{ show: true }" x-show="show" x-transition>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <div class="flex-none">
                        <button @click="show = false" class="btn btn-sm btn-ghost">✕</button>
                    </div>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-error shadow-lg mb-4" x-data="{ show: true }" x-show="show" x-transition>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <div class="flex-none">
                        <button @click="show = false" class="btn btn-sm btn-ghost">✕</button>
                    </div>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert-warning shadow-lg mb-4">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
            
            {{-- Main Content --}}
            <main class="p-4">
                @yield('content')
            </main>
        </div>
        
        {{-- Sidebar Navigation --}}
        <div class="drawer-side">
            <label for="drawer-toggle" class="drawer-overlay"></label>
            <aside class="w-64 min-h-full bg-base-100">
                {{-- Logo --}}
                <div class="p-4 bg-primary text-primary-content">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clinic-medical text-3xl"></i>
                        <div>
                            <h2 class="text-xl font-bold">VetClinic</h2>
                            <p class="text-xs opacity-75">Management System</p>
                        </div>
                    </div>
                </div>
                
                {{-- User Info --}}
                @auth
                <div class="p-4 bg-base-200">
                    <div class="flex items-center space-x-3">
                        <div class="avatar">
                            <div class="w-10 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff" />
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-xs opacity-60">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
                @endauth
                
                {{-- Navigation Menu --}}
                <ul class="menu p-4 w-full">
                    <li class="menu-title">
                        <span>Main Menu</span>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    
                    <li class="menu-title">
                        <span>Management</span>
                    </li>
                    <li>
                        <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i> Appointments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Clients
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pets.index') }}" class="{{ request()->routeIs('pets.*') ? 'active' : '' }}">
                            <i class="fas fa-paw"></i> Pets
                        </a>
                    </li>
                    
                    <li class="menu-title">
                        <span>Medical</span>
                    </li>
                    <li>
                        <a href="{{ route('treatments.index') }}" class="{{ request()->routeIs('treatments.*') ? 'active' : '' }}">
                            <i class="fas fa-stethoscope"></i> Treatments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vaccinations.index') }}" class="{{ request()->routeIs('vaccinations.*') ? 'active' : '' }}">
                            <i class="fas fa-syringe"></i> Vaccinations
                        </a>
                    </li>
                    
                    <li class="menu-title">
                        <span>Financial</span>
                    </li>
                    <li>
                        <a href="{{ route('invoices.index') }}" class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice-dollar"></i> Invoices
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                            <i class="fas fa-boxes"></i> Inventory
                        </a>
                    </li>
                    
                    <li class="menu-title">
                        <span>System</span>
                    </li>
                    <li>
                        <a href="{{ route('staff.index') }}" class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                            <i class="fas fa-user-md"></i> Staff
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i> Reports
                        </a>
                    </li>
                    
                    @auth
                    <li class="mt-auto">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                    @endauth
                </ul>
            </aside>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>