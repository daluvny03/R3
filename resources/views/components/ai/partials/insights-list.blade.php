<div class="mb-6" x-show="results.insights && results.insights.length > 0">
    <x-ai.partials.section-header 
        icon="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
        title="Key Insights"
        color="yellow"
    />
    <ul class="space-y-2">
        <template x-for="(insight, index) in results.insights" :key="index">
            <li class="flex items-start bg-yellow-50 p-3 rounded-lg">
                <span class="text-yellow-500 mr-3 font-bold">💡</span>
                <span x-text="insight" class="text-gray-700"></span>
            </li>
        </template>
    </ul>
</div>