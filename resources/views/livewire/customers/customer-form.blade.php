<div class="p-6 max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-800">
                {{ $customerId ? 'Edit Customer' : 'New Customer' }}
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">Fill in the details below to {{ $customerId ? 'update the' : 'register a new' }} customer.</p>
        </div>
        <a href="{{ route('customers.index') }}" wire:navigate
           class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Customers
        </a>
    </div>

    @if (session()->has('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-black uppercase tracking-wider text-gray-600">Customer Information</h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                        placeholder="e.g., John Doe">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                        <input type="text" wire:model="phone"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            placeholder="e.g., +233 24 123 4567">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            placeholder="e.g., john@example.com">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                    <textarea wire:model="address" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 resize-none"
                        placeholder="Enter customer address..."></textarea>
                </div>

                @if($customerId)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Loyalty Points</label>
                            <input type="number" min="0" wire:model="loyalty_points"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                placeholder="0">
                            @error('loyalty_points') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Total Spend (₵)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400 text-sm font-bold">₵</span>
                                <input type="number" step="0.01" min="0" wire:model="total_spend"
                                    class="w-full border border-gray-300 rounded-lg pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                                    placeholder="0.00">
                            </div>
                            @error('total_spend') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('customers.index') }}" wire:navigate
               class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow transition">
                <span wire:loading.remove wire:target="save">{{ $customerId ? 'Update Customer' : 'Save Customer' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>
