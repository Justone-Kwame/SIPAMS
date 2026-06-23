<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Profit and Loss</h1>
            <p class="text-sm text-gray-500 mt-0.5">Sales, purchases, payments and net profit for the period.</p>
        </div>
        <a href="{{ route('reports.profit-loss.print', ['start' => $startDate, 'end' => $endDate]) }}"
           target="_blank"
           class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Preview
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                <input type="date" wire:model.live="startDate"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>
            <div class="flex-1 min-w-40">
                <label class="block text-sm font-semibold text-gray-700 mb-1">End Date</label>
                <input type="date" wire:model.live="endDate"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            </div>
        </div>
    </div>

    {{-- Statement --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @include('reports.partials.profit-loss-statement', ['pl' => $pl])
    </div>
</div>
