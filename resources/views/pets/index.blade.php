@extends('layouts.app')

@section('title', 'Pets')
@section('header', 'Pet Management')

@section('breadcrumbs')
    <li>Pets</li>
@endsection

@section('content')
<div class="container mx-auto">
    {{-- Header with Search --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
                <div class="flex-1 w-full">
                    <form method="GET" action="{{ route('pets.index') }}" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search pets by name, owner, or species..." 
                               class="input input-bordered w-full" />
                        <select name="species" class="select select-bordered">
                            <option value="">All Species</option>
                            <option value="dog" {{ request('species') == 'dog' ? 'selected' : '' }}>Dogs</option>
                            <option value="cat" {{ request('species') == 'cat' ? 'selected' : '' }}>Cats</option>
                            <option value="bird" {{ request('species') == 'bird' ? 'selected' : '' }}>Birds</option>
                            <option value="rabbit" {{ request('species') == 'rabbit' ? 'selected' : '' }}>Rabbits</option>
                            <option value="other" {{ request('species') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <a href="{{ route('pets.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Pet
                </a>
            </div>
        </div>
    </div>
    
    {{-- Pets Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($pets ?? [] as $pet)
        <div class="card bg-base-100 shadow-xl pet-card">
            <figure class="px-4 pt-4">
                <div class="avatar">
                    <div class="w-32 rounded-xl">
                        @if($pet->photo)
                            <img src="{{ Storage::url($pet->photo) }}" alt="{{ $pet->name }}" />
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pet->name) }}&background=10b981&color=fff&size=128" alt="{{ $pet->name }}" />
                        @endif
                    </div>
                </div>
            </figure>
            <div class="card-body">
                <h2 class="card-title">
                    {{ $pet->name }}
                    @if($pet->gender == 'male')
                        <i class="fas fa-mars text-blue-500"></i>
                    @else
                        <i class="fas fa-venus text-pink-500"></i>
                    @endif
                </h2>
                <div class="badge badge-{{ $pet->species == 'dog' ? 'primary' : ($pet->species == 'cat' ? 'secondary' : 'accent') }} badge-outline">
                    {{ ucfirst($pet->species) }}
                </div>
                <p class="text-sm opacity-70">{{ $pet->breed ?? 'Mixed' }}</p>
                
                <div class="divider my-2"></div>
                
                <div class="text-sm space-y-1">
                    <p><i class="fas fa-user text-primary"></i> {{ $pet->client->full_name ?? 'Unknown' }}</p>
                    <p><i class="fas fa-birthday-cake text-accent"></i> {{ $pet->age ?? 'Unknown age' }}</p>
                    <p><i class="fas fa-weight text-info"></i> {{ $pet->weight ? $pet->weight . ' kg' : 'Not recorded' }}</p>
                </div>
                
                <div class="card-actions justify-end mt-4">
                    <a href="{{ route('pets.show', $pet) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('pets.edit', $pet) }}" class="btn btn-ghost btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="alert alert-info shadow-lg">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current flex-shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>No pets found. Start by adding a new pet!</span>
                </div>
            </div>
        </div>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    @if(isset($pets) && $pets->hasPages())
    <div class="flex justify-center mt-6">
        {{ $pets->links() }}
    </div>
    @endif
</div>
@endsection