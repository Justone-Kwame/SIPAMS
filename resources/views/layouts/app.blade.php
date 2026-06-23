<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIPAMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">

        <div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

            {{-- ═══════════ SIDEBAR ═══════════ --}}
            <aside
                :class="sidebarOpen ? 'w-64' : 'w-16'"
                class="relative flex flex-col flex-shrink-0 transition-all duration-300 ease-in-out overflow-hidden"
                style="background:#0e2a38;"
            >
                {{-- Logo / Brand --}}
                <div class="flex items-center gap-3 px-4 h-16 border-b flex-shrink-0" style="border-color:#1a3e52;">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 min-w-0">
                        <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-teal-500 text-white font-black text-sm">
                            {{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}
                        </span>
                        <span
                            x-show="sidebarOpen"
                            x-transition:enter="transition-opacity duration-200 delay-100"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-100"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="font-black text-white text-sm uppercase tracking-widest truncate"
                        >
                            {{ config('app.name', 'Laravel') }}
                        </span>
                    </a>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 overflow-y-auto overflow-x-hidden py-4 space-y-1 px-2">

                    {{-- Dashboard --}}
                    <x-sidebar-link route="dashboard" :active="request()->routeIs('dashboard')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </x-slot>
                        Dashboard
                    </x-sidebar-link>

                    {{-- Point of Sale --}}
                    <x-sidebar-link route="pos.index" :active="request()->routeIs('pos.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </x-slot>
                        Point of Sale
                    </x-sidebar-link>

                    {{-- Categories --}}
                    <x-sidebar-link route="categories.index" :active="request()->routeIs('categories.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </x-slot>
                        Categories
                    </x-sidebar-link>

                    {{-- Products --}}
                    <x-sidebar-link route="products.index" :active="request()->routeIs('products.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </x-slot>
                        Products
                    </x-sidebar-link>

                    {{-- Inventory --}}
                    <x-sidebar-link route="inventory.index" :active="request()->routeIs('inventory.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </x-slot>
                        Inventory
                    </x-sidebar-link>

                    {{-- Suppliers --}}
                    <x-sidebar-link route="suppliers.index" :active="request()->routeIs('suppliers.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </x-slot>
                        Suppliers
                    </x-sidebar-link>

                    {{-- Purchases --}}
                    <x-sidebar-link route="purchases.index" :active="request()->routeIs('purchases.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </x-slot>
                        Purchases
                    </x-sidebar-link>

                    {{-- Sales Returns --}}
                    <x-sidebar-link route="returns.sales" :active="request()->routeIs('returns.sales')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a4 4 0 014 4v1m-7-9l-3 3 3 3"/>
                            </svg>
                        </x-slot>
                        Sales Returns
                    </x-sidebar-link>

                    {{-- Purchase Returns --}}
                    <x-sidebar-link route="returns.purchases" :active="request()->routeIs('returns.purchases')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 10H11a4 4 0 00-4 4v1m7-9l3 3-3 3"/>
                            </svg>
                        </x-slot>
                        Purchase Returns
                    </x-sidebar-link>

                    {{-- Customers --}}
                    <x-sidebar-link route="roles.index" :active="request()->routeIs('roles.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 018.382 3.042M9 12l2 2 4-4m5.618 4.016A11.955 11.955 0 0112 21.056a11.955 11.955 0 018.382-3.042"/>
                            </svg>
                        </x-slot>
                        Roles & Permissions
                    </x-sidebar-link>
                    <x-sidebar-link route="users.index" :active="request()->routeIs('users.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </x-slot>
                        Users
                    </x-sidebar-link>

                    {{-- Expenses --}}
                    <x-sidebar-link route="expenses.index" :active="request()->routeIs('expenses.*')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </x-slot>
                        Expenses
                    </x-sidebar-link>

                    {{-- Accounting section --}}
                    <div class="pt-3">
                        <p x-show="sidebarOpen" class="px-2 mb-1 text-xs font-bold uppercase tracking-widest" style="color:#4a7c94;">Accounting</p>
                        <p x-show="!sidebarOpen" class="px-2 mb-1 border-t" style="border-color:#1a3e52;"></p>
                    </div>

                    <x-sidebar-link route="accounting.profit-loss" :active="request()->routeIs('accounting.profit-loss')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m4 11H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                            </svg>
                        </x-slot>
                        Profit and Loss
                    </x-sidebar-link>

                    {{-- Reports section --}}
                    <div class="pt-3">
                        <p x-show="sidebarOpen" class="px-2 mb-1 text-xs font-bold uppercase tracking-widest" style="color:#4a7c94;">Reports</p>
                        <p x-show="!sidebarOpen" class="px-2 mb-1 border-t" style="border-color:#1a3e52;"></p>
                    </div>

                    <x-sidebar-link route="reports.sales" :active="request()->routeIs('reports.sales')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </x-slot>
                        Sales Report
                    </x-sidebar-link>

                    <x-sidebar-link route="reports.inventory" :active="request()->routeIs('reports.inventory')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </x-slot>
                        Inventory Report
                    </x-sidebar-link>

                    <x-sidebar-link route="reports.financial" :active="request()->routeIs('reports.financial')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </x-slot>
                        Financial Report
                    </x-sidebar-link>

                    <x-sidebar-link route="reports.profit-loss" :active="request()->routeIs('reports.profit-loss')" :open="true">
                        <x-slot name="icon">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m4 11H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                            </svg>
                        </x-slot>
                        Profit and Loss
                    </x-sidebar-link>

                    <x-sidebar-link route="reports.product-movement" :active="request()->routeIs('reports.product-movement')" :open="true">
        <x-slot name="icon">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </x-slot>
        Product Movement
    </x-sidebar-link>
    <x-sidebar-link route="reports.audit-trail" :active="request()->routeIs('reports.audit-trail')" :open="true">
        <x-slot name="icon">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h3m3 0h3M9 16h6m-9-8h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2V10a2 2 0 012-2z"/>
            </svg>
        </x-slot>
        Audit Trail
    </x-sidebar-link>

                </nav>

                {{-- User footer --}}
                <div class="flex-shrink-0 border-t px-3 py-3" style="border-color:#1a3e52;">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-white text-xs font-bold uppercase">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div x-show="sidebarOpen" class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? '' }}</p>
                            <p class="text-xs truncate" style="color:#4a7c94;">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <div x-show="sidebarOpen" class="flex-shrink-0 flex gap-1">
                            <a href="{{ route('profile') }}" wire:navigate title="Profile"
                               class="p-1 rounded hover:bg-white/10 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </a>
                            <livewire:layout.navigation />
                        </div>
                    </div>
                </div>

                {{-- Collapse toggle --}}
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="absolute -right-3 top-20 z-10 w-6 h-6 rounded-full flex items-center justify-center shadow-md transition hover:scale-110"
                    style="background:#14b8a6; color:#fff;"
                    title="Toggle sidebar"
                >
                    <svg :class="sidebarOpen ? 'rotate-0' : 'rotate-180'" class="w-3 h-3 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </aside>

            {{-- ═══════════ MAIN CONTENT ═══════════ --}}
            <div class="flex flex-col flex-1 overflow-hidden">

                {{-- Top bar --}}
                <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200 flex-shrink-0">
                    <div>
                        @if (isset($header))
                            {{ $header }}
                        @else
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                {{ config('app.name', 'SIPAMS') }}
                            </h2>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </header>

                {{-- Page content --}}
                <main class="flex-1 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>

        </div>

    </body>
</html>
