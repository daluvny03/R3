@props(['icon', 'title', 'color' => 'blue'])

<h4 class="font-bold text-lg mb-4 flex items-center">
    <svg class="w-6 h-6 text-{{ $color }}-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
    </svg>
    {{ $title }}
</h4>