@props([
    'route',
    'active' => false,
    'open'   => true,
])

@php
    try {
        $url = route($route);
        $valid = true;
    } catch (\Exception $e) {
        $url = '#';
        $valid = false;
    }
@endphp

<a
    href="{{ $url }}"
    {{ $valid ? 'wire:navigate' : '' }}
    title="{{ $slot }}"
    class="flex items-center gap-3 px-2 py-2 rounded-lg text-sm font-medium transition-colors duration-150 group"
    style="{{ $active
        ? 'background:#14b8a6; color:#fff;'
        : 'color:#94a3b8;' }}"
    onmouseover="{{ $active ? '' : "this.style.background='#1a3e52'; this.style.color='#fff';" }}"
    onmouseout="{{ $active ? '' : "this.style.background=''; this.style.color='#94a3b8';" }}"
>
    {{-- Icon --}}
    <span class="flex-shrink-0">
        {{ $icon }}
    </span>

    {{-- Label (shown when sidebar is open) --}}
    <span
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity duration-200 delay-100"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="truncate"
    >
        {{ $slot }}
    </span>
</a>
