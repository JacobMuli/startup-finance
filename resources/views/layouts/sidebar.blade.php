<div class="relative z-40 lg:hidden" role="dialog" aria-modal="true" x-show="sidebarOpen" x-cloak>
    <!-- Mobile Sidebar Drawer Background -->
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <div class="fixed inset-0 flex">
        <div class="relative mr-16 flex w-full max-w-xs flex-1" 
             x-show="sidebarOpen" 
             x-transition:enter="transition ease-in-out duration-300 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-300 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full"
             @click.away="sidebarOpen = false">
            
            <!-- Mobile Sidebar Content -->
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4 ring-1 ring-white/10">
                <div class="flex h-16 shrink-0 items-center">
                    <x-application-logo class="h-9 w-9 shrink-0" />
                    <span class="ml-3 text-lg font-black text-white tracking-tight">Startup Finance</span>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <a href="{{ route('transactions.create') }}" class="flex items-center justify-center gap-x-3 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-emerald-500 transition-all duration-200">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.25h-4.25a.75.75 0 000 1.5h4.25v4.25a.75.75 0 001.5 0v-4.25h4.25a.75.75 0 000-1.5h-4.25V4.75z" /></svg>
                                New Transaction
                            </a>
                        </li>
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                                        Dashboard
                                    </x-sidebar-link>
                                </li>
                                <li>
                                    <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-3.75 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                        Transactions
                                    </x-sidebar-link>
                                </li>
                                <li>
                                    <x-sidebar-link :href="route('reports.monthly')" :active="request()->routeIs('reports.monthly')">
                                        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0h-1.5m1.5 0h1.5m-7.450-3h1.450 m0 0h3.5m-7-3h7m-7-3h7" /></svg>
                                        Monthly Reports
                                    </x-sidebar-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Static Sidebar for Desktop -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-72 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4">
        <div class="flex h-16 shrink-0 items-center">
            <x-application-logo class="h-9 w-9 shrink-0" />
            <span class="ml-3 text-lg font-black text-white tracking-tight">Startup Finance</span>
        </div>
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <a href="{{ route('transactions.create') }}" class="group flex items-center justify-center gap-x-3 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-emerald-500 transition-all duration-200">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.25h-4.25a.75.75 0 000 1.5h4.25v4.25a.75.75 0 001.5 0v-4.25h4.25a.75.75 0 000-1.5h-4.25V4.75z" /></svg>
                        New Transaction
                    </a>
                </li>
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <li>
                            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                                Dashboard
                            </x-sidebar-link>
                        </li>
                        <li>
                            <x-sidebar-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-3.75 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                Transactions
                            </x-sidebar-link>
                        </li>
                        <li>
                            <x-sidebar-link :href="route('reports.monthly')" :active="request()->routeIs('reports.monthly')">
                                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0h-1.5m1.5 0h1.5m-7.450-3h1.450 m0 0h3.5m-7-3h7m-7-3h7" /></svg>
                                Monthly Reports
                            </x-sidebar-link>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
