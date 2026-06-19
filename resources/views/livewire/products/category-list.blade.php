<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">Categories</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage product categories.</p>
        </div>
        <a href="{{ route('categories.create') }}" wire:navigate
            class="flex items-center gap-2 px-4 py-2.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Category
        </a>
    </div>

    @if(session()->has('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-48">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search categories..."
                class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Name</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600 uppercase tracking-wider text-xs">Description</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-600 uppercase tracking-wider text-xs">Products</th>
                        <th class="px-4 py-3 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-semibold text-gray-800">
                                {{ $category->name }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $category->description ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right text-gray-600">
                                {{ $category->products_count ?? $category->products()->count() }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('categories.edit', $category->id) }}" wire:navigate
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-teal-600 hover:bg-teal-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $category->id }})"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <p class="text-sm font-semibold">No categories found</p>
                                <p class="text-xs mt-1">Try adjusting your search or <a href="{{ route('categories.create') }}" wire:navigate class="text-teal-600 hover:underline">add a new category</a>.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    @if($confirmDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-gray-800 text-center">Delete Category?</h3>
                <p class="text-sm text-gray-500 text-center mt-1 mb-5">This will permanently delete the category. Products in this category will not be deleted, but will no longer be assigned to a category.</p>
                <div class="flex gap-3">
                    <button wire:click="cancelDelete" class="flex-1 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                    <button wire:click="delete" class="flex-1 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-bold shadow transition">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
