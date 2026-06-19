<div class="p-6 max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">
                {{ $expenseId ? 'Edit Expense' : 'Record Expense' }}
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">
                {{ $expenseId ? 'Update the expense details below.' : 'Track a business expenditure.' }}
            </p>
        </div>
        <a href="{{ route('expenses.index') }}" wire:navigate
           class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    @if (session()->has('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Expense Details</h2>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Expense Category --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Expense Category <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="expense_category_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        <option value="">— Select Category —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('expense_category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Expense Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Expense Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="title"
                        placeholder="e.g. Monthly Rent Payment"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Amount --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Amount (₵) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-sm font-bold">₵</span>
                        <input type="number" step="0.01" min="0.01" wire:model="amount"
                            placeholder="0.00"
                            class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" wire:model="date"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Time --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" wire:model="time"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    @error('time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Recorded By (read-only) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Recorded By</label>
                    <div class="flex items-center gap-2 h-10 px-3 border border-gray-200 rounded-lg bg-gray-50">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    </div>
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" rows="3"
                        placeholder="Optional notes about this expense…"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 resize-none"></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('expenses.index') }}" wire:navigate
               class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-sm font-black shadow transition">
                <span wire:loading.remove wire:target="save">
                    {{ $expenseId ? 'Update Expense' : 'Record Expense' }}
                </span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </form>
</div>
