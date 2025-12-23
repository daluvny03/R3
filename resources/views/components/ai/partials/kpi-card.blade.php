@props(['title', 'value', 'color' => 'blue', 'badge' => ''])

<div class="bg-gradient-to-br from-{{ $color }}-50 to-{{ $color }}-100 p-6 rounded-xl border-2 border-{{ $color }}-200">
    <p class="text-sm text-gray-600 mb-2">{{ $title }}</p>
    <p class="text-3xl font-bold text-{{ $color }}-600" x-text="{{ $value }}"></p>
    @if($badge)
        <span class="text-xs bg-{{ $color }}-200 text-{{ $color }}-800 px-2 py-1 rounded-full mt-2 inline-block" x-text="{{ $badge }}"></span>
    @endif
</div>