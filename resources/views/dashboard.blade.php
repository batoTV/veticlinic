@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="container mx-auto">
    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stats shadow bg-gradient-to-br from-primary to-primary-focus text-primary-content">
            <div class="stat">
                <div class="stat-figure">
                    <i class="fas fa-calendar-check text-4xl opacity-30"></i>
                </div>
                <div class="stat-title text-primary-content opacity-80">Today's Appointments</div>
                <div class="stat-value">{{ $todayAppointments ?? 0 }}</div>
                <div class="stat-desc text-primary-content opacity-60">
                    @if(($appointmentChange ?? 0) > 0)
                        <i class="fas fa-arrow-up"></i> {{ $appointmentChange }} more than yesterday
                    @elseif(($appointmentChange ?? 0) < 0)
                        <i class="fas fa-arrow-down"></i> {{ abs($appointmentChange) }} less than yesterday
                    @else
                        Same as yesterday
                    @endif
                </div>
            </div>
        </div>
        
        <div class="stats shadow bg-gradient-to-br from-secondary to-secondary-focus text-secondary-content">
            <div class="stat">
                <div class="stat-figure">
                    <i class="fas fa-users text-4xl opacity-30"></i>
                </div>
                <div class="stat-title text-secondary-content opacity-80">Total Owners</div>
                <div class="stat-value">{{ $totalClients ?? 0 }}</div>
                <div class="stat-desc text-secondary-content opacity-60">
                    <i class="fas fa-plus"></i> {{ $newClientsThisMonth ?? 0 }} new this month
                </div>
            </div>
        </div>
        
        <div class="stats shadow bg-gradient-to-br from-accent to-accent-focus text-accent-content">
            <div class="stat">
                <div class="stat-figure">
                    <i class="fas fa-paw text-4xl opacity-30"></i>
                </div>
                <div class="stat-title text-accent-content opacity-80">Active Pets</div>
                <div class="stat-value">{{ $activePets ?? 0 }}</div>
                <div class="stat-desc text-accent-content opacity-60">
                    <i class="fas fa-heart"></i> {{ $newPetsThisMonth ?? 0 }} new this month
                </div>
            </div>
        </div>
        
        <div class="stats shadow bg-gradient-to-br from-success to-success-focus text-success-content">
            <div class="stat">
                <div class="stat-figure">
                    <i class="fas fa-dollar-sign text-4xl opacity-30"></i>
                </div>
                <div class="stat-title text-success-content opacity-80">Today's Revenue</div>
                <div class="stat-value">${{ number_format($todayRevenue ?? 0, 0) }}</div>
                <div class="stat-desc text-success-content opacity-60">
                    <i class="fas fa-chart-line"></i> From paid invoices
                </div>
            </div>
        </div>
    </div>
    
    {{-- Quick Actions & Today's Schedule --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Quick Actions --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="fas fa-bolt text-warning"></i> Quick Actions
                </h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Appointment
                    </a>
                    <a href="{{ route('owners.create') }}" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i> Owner
                    </a>
                    <a href="{{ route('pets.create') }}" class="btn btn-accent">
                        <i class="fas fa-paw"></i> Pet
                    </a>
                    <a href="{{ route('vaccinations.create') }}" class="btn btn-info">
                        <i class="fas fa-syringe"></i> Vaccine
                    </a>
                    <a href="{{ route('treatments.create') }}" class="btn btn-success">
                        <i class="fas fa-notes-medical"></i> Treatment
                    </a>
                    <a href="{{ route('invoices.create') }}" class="btn btn-warning">
                        <i class="fas fa-file-invoice"></i> Invoice
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Today's Schedule --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="fas fa-clock text-info"></i> Today's Schedule
                </h2>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @forelse($todaySchedule ?? [] as $appointment)
                    <div class="flex items-center justify-between p-2 rounded-lg hover:bg-base-200 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="badge badge-primary badge-outline">
                                {{ $appointment->time }}
                            </div>
                            <div>
                                <p class="font-semibold">{{ $appointment->pet_name }}</p>
                                <p class="text-xs opacity-60">Owner: {{ $appointment->owner_name }}</p>
                            </div>
                        </div>
                        <div class="badge badge-{{ $appointment->type_color }} badge-sm">
                            {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 opacity-60">
                        <i class="fas fa-calendar-times text-4xl mb-2"></i>
                        <p>No appointments scheduled for today</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        {{-- Alerts & Notifications --}}
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="fas fa-bell text-error"></i> Alerts
                </h2>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @forelse($alerts ?? [] as $alert)
                    <div class="alert alert-{{ $alert->type }} shadow-sm">
                        <i class="fas fa-{{ $alert->icon }}"></i>
                        <span class="text-sm">{{ $alert->message }}</span>
                    </div>
                    @empty
                    <div class="alert alert-info shadow-sm">
                        <i class="fas fa-info-circle"></i>
                        <span class="text-sm">No alerts at this time</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    {{-- Recent Appointments --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <h2 class="card-title">
                    <i class="fas fa-history text-secondary"></i> Recent Appointments
                </h2>
                <a href="{{ route('appointments.index') }}" class="btn btn-ghost btn-sm">
                    View All <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>Pet</th>
                            <th>Owner</th>
                            <th>Service</th>
                            <th>Veterinarian</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAppointments ?? [] as $appointment)
                        <tr class="hover">
                            <td>
                                <div class="font-bold">{{ $appointment->appointment_date->format('M d') }}</div>
                                <div class="text-sm opacity-50">{{ $appointment->appointment_date->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-12 h-12">
                                            @if($appointment->pet->photo)
                                                <img src="{{ Storage::url($appointment->pet->photo) }}" alt="{{ $appointment->pet->name }}" />
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($appointment->pet->name) }}&background=10b981&color=fff" alt="{{ $appointment->pet->name }}" />
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $appointment->pet->name }}</div>
                                        <div class="text-sm opacity-50">{{ ucfirst($appointment->pet->species) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $appointment->pet->owner->name ?? 'Unknown' }}</td>
                            <td>
                                @php
                                    $typeColors = [
                                        'checkup' => 'primary',
                                        'vaccination' => 'success',
                                        'surgery' => 'warning',
                                        'grooming' => 'info',
                                        'emergency' => 'error',
                                        'follow_up' => 'secondary',
                                        'other' => 'neutral'
                                    ];
                                    $color = $typeColors[$appointment->type] ?? 'neutral';
                                @endphp
                                <span class="badge badge-{{ $color }} badge-sm">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->type)) }}
                                </span>
                            </td>
                            <td>{{ $appointment->staff->name ?? 'Unassigned' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'scheduled' => 'info',
                                        'confirmed' => 'primary',
                                        'in_progress' => 'warning',
                                        'completed' => 'success',
                                        'cancelled' => 'error',
                                        'no_show' => 'ghost'
                                    ];
                                    $statusColor = $statusColors[$appointment->status] ?? 'neutral';
                                @endphp
                                <span class="badge badge-{{ $statusColor }} badge-outline">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-ghost btn-xs">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 opacity-60">
                                No appointments found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh dashboard stats every 30 seconds
    setInterval(function() {
        fetch('{{ route("api.dashboard.stats") }}')
            .then(response => response.json())
            .then(data => {
                // Update stats if needed
                console.log('Dashboard stats updated', data);
            });
    }, 30000);
</script>
@endpush