<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <div class="flex justify-between items-center mb-4">
            <h2 class="card-title">{{ $title }}</h2>
            @if(isset($createRoute))
            <a href="{{ $createRoute }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New
            </a>
            @endif
        </div>
        
        @if(isset($searchRoute))
        <form method="GET" action="{{ $searchRoute }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search..." 
                       class="input input-bordered w-full" />
                <button type="submit" class="btn btn-square">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
        @endif
        
        <div class="overflow-x-auto">
            {{ $slot }}
        </div>
    </div>
</div>