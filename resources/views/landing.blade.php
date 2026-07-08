<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NeverMC - Stop guessing, start building your edge</title>
    <meta name="description" content="NeverMC logs every trade, screenshot, and emotion, then shows you the setups that actually make money.">

    <!-- Dev convenience: Tailwind Play CDN + Phosphor icons + Geist fonts. For production, move these into your Vite pipeline. -->
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
                        brand: { DEFAULT: '#2563eb', 400: '#3b82f6', 500: '#2563eb', 600: '#1d4ed8' },
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
        @keyframes revUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
        .reveal { animation: revUp .7s cubic-bezier(.16,1,.3,1) both; }
        .tnum { font-variant-numeric: tabular-nums; }
        .grid-faint { background-image: linear-gradient(to right, rgba(255,255,255,.045) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,.045) 1px, transparent 1px); background-size: 46px 46px; }
        .text-gradient { background: linear-gradient(180deg, #ffffff 0%, rgba(255,255,255,.55) 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
        @media (prefers-reduced-motion: reduce) {
            .reveal { animation: none !important; opacity: 1 !important; transform: none !important; }
            html { scroll-behavior: auto; }
        }
    </style>
</head>
<body class="font-sans text-white antialiased selection:bg-brand-500/30">

    <!-- NAV -->
    <header class="fixed top-0 inset-x-0 z-50 border-b border-white/[0.06] bg-ink/70 backdrop-blur-xl">
        <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
            <a href="/" class="flex items-center gap-2.5">
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand-500 text-white"><i class="ph-bold ph-pulse text-lg"></i></span>
                <span class="text-[15px] font-bold tracking-tight">NeverMC</span>
            </a>
            <div class="hidden items-center gap-8 text-sm text-white/55 md:flex">
                <a href="#dashboard" class="transition hover:text-white">Dashboard</a>
                <a href="#features" class="transition hover:text-white">Features</a>
                <a href="#how" class="transition hover:text-white">How it works</a>
                <a href="#traders" class="transition hover:text-white">Traders</a>
            </div>
            <div class="flex items-center gap-2">
                <a href="/login" class="hidden rounded-lg px-3.5 py-2 text-sm font-medium text-white/70 transition hover:text-white sm:block">Sign in</a>
                <a href="/register" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-ink transition hover:bg-white/90">Get started</a>
            </div>
        </nav>
    </header>

    <!-- HERO -->
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 grid-faint opacity-50 [mask-image:radial-gradient(ellipse_at_top,black,transparent_65%)]"></div>
        <div class="pointer-events-none absolute left-1/2 top-0 h-[420px] w-[820px] -translate-x-1/2 rounded-full bg-brand-500/10 blur-[120px]"></div>
        <div class="mb-16 relative mx-auto flex max-w-3xl flex-col items-center px-6 pt-40 pb-12 text-center">
            <h1 class="text-5xl font-extrabold leading-[1.02] tracking-tight sm:text-7xl text-gradient">
                Stop Margin-call,<br>start your real trades here.
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/55">
                NeverMC logs every trade, screenshot, and emotion, then shows you the setups that actually make money.
            </p>
            <div class="mt-9 flex flex-wrap items-center justify-center gap-3">
                <a href="/register" class="group inline-flex items-center gap-2 rounded-xl bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-400">
                    Get started free <i class="ph-bold ph-arrow-right transition group-hover:translate-x-0.5"></i>
                </a>
                <a href="#how" class="inline-flex items-center gap-2 rounded-xl border border-white/15 px-6 py-3 text-sm font-semibold text-white/80 transition hover:border-white/30 hover:text-white">
                    See how it works
                </a>
            </div>
        </div>
    </section>

    <!-- DASHBOARD PREVIEW (hero product shot) -->
    <section id="dashboard" class="relative px-6 pb-24">
        <div class="pointer-events-none absolute inset-x-0 top-0 mx-auto h-72 max-w-4xl rounded-full bg-brand-500/10 blur-[120px]"></div>
        <div class="reveal relative mx-auto max-w-5xl">
            <div class="overflow-hidden rounded-2xl border border-white/[0.08] bg-[#0e0e0e] shadow-2xl shadow-black/70 ring-1 ring-white/[0.02]">
                <div class="flex items-center justify-between border-b border-white/[0.06] px-5 py-3.5">
                    <span class="text-sm font-semibold text-white/80">Dashboard</span>
                    <span class="rounded-md border border-white/[0.08] px-2.5 py-1 text-[11px] text-white/40">All accounts</span>
                </div>
                <div class="p-5">
                    <!-- top stat row -->
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                            <p class="text-[11px] uppercase tracking-[0.15em] text-white/40">Net P&amp;L</p>
                            <p class="mt-1.5 text-2xl font-bold tnum font-mono text-emerald-400">+$58.00</p>
                            <p class="mt-1 text-[11px] text-white/35">after $0.00 commission</p>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                            <p class="text-[11px] uppercase tracking-[0.15em] text-white/40">Win Rate</p>
                            <p class="mt-1.5 text-2xl font-bold tnum font-mono">50%</p>
                            <p class="mt-1 text-[11px] text-white/35">1W / 1L of 2 trades</p>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                            <p class="text-[11px] uppercase tracking-[0.15em] text-white/40">Profit Factor</p>
                            <p class="mt-1.5 text-2xl font-bold tnum font-mono">1.41</p>
                            <p class="mt-1 text-[11px] text-white/35">avg win $200.00</p>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                            <p class="text-[11px] uppercase tracking-[0.15em] text-white/40">Drawdown</p>
                            <p class="mt-1.5 text-2xl font-bold tnum font-mono text-red-400">-$142.00</p>
                            <p class="mt-1 text-[11px] text-white/35">worst trade -$142.00</p>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                            <p class="text-[11px] uppercase tracking-[0.15em] text-white/40">Avg Trade</p>
                            <p class="mt-1.5 text-xl font-bold tnum font-mono text-emerald-400">+$29.00</p>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                            <p class="text-[11px] uppercase tracking-[0.15em] text-white/40">Best Trade</p>
                            <p class="mt-1.5 text-xl font-bold tnum font-mono text-emerald-400">+$200.00</p>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                            <p class="text-[11px] uppercase tracking-[0.15em] text-white/40">Current Streak</p>
                            <p class="mt-1.5 text-xl font-bold">1 Win</p>
                        </div>
                    </div>
                    <!-- equity + instrument -->
                    <div class="mt-3 grid gap-3 lg:grid-cols-3">
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4 lg:col-span-2">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-white/80">Equity Curve</span>
                                <span class="text-[11px] text-white/30">Cumulative P&amp;L</span>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex flex-col justify-between py-1 text-[10px] tnum font-mono text-white/30">
                                    <span>$100</span><span>$50</span><span>$0</span><span>-$50</span><span>-$100</span><span>-$150</span>
                                </div>
                                <div class="flex-1">
                                    <svg viewBox="0 0 600 170" class="h-40 w-full" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="dashEq" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#10b981" stop-opacity="0.30"></stop>
                                                <stop offset="100%" stop-color="#10b981" stop-opacity="0"></stop>
                                            </linearGradient>
                                        </defs>
                                        <line x1="0" y1="57" x2="600" y2="57" stroke="rgba(255,255,255,.05)"></line>
                                        <line x1="0" y1="113" x2="600" y2="113" stroke="rgba(255,255,255,.05)"></line>
                                        <path d="M0,150 L150,140 L300,120 L450,86 L600,40 L600,170 L0,170 Z" fill="url(#dashEq)"></path>
                                        <path d="M0,150 L150,140 L300,120 L450,86 L600,40" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="mt-1 flex justify-between text-[10px] tnum font-mono text-white/30"><span>2026-06-15</span><span>2026-06-16</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                            <span class="text-sm font-semibold text-white/80">By Instrument</span>
                            <div class="mt-5 flex items-center gap-3">
                                <span class="w-8 text-xs font-medium text-white/60">NQ</span>
                                <div class="h-2 flex-1 overflow-hidden rounded-full bg-white/10"><div class="h-full rounded-full bg-emerald-400" style="width:82%"></div></div>
                                <span class="text-xs font-semibold tnum font-mono text-emerald-400">+$58</span>
                            </div>
                        </div>
                    </div>
                    <!-- daily p&l -->
                    <div class="mt-3 rounded-xl border border-white/[0.06] bg-white/[0.02] p-4">
                        <span class="text-sm font-semibold text-white/80">Daily P&amp;L</span>
                        <div class="mt-4 grid grid-cols-2 gap-10">
                            <div class="flex h-28 flex-col">
                                <div class="flex flex-1 items-end justify-center"><div class="w-2/3 rounded-t bg-red-400/70" style="height:46%"></div></div>
                                <div class="mt-2 border-t border-white/10 pt-2 text-center"><p class="text-[11px] tnum font-mono text-white/35">06-15</p><p class="text-xs font-semibold tnum font-mono text-red-400">-$142</p></div>
                            </div>
                            <div class="flex h-28 flex-col">
                                <div class="flex flex-1 items-end justify-center"><div class="w-2/3 rounded-t bg-emerald-400" style="height:66%"></div></div>
                                <div class="mt-2 border-t border-white/10 pt-2 text-center"><p class="text-[11px] tnum font-mono text-white/35">06-16</p><p class="text-xs font-semibold tnum font-mono text-emerald-400">+$200</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="mt-3 text-center text-xs text-white/30">Live preview with sample data.</p>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="mx-auto max-w-6xl px-6 py-24">
        <div class="reveal mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Everything you need to find your edge</h2>
            <p class="mt-4 text-lg text-white/50">Log the trade, study the chart, and let the data tell you what to keep doing.</p>
        </div>
        <div class="mt-14 grid gap-4 md:grid-cols-3">
            <div class="reveal group rounded-2xl border border-white/[0.06] bg-white/[0.02] p-6 transition hover:border-white/[0.12] md:col-span-2">
                <div class="flex items-center gap-2 text-emerald-400"><i class="ph ph-camera text-xl"></i><span class="text-[11px] font-medium uppercase tracking-[0.15em] text-white/40">Trade log</span></div>
                <h3 class="mt-3 text-xl font-semibold">Log every trade with proof</h3>
                <p class="mt-2 max-w-md text-sm leading-relaxed text-white/50">Attach a before and after screenshot to each entry so your review is grounded in what the chart actually did, not how you remember it.</p>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="flex aspect-[16/9] items-center justify-center rounded-lg border border-white/[0.06] bg-[#0e0e0e] text-white/25"><div class="text-center"><i class="ph ph-chart-line-up text-2xl"></i><p class="mt-1 text-[10px] uppercase tracking-widest">Before</p></div></div>
                    <div class="flex aspect-[16/9] items-center justify-center rounded-lg border border-emerald-500/20 bg-emerald-500/[0.06] text-emerald-400/70"><div class="text-center"><i class="ph ph-check-fat text-2xl"></i><p class="mt-1 text-[10px] uppercase tracking-widest">After</p></div></div>
                </div>
            </div>
            <div class="reveal group rounded-2xl border border-white/[0.06] bg-white/[0.02] p-6 transition hover:border-white/[0.12]">
                <div class="flex items-center gap-2 text-emerald-400"><i class="ph ph-calendar-dots text-xl"></i><span class="text-[11px] font-medium uppercase tracking-[0.15em] text-white/40">Calendar</span></div>
                <h3 class="mt-3 text-xl font-semibold">Spot your patterns</h3>
                <p class="mt-2 text-sm leading-relaxed text-white/50">A heatmap of daily results shows which days you trade well and which you should sit out.</p>
                <div id="heatmap" class="mt-5 grid grid-cols-12 gap-1"></div>
            </div>
            <div class="reveal group rounded-2xl border border-white/[0.06] bg-white/[0.02] p-6 transition hover:border-white/[0.12]">
                <div class="flex items-center gap-2 text-brand-400"><i class="ph ph-notebook text-xl"></i><span class="text-[11px] font-medium uppercase tracking-[0.15em] text-white/40">Journal</span></div>
                <h3 class="mt-3 text-xl font-semibold">Capture the mindset</h3>
                <p class="mt-2 text-sm leading-relaxed text-white/50">Write the thesis and the emotion behind every position. Discipline starts with honesty.</p>
            </div>
            <div class="reveal group rounded-2xl border border-white/[0.06] bg-white/[0.02] p-6 transition hover:border-white/[0.12]">
                <div class="flex items-center gap-2 text-emerald-400"><i class="ph ph-file-csv text-xl"></i><span class="text-[11px] font-medium uppercase tracking-[0.15em] text-white/40">CSV import</span></div>
                <h3 class="mt-3 text-xl font-semibold">Bring your history</h3>
                <p class="mt-2 text-sm leading-relaxed text-white/50">Upload a CSV export and NeverMC maps the columns for you, so day one already has your full track record.</p>
            </div>
        </div>
    </section>

    <!-- ACCOUNTS SHOWCASE + process loop -->
    <section id="accounts" class="border-y border-white/[0.06] bg-white/[0.015]">
        <div class="mx-auto max-w-6xl px-6 py-24">
            <!-- row 1: intro + screenshot (2 kolom) -->
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div class="reveal">
                    <span class="text-[11px] font-medium uppercase tracking-[0.15em] text-white/40">Multi-account</span>
                    <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Manage every account in one view</h2>
                    <p class="mt-4 text-lg leading-relaxed text-white/50">Funded, challenge, or personal, track them side by side. See balance, net P&amp;L, and status at a glance.</p>
                    <ul class="mt-6 space-y-3 text-sm text-white/60">
                        <li class="flex items-center gap-3"><i class="ph-bold ph-check text-emerald-400"></i> Combined balance across all accounts</li>
                        <li class="flex items-center gap-3"><i class="ph-bold ph-check text-emerald-400"></i> Per-account net P&amp;L, color coded</li>
                        <li class="flex items-center gap-3"><i class="ph-bold ph-check text-emerald-400"></i> Funded and challenge tracking</li>
                    </ul>
                </div>
                <div class="reveal">
                    <div class="overflow-hidden rounded-2xl border border-white/[0.08] bg-[#0e0e0e] shadow-2xl shadow-black/60">
                        <img src="/images/nevermc-accounts.png" alt="NeverMC accounts dashboard" class="w-full" loading="lazy">
                    </div>
                </div>
            </div>

            <!-- row 2: process loop (full width, 3 kartu) -->
            <div id="how">
                <div class="mt-20 grid gap-6 md:grid-cols-3">
                    <div class="reveal rounded-2xl border border-white/[0.06] bg-white/[0.02] p-6">
                        <i class="ph ph-pencil-simple-line text-3xl text-brand-400"></i>
                        <h3 class="mt-4 text-lg font-semibold">Log the trade</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/50">Entry, exit, size, screenshots, and the thesis behind it. Two minutes while it is fresh.</p>
                    </div>
                    <div class="reveal rounded-2xl border border-white/[0.06] bg-white/[0.02] p-6">
                        <i class="ph ph-magnifying-glass text-3xl text-emerald-400"></i>
                        <h3 class="mt-4 text-lg font-semibold">Review the data</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/50">Open the analytics and the calendar. Find the setups, sessions, and emotions that cost you.</p>
                    </div>
                    <div class="reveal rounded-2xl border border-white/[0.06] bg-white/[0.02] p-6">
                        <i class="ph ph-trend-up text-3xl text-brand-400"></i>
                        <h3 class="mt-4 text-lg font-semibold">Refine the edge</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/50">Cut what loses, size up what works, and let the next month build on the last.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
<section id="traders" class="border-y border-white/[0.06] bg-white/[0.015]">
    <div class="mx-auto max-w-6xl px-6 py-24">
        <div class="reveal mx-auto max-w-2xl text-center">
            <span class="text-[11px] font-medium uppercase tracking-[0.15em] text-white/40">Traders</span>
            <h2 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Loved by disciplined traders</h2>
            <p class="mt-4 text-lg text-white/50">Real reviews from traders who turned their journal into an edge.</p>
        </div>
        <div class="mt-14 grid gap-6 md:grid-cols-2">
            <figure class="reveal rounded-2xl border border-white/[0.06] bg-white/[0.02] p-8">
                <div class="flex gap-1 text-emerald-400"><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i></div>
                <blockquote class="mt-4 text-lg leading-relaxed text-white/80">The screenshot log changed how I review. I stopped trusting my memory and started trusting the chart, and my win rate followed.</blockquote>
                <figcaption class="mt-6 flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-brand-500/20 text-sm font-semibold text-brand-400">JA</span>
                    <div><p class="text-sm font-semibold">Jafar Arif Hidayat</p><p class="text-xs text-white/40">Futures day trader</p></div>
                </figcaption>
            </figure>
            <figure class="reveal rounded-2xl border border-white/[0.06] bg-white/[0.02] p-8">
                <div class="flex gap-1 text-emerald-400"><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i></div>
                <blockquote class="mt-4 text-lg leading-relaxed text-white/80">Tracking my funded and challenge accounts in one place finally gave me a real picture of my performance.</blockquote>
                <figcaption class="mt-12 flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-emerald-500/20 text-sm font-semibold text-emerald-400">DS</span>
                    <div><p class="text-sm font-semibold">David Setyo Hardono</p><p class="text-xs text-white/40">Prop firm trader</p></div>
                </figcaption>
            </figure>
        </div>
    </div>
</section>

    <!-- FINAL CTA -->
    <section class="mx-auto max-w-6xl px-6 py-24">
        <div class="reveal relative overflow-hidden rounded-3xl border border-white/[0.08] bg-gradient-to-b from-white/[0.04] to-transparent px-6 py-16 text-center">
            <div class="pointer-events-none absolute inset-0 grid-faint opacity-50 [mask-image:radial-gradient(ellipse_at_center,black,transparent_75%)]"></div>
            <div class="relative">
                <h2 class="mx-auto max-w-xl text-3xl font-bold tracking-tight sm:text-4xl">Start building your edge today</h2>
                <p class="mx-auto mt-4 max-w-md text-white/55">Your next hundred trades deserve a real record. Set up your journal in under a minute.</p>
                <a href="/register" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-brand-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition hover:bg-brand-400">
                    Get started <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-white/[0.06]">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 py-8 sm:flex-row">
            <a href="/" class="flex items-center gap-2.5">
                <span class="grid h-7 w-7 place-items-center rounded-lg bg-brand-500 text-white"><i class="ph-bold ph-pulse"></i></span>
                <span class="text-sm font-bold tracking-tight">NeverMC</span>
            </a>
            <div class="flex items-center gap-6 text-sm text-white/50">
                <a href="#features" class="transition hover:text-white">Features</a>
                <a href="#dashboard" class="transition hover:text-white">Dashboard</a>
                <a href="/login" class="transition hover:text-white">Sign in</a>
            </div>
            <p class="text-xs text-white/30">Built for traders who keep receipts.</p>
        </div>
    </footer>

    <script>
        // Calendar heatmap cells
        (function () {
            var wrap = document.getElementById('heatmap');
            if (!wrap) return;
            var levels = ['bg-white/[0.04]', 'bg-emerald-500/20', 'bg-emerald-500/40', 'bg-emerald-500/70', 'bg-red-400/40'];
            var html = '';
            for (var i = 0; i < 48; i++) {
                var r = Math.random();
                var cls = r < 0.45 ? levels[0] : r < 0.6 ? levels[1] : r < 0.78 ? levels[2] : r < 0.9 ? levels[3] : levels[4];
                html += '<span class="aspect-square rounded-sm ' + cls + '"></span>';
            }
            wrap.innerHTML = html;
        })();
    </script>
</body>
</html>
