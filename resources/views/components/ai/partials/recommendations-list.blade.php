<div class="mb-6" x-show="results.recommendations && results.recommendations.length > 0">
    <x-ai.partials.section-header 
        icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
        title="Rekomendasi Aksi"
        color="green"
    />
    <ul class="space-y-2">
        <template x-for="(rec, index) in results.recommendations" :key="index">
            <li class="flex items-start bg-green-50 p-3 rounded-lg border-l-4 border-green-500">
                <span class="text-green-500 mr-3 font-bold">✓</span>
                <span x-text="rec" class="text-gray-700"></span>
            </li>
        </template>
    </ul>
</div>