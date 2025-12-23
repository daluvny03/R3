@props(['type', 'color', 'icon', 'title', 'description'])

<button @click="analyzeType('{{ $type }}')" 
    :class="analysisType === '{{ $type }}' ? 'ring-2 ring-{{ $color }}-500 bg-{{ $color }}-50' : ''"
    class="p-4 border-2 border-gray-200 rounded-xl hover:border-{{ $color }}-500 hover:shadow-md transition-all text-left group w-full">
    <div class="flex items-center space-x-3 mb-2">
        <div class="bg-{{ $color }}-100 p-2 rounded-lg group-hover:bg-{{ $color }}-500 transition-all duration-300">
            <svg class="w-6 h-6 text-{{ $color }}-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
            </svg>
        </div>
        <h4 class="font-bold text-gray-800 group-hover:text-{{ $color }}-600 transition-colors">{{ $title }}</h4>
    </div>
    <p class="text-sm text-gray-600">{{ $description }}</p>
</button>