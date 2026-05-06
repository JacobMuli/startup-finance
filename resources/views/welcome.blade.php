<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Startup Finance') }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">
        <meta name="theme-color" content="#020617">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-white">
        <div class="min-h-screen">
            <section class="relative min-h-[88vh] overflow-hidden bg-slate-950">
                <div class="absolute inset-0 opacity-25">
                    <div class="grid h-full grid-cols-6 border-l border-slate-800/80">
                        @for ($i = 0; $i < 6; $i++)
                            <div class="border-r border-slate-800/80"></div>
                        @endfor
                    </div>
                </div>

                <div class="absolute inset-x-0 bottom-0 hidden h-[58%] border-t border-slate-800/80 lg:block">
                    <div class="mx-auto grid h-full max-w-7xl grid-cols-12 text-[10px] font-black uppercase tracking-widest text-slate-600">
                        @foreach (['MRR', 'Burn', 'Runway', 'Cash', 'COGS', 'ARR', 'Margin', 'Payroll', 'Ops', 'Tax', 'AR', 'AP'] as $label)
                            <div class="border-r border-slate-800/70 px-3 py-4">{{ $label }}</div>
                        @endforeach
                    </div>
                </div>

                <header class="relative z-10 mx-auto flex max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-emerald-400/30 bg-emerald-400/10 text-sm font-black text-emerald-300">SF</div>
                        <div>
                            <div class="text-sm font-black uppercase tracking-widest text-white">Startup Finance</div>
                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Founder treasury console</div>
                        </div>
                    </a>

                    @if (Route::has('login'))
                        <nav class="flex items-center gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-xl bg-white px-4 py-2.5 text-xs font-black uppercase tracking-widest text-slate-950 shadow-sm transition hover:bg-emerald-100">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="hidden rounded-xl px-4 py-2.5 text-xs font-black uppercase tracking-widest text-slate-300 transition hover:text-white sm:inline-flex">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="rounded-xl bg-white px-4 py-2.5 text-xs font-black uppercase tracking-widest text-slate-950 shadow-sm transition hover:bg-emerald-100">Register</a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>

                <div class="relative z-10 mx-auto grid max-w-7xl items-center gap-12 px-6 pb-16 pt-10 lg:grid-cols-[1fr_0.92fr] lg:px-8 lg:pb-24 lg:pt-20">
                    <div class="max-w-4xl">
                        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-emerald-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                            Cash flow intelligence for early teams
                        </div>

                        <h1 class="max-w-5xl text-5xl font-black leading-[0.95] tracking-tight text-white sm:text-6xl lg:text-7xl">
                            Startup Finance
                        </h1>

                        <p class="mt-6 max-w-2xl text-base font-medium leading-8 text-slate-300 sm:text-lg">
                            Track revenue, expenses, burn rate, runway, receipts, and monthly performance in one disciplined finance workspace built for founders who need signal before spreadsheets become a mess.
                        </p>

                        <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-400 px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-950 shadow-lg shadow-emerald-950/40 transition hover:bg-emerald-300">Enter dashboard</a>
                                <a href="{{ route('transactions.create') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:border-emerald-300 hover:text-emerald-200">Record transaction</a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-400 px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-950 shadow-lg shadow-emerald-950/40 transition hover:bg-emerald-300">Create finance room</a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:border-emerald-300 hover:text-emerald-200">Access ledger</a>
                            @endauth
                        </div>

                        <div class="mt-12 grid max-w-3xl grid-cols-3 divide-x divide-slate-800 rounded-2xl border border-slate-800 bg-slate-900/70">
                            <div class="p-4">
                                <div class="text-2xl font-black text-white">30d</div>
                                <div class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-500">Forecast window</div>
                            </div>
                            <div class="p-4">
                                <div class="text-2xl font-black text-emerald-300">KES</div>
                                <div class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-500">Native reporting</div>
                            </div>
                            <div class="p-4">
                                <div class="text-2xl font-black text-amber-300">3 mo</div>
                                <div class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-500">Burn baseline</div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="rounded-2xl border border-slate-700 bg-slate-900 shadow-2xl shadow-black/50">
                            <div class="flex items-center justify-between border-b border-slate-800 px-5 py-4">
                                <div>
                                    <div class="text-xs font-black uppercase tracking-widest text-white">Command Ledger</div>
                                    <div class="mt-1 text-[10px] font-bold uppercase tracking-widest text-slate-500">May operating view</div>
                                </div>
                                <div class="rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-200">Live</div>
                            </div>

                            <div class="grid grid-cols-2 border-b border-slate-800">
                                <div class="border-r border-slate-800 p-5">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-500">Net cash</div>
                                    <div class="mt-3 text-3xl font-black text-white">KES 4.82M</div>
                                    <div class="mt-3 inline-flex rounded-full bg-emerald-400/10 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-300">+18.4%</div>
                                </div>
                                <div class="p-5">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-500">Runway</div>
                                    <div class="mt-3 text-3xl font-black text-cyan-200">14.7 mo</div>
                                    <div class="mt-3 inline-flex rounded-full bg-cyan-400/10 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-cyan-200">Stable</div>
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="mb-4 flex items-center justify-between">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-500">Cash movement</div>
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-600">Revenue / Expense</div>
                                </div>
                                <div class="space-y-3">
                                    @foreach ([['May', 'w-11/12', 'w-5/12'], ['Apr', 'w-9/12', 'w-6/12'], ['Mar', 'w-8/12', 'w-4/12'], ['Feb', 'w-6/12', 'w-7/12']] as [$month, $in, $out])
                                        <div class="grid grid-cols-[3rem_1fr] items-center gap-3">
                                            <div class="text-xs font-black uppercase tracking-widest text-slate-500">{{ $month }}</div>
                                            <div class="space-y-1.5">
                                                <div class="h-2 rounded-full bg-slate-800">
                                                    <div class="h-2 rounded-full bg-emerald-400 {{ $in }}"></div>
                                                </div>
                                                <div class="h-2 rounded-full bg-slate-800">
                                                    <div class="h-2 rounded-full bg-rose-400 {{ $out }}"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-3 border-t border-slate-800">
                                <div class="p-4">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-500">Top outflow</div>
                                    <div class="mt-2 text-sm font-black text-white">Payroll</div>
                                </div>
                                <div class="border-x border-slate-800 p-4">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-500">Margin</div>
                                    <div class="mt-2 text-sm font-black text-emerald-300">62%</div>
                                </div>
                                <div class="p-4">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-500">Receipts</div>
                                    <div class="mt-2 text-sm font-black text-amber-300">Verified</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white text-slate-950">
                <div class="mx-auto grid max-w-7xl gap-8 px-6 py-16 lg:grid-cols-3 lg:px-8">
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Financial operating system</div>
                        <h2 class="mt-3 text-3xl font-black tracking-tight">Every finance workflow has a place.</h2>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="text-sm font-black uppercase tracking-widest text-slate-900">Ledger discipline</div>
                        <p class="mt-3 text-sm font-medium leading-7 text-slate-600">Categorize every inflow and outflow, attach receipts, filter by account, and keep founder-ready records without spreadsheet drift.</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="text-sm font-black uppercase tracking-widest text-slate-900">Runway control</div>
                        <p class="mt-3 text-sm font-medium leading-7 text-slate-600">See global burn, cash position, monthly performance, and scenario-filtered metrics from the same transaction source.</p>
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>
