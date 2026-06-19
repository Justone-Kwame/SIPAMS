<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('roles.index') }}" wire:navigate class="p-2 -ml-2 rounded-full text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-800">{{ $roleId ? 'Edit Role' : 'Create Role' }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $roleId ? 'Update role details and permissions' : 'Define a new role with specific permissions' }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-700 mb-4">Role Details</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Role Name *</label>
                        <input type="text" wire:model="name" placeholder="e.g., manager"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white">
                        @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('roles.index') }}" wire:navigate class="flex-1 py-2.5 text-center rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button wire:click="save" class="flex-1 py-2.5 text-center rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow transition">
                    {{ $roleId ? 'Update Role' : 'Save Role' }}
                </button>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-700">Permissions</h3>
                <div class="flex items-center gap-2 text-xs">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-gray-600">
                        <input type="checkbox" wire:click="toggleAllGroups({{ in_array('products.view', $permissions) ? 'false' : 'true' }})"
                            class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <span class="font-semibold">All Permissions</span>
                    </label>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($permissionGroups as $group => $actions)
                    @php
                        $groupLower = strtolower($group);
                        $allGroupChecked = true;
                        foreach ($actions as $action) {
                            if (!in_array($groupLower . '.' . $action, $permissions)) {
                                $allGroupChecked = false;
                                break;
                            }
                        }
                    @endphp
                    <div class="border border-gray-100 rounded-lg overflow-hidden">
                        <div class="px-4 py-2.5 bg-gray-50 flex items-center justify-between">
                            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Manage {{ $group }}</h4>
                            <label class="inline-flex items-center gap-2 text-xs cursor-pointer text-gray-600">
                                <input type="checkbox" wire:click="toggleAllPermissions('{{ $group }}', {{ $allGroupChecked ? 'false' : 'true' }})"
                                    @checked($allGroupChecked)
                                    class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                <span class="font-semibold">Select All</span>
                            </label>
                        </div>

                        <div class="px-4 py-3 grid grid-cols-4 gap-3">
                            @foreach($actions as $action)
                                @php
                                    $permName = $groupLower . '.' . $action;
                                @endphp
                                <label class="inline-flex items-center gap-2 cursor-pointer text-gray-700">
                                    <input type="checkbox" wire:model="permissions" value="{{ $permName }}"
                                        @checked(in_array($permName, $permissions))
                                        class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                                    <span class="text-sm font-semibold">{{ ucwords($action) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
