<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full bg-slate-50">
        <div x-data="{ sidebarOpen: false }">
            <!-- Sidebar Component -->
            @include('layouts.sidebar')

            <div class="lg:pl-72">
                <!-- Mobile Header / Top Nav -->
                <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-slate-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" type="button" class="-m-2.5 p-2.5 text-slate-700 lg:hidden">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Separator (Mobile) -->
                    <div class="h-6 w-px bg-slate-200 lg:hidden" aria-hidden="true"></div>

                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <div class="flex flex-1"></div>
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <!-- User Profile Dropdown -->
                            <div class="relative">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="flex items-center gap-x-2 p-1.5 text-sm font-semibold leading-6 text-slate-900 hover:bg-slate-50 rounded-lg transition-colors duration-200">
                                            <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 border border-emerald-200">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                            <span class="hidden lg:flex lg:items-center">
                                                <span class="ml-1 text-sm font-semibold leading-6 text-slate-900" aria-hidden="true">{{ Auth::user()->name }}</span>
                                                <svg class="ml-2 h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                                            </span>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>

                <main class="py-10">
                    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                        <!-- Flash Messages (Global) -->
                        @if (session('success'))
                            <div class="mb-6 rounded-md bg-emerald-50 p-4 border border-emerald-200 shadow-sm animate-fade-in">
                                <div class="flex">
                                    <div class="shrink-0"><svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg></div>
                                    <div class="ml-3"><p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p></div>
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 rounded-md bg-rose-50 p-4 border border-rose-200 shadow-sm animate-fade-in">
                                <div class="flex">
                                    <div class="shrink-0"><svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-rose-800">Please correct the following errors:</h3>
                                        <ul class="mt-2 text-sm text-rose-700 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <script>
            document.addEventListener('keydown', function (e) {
                const activeTag = document.activeElement.tagName.toLowerCase();
                const isFormInput = ['input', 'textarea', 'select'].includes(activeTag);

                if (isFormInput) return;

                // 'N' -> New Transaction
                if (e.key.toLowerCase() === 'n') {
                    window.location.href = "{{ route('transactions.create') }}";
                }

                // '/' -> Focus Search
                if (e.key === '/') {
                    const search = document.getElementById('ledger-search');
                    if (search) {
                        e.preventDefault();
                        search.focus();
                        search.select();
                    }
                }
            });
        </script>
    </body>
</html>
