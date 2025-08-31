@extends('layouts.app')

@section('title', 'Appointments')
@section('header', 'Appointment Schedule')

@section('breadcrumbs')
    <li>Appointments</li>
@endsection

@section('content')
<div class="container mx-auto">
    {{-- Calendar View Toggle --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                <div class="tabs tabs-boxed">
                    <a class="tab tab-active" data-view="list">
                        <i class="fas fa-list"></i> List View
                    </a>
                    <a class="tab" data-view="calendar">
                        <i class="fas fa-calendar"></i> Calendar View
                    </a>
                    <a class="tab" data-view="today">
                        <i class="fas fa-clock"></i> Today
                    </a>
                </div>
                
                <div class="flex gap-2">
                    <input type="date" class="input input-bordered" value="{{ date('Y-m-d') }}" />
                    <select class="select select-bordered">
                        <option>All Veterinarians</option>
                        @foreach($staff ?? [] as $vet)
                        <option value="{{ $vet->id }}">{{ $vet->name }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Appointment
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Today's Timeline --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title mb-4">Today's Timeline</h2>
            <div class="relative">
                @foreach($todayAppointments ?? [] as $index => $appointment)
                <div class="flex gap-4 mb-4">
                    <div class="flex flex-col items-center">
                        <div class="badge badge-primary badge-lg">{{ $appointment->time ?? '09:00' }}</div>
                        @if($index < count($todayAppointments ?? []) - 1)
                        <div class="w-0.5 h-20 bg-gray-300 mt-2"></div>
                        @endif
                    </div>
                    
                    <div class="flex-1 appointment-card p-4 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg">{{ $appointment->pet_name ?? 'Pet Name' }}</h3>
                                <p class="text-sm opacity-70">Owner: {{ $appointment->client_name ?? 'Client Name' }}</p>
                                <p class="text-sm">Service: {{ $appointment->type ?? 'Checkup' }}</p>
                                <p class="text-sm">Vet: {{ $appointment->vet_name ?? 'Dr. Smith' }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="badge badge-{{ $appointment->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($appointment->status ?? 'scheduled') }}
                                </span>
                                <div class="btn-group btn-group-vertical">
                                    <button class="btn btn-xs">View</button>
                                    <button class="btn btn-xs">Edit</button>
                                    <button class="btn btn-xs">Complete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    {{-- Appointments Table --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title mb-4">All Appointments</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>Pet</th>
                            <th>Owner</th>
                            <th>Service</th>
                            <th>Veterinarian</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments ?? [] as $appointment)
                        <tr class="hover">
                            <td>
                                <div class="font-bold">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                <div class="text-sm opacity-50">{{ $appointment->appointment_date->format('h:i A') }}</div>
                            </td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-12 h-12">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($appointment->pet->name) }}&background=10b981&color=fff" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $appointment->pet->name }}</div>
                                        <div class="text-sm opacity-50">{{ $appointment->pet->species }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $appointment->client->full_name }}</td>
                            <td>
                                <span class="badge badge-primary badge-outline">
                                    {{ ucfirst($appointment->type) }}
                                </span>
                            </td>
                            <td>{{ $appointment->staff->full_name ?? 'Unassigned' }}</td>
                            <td>{{ $appointment->duration }} min</td>
                            <td>
                                <span class="badge badge-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'error' : 'warning') }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown dropdown-left">
                                    <label tabindex="0" class="btn btn-ghost btn-xs">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </label>
                                    <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                                        <li><a href="{{ route('appointments.show', $appointment) }}"><i class="fas fa-eye"></i> View</a></li>
                                        <li><a href="{{ route('appointments.edit', $appointment) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                        <li><a><i class="fas fa-check"></i> Mark Complete</a></li>
                                        <li><a class="text-error"><i class="fas fa-times"></i> Cancel</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No appointments found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection