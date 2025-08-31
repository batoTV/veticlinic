<div class="stats shadow stats-card">
    <div class="stat">
        <div class="stat-figure text-{{ $color ?? 'primary' }}">
            <i class="fas fa-{{ $icon }} text-3xl"></i>
        </div>
        <div class="stat-title">{{ $title }}</div>
        <div class="stat-value text-{{ $color ?? 'primary' }}">{{ $value }}</div>
        <div class="stat-desc">{{ $description }}</div>
    </div>
</div>

