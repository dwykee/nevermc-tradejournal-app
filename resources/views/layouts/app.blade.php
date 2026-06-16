<!DOCTYPE html>
<html lang="en" class="dark">
    <style>
        select option {
            background-color: #1a1a1a;
            color: #ffffff;
        }
        select option:checked {
            background-color: #2563eb; /* blue-600 */
            color: #ffffff;
        }
    </style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NeverMC') — NeverMC</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        background: '#0b0d12',

                        surface: '#0b0d12',
                        'surface-dim': '#0b0d12',

                        'surface-container-lowest': '#06070a',
                        'surface-container-low': '#11141b',
                        'surface-container': '#161a23',
                        'surface-container-high': '#1d2330',
                        'surface-container-highest': '#252c3c',

                        'surface-bright': '#2f3850',
                        'surface-variant': '#2a3142',

                        outline: '#7c8aa8',
                        'outline-variant': '#3a4257',

                        'on-surface': '#e6eaf5',
                        'on-surface-variant': '#b8c2d9',

                        'inverse-surface': '#e6eaf5',
                        'inverse-on-surface': '#2a3142',

                        primary: '#aac4ff',
                        'primary-container': '#3D63FF',

                        'on-primary': '#00205c',
                        'on-primary-container': '#EAF0FF',
                    }
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { background: #0f0f10; color: #e2e2e6; font-family: 'Inter', sans-serif; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

        /* Sidebar active state */
        .nav-link.active { background: rgba(124,106,255,0.12); color: #a99fff; }
        .nav-link.active svg { color: #7c6aff; }
        .nav-link:hover:not(.active) { background: rgba(255,255,255,0.04); }

        /* Stat card hover */
        .stat-card:hover { border-color: rgba(255,255,255,0.12); }

        /* Table row hover */
        .trade-row:hover { background: rgba(255,255,255,0.025); }

        /* Input focus */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: rgba(124,106,255,0.5) !important;
            box-shadow: 0 0 0 3px rgba(124,106,255,0.1);
        }

        /* Flash messages */
        .flash-success { background: rgba(52,211,153,0.1); border-color: rgba(52,211,153,0.25); color: #34d399; }
        .flash-error   { background: rgba(248,113,113,0.1); border-color: rgba(248,113,113,0.25); color: #f87171; }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex bg-surface">

    {{-- ── Sidebar ──────────────────────────────────────────────────────────── --}}
    <aside class="w-64 flex-shrink-0 flex flex-col border-r border-white/[0.07] bg-surface-1 fixed inset-y-0 left-0 z-30">

        {{-- Logo --}}
        <div class="h-16 flex items-center px-5 border-b border-white/[0.07]">
            <span class="text-lg font-semibold tracking-tight">
                <span class="text-accent">Never</span><span class="text-white">MC</span>
            </span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

            @php $seg = request()->segment(1); @endphp

            <a href="{{ route('dashboard') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/70 transition-colors {{ $seg === 'dashboard' ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M4 6h16M4 10h16M4 14h16M4 18h7"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('trades.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/70 transition-colors {{ $seg === 'trades' ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M3 3h18M3 9h18M3 15h11M3 21h7"/>
                </svg>
                Trades
            </a>

            <a href="{{ route('journal.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/70 transition-colors {{ $seg === 'journal' ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.966 8.966 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                Journal
            </a>

            <a href="{{ route('accounts.index') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/70 transition-colors {{ $seg === 'accounts' ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                </svg>
                Accounts
            </a>

            <div class="pt-3 pb-1 px-3">
                <span class="text-[10px] uppercase tracking-widest text-white/25 font-medium">Import</span>
            </div>

            <a href="{{ route('trades.import') }}"
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/70 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                </svg>
                Import CSV
            </a>
        </nav>

        {{-- User footer --}}
        <div class="px-3 py-3 border-t border-white/[0.07]">
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg">
                <div class="w-7 h-7 rounded-full bg-accent/20 flex items-center justify-center text-xs font-medium text-accent flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white/90 truncate font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/35 truncate">{{ Auth::user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white/30 hover:text-white/70 transition-colors" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main content ──────────────────────────────────────────────────────── --}}
    <main class="flex-1 ml-64 min-h-screen flex flex-col">

        {{-- Top bar --}}
        <header class="h-16 flex items-center px-8 border-b border-white/[0.07] bg-surface-1/50 backdrop-blur sticky top-0 z-20">
            <div class="flex-1">
                <h1 class="text-base font-medium text-white/90">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                @yield('header-actions')
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mx-8 mt-4 px-4 py-3 rounded-lg border flash-success text-sm">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error') || $errors->any())
        <div class="mx-8 mt-4 px-4 py-3 rounded-lg border flash-error text-sm">
            {{ session('error') ?? $errors->first() }}
        </div>
        @endif

        {{-- Page content --}}
        <div class="flex-1 px-6 xl:px-8 py-6 overflow-x-hidden">
            <div class="max-w-[1500px] mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    @stack('scripts')
</body>
</html>