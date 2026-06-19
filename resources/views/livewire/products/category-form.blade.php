<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.index') }}" wire:navigate class="p-2 -ml-2 rounded-full text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-800">{{ $categoryId ? 'Edit Category' : 'New Category' }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $categoryId ? 'Update category details.' : 'Add a new product category.' }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-xl">
        <form wire:submit.prevent="save" class="space-y-5 bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Category Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Description</label>
                <textarea wire:model="description" rows="4" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"></textarea>
                @error('description') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="{{ route('categories.index') }}" wire:navigate class="flex-1 py-2.5 text-center rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="flex-1 py-2.5 text-center rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow transition">
                    {{ $categoryId ? 'Update Category' : 'Create Category' }}
                </button>
            </div>
        </form>
    </div>
</div>
