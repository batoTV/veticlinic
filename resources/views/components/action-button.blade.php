{{-- resources/views/components/action-button.blade.php --}}
{{-- Reusable action button component --}}
<a href="{{ $route }}" class="btn btn-{{ $color ?? 'primary' }} {{ $size ?? '' }}">
    <i class="fas fa-{{ $icon }}"></i> {{ $text }}
</a>