<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Audit Trail</h1>
            <p class="text-sm text-gray-500 mt-0.5">View all system activity and user actions.</p>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-48">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search by user, activity, or description..."
                class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <button wire:click="sortBy('created_at')" class="flex items-center gap-1 font-bold text-gray-600 hover:text-gray-900 uppercase tracking-wider text-xs">
                                Date & Time
                                @if($sortBy === 'created_at') <span class="text-teal-500">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">User</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Activity</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Description</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($audits as $audit)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $audit->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $audit->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                {{ $audit->user ? $audit->user->name : 'System' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-700">
                                    {{ $audit->activity }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $audit->description }}
                            </td>
                            <td class="px-4 py-3 text-gray-400 font-mono text-xs">
                                {{ $audit->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm font-semibold">No audit records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($audits->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $audits->links() }}
            </div>
        @endif
    </div>
</div>
