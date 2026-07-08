<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NeverMC')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&family=Geist+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#2563eb', 300: '#60a5fa', 400: '#3b82f6', 500: '#2563eb', 600: '#1d4ed8' },
                        ink: '#0a0a0a'
                    },
                    fontFamily: {
                        sans: ['Geist', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        mono: ['"Geist Mono"', 'ui-monospace', 'monospace']
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0a0a0a; -webkit-font-smoothing: antialiased; }
        .grid-faint { background-image: linear-gradient(to right, rgba(255,255,255,.04) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,.04) 1px, transparent 1px); background-size: 46px 46px; }
    </style>
</head>
<body class="font-sans text-white antialiased selection:bg-brand-500/30">
    <div class="grid min-h-screen lg:grid-cols-2">
        <!-- LEFT: form -->
        <div class="flex min-h-screen flex-col justify-center px-6 py-12 sm:px-10 lg:px-16">
            <div class="mx-auto w-full max-w-sm">
                <a href="/" class="mb-10 inline-flex items-center gap-2.5">
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand-500 text-white"><i class="ph-bold ph-pulse text-lg"></i></span>
                    <span class="text-[15px] font-bold tracking-tight">NeverMC</span>
                </a>
                @yield('content')
            </div>
        </div>
        <!-- RIGHT: brand panel -->
        <div class="relative flex h-full flex-col justify-center gap-10 p-14">
            <div class="absolute inset-0 bg-gradient-to-br from-[#0d1424] via-[#0a0a0a] to-[#0a0a0a]"></div>
            <div class="absolute inset-0 grid-faint opacity-40"></div>
            <div class="pointer-events-none absolute -right-24 -top-16 h-[460px] w-[460px] rounded-full bg-brand-500/15 blur-[130px]"></div>
            <div class="relative flex h-full flex-col justify-between p-14">
                <span class="text-[11px] font-medium uppercase tracking-[0.18em] text-white/40">Trading journal</span>

                <div class="max-w-md">
                    <h2 class="text-3xl font-bold leading-tight tracking-tight">The journal that shows you what actually works.</h2>
                    <div class="mt-10 space-y-6">
                        <div class="flex gap-4">
                            <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-lg border border-white/10 bg-white/[0.03] text-brand-400"><i class="ph ph-camera"></i></span>
                            <div>
                                <p class="text-sm font-semibold">Log every trade with proof</p>
                                <p class="mt-1 text-sm leading-relaxed text-white/45">Before and after screenshots on every entry.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-lg border border-white/10 bg-white/[0.03] text-brand-400"><i class="ph ph-chart-line-up"></i></span>
                            <div>
                                <p class="text-sm font-semibold">See your real edge</p>
                                <p class="mt-1 text-sm leading-relaxed text-white/45">Win rate, profit factor, and drawdown at a glance.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-lg border border-white/10 bg-white/[0.03] text-brand-400"><i class="ph ph-stack"></i></span>
                            <div>
                                <p class="text-sm font-semibold">All your accounts in one place</p>
                                <p class="mt-1 text-sm leading-relaxed text-white/45">Funded, challenge, and personal, side by side.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-white/30">Built for traders who keep receipts.</p>
            </div>
        </div>
    </div>
</body>
</html>
