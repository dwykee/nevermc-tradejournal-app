@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('header-actions')
{{-- Period selector --}}
<form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
    @if($accountId)<input type="hidden" name="account_id" value="{{ $accountId }}">@endif
    <select name="period" onchange="this.form.submit()"
            class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:border-accent/50">
        <option value="7"   {{ $period=='7'   ? 'selected':'' }}>7 days</option>
        <option value="30"  {{ $period=='30'  ? 'selected':'' }}>30 days</option>
        <option value="90"  {{ $period=='90'  ? 'selected':'' }}>90 days</option>
        <option value="365" {{ $period=='365' ? 'selected':'' }}>1 year</option>
        <option value="all" {{ $period=='all' ? 'selected':'' }}>All time</option>
    </select>
</form>

{{-- Account selector --}}
@if($accounts->count() > 1)
<form method="GET" action="{{ route('dashboard') }}">
    <input type="hidden" name="period" value="{{ $period }}">
    <select name="account_id" onchange="this.form.submit()"
            class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:border-accent/50">
        <option value="">All accounts</option>
        @foreach($accounts as $acc)
        <option value="{{ $acc->id }}" {{ $accountId == $acc->id ? 'selected':'' }}>{{ $acc->name }}</option>
        @endforeach
    </select>
</form>
@endif
@endsection

@section('content')

{{-- ── Stats Grid ─────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">

    {{-- Net PnL --}}
    <div class="stat-card bg-surface-2 border border-white/[0.06] rounded-xl p-4 transition-colors">
        <p class="text-xs text-white/40 mb-2">Net P&L</p>
        <p class="text-2xl font-semibold font-mono {{ $totalNetPnl >= 0 ? 'text-win' : 'text-loss' }}">
            {{ $totalNetPnl >= 0 ? '+' : '' }}${{ number_format($totalNetPnl, 2) }}
        </p>
        <p class="text-xs text-white/25 mt-1">after ${{ number_format($totalCommission, 2) }} commission</p>
    </div>

    {{-- Win Rate --}}
    <div class="stat-card bg-surface-2 border border-white/[0.06] rounded-xl p-4 transition-colors">
        <p class="text-xs text-white/40 mb-2">Win Rate</p>
        <p class="text-2xl font-semibold text-white">{{ $winRate }}%</p>
        <p class="text-xs text-white/25 mt-1">{{ $wins }}W / {{ $losses }}L of {{ $totalTrades }} trades</p>
    </div>

    {{-- Profit Factor --}}
    <div class="stat-card bg-surface-2 border border-white/[0.06] rounded-xl p-4 transition-colors">
        <p class="text-xs text-white/40 mb-2">Profit Factor</p>
        <p class="text-2xl font-semibold text-white">{{ $profitFactor }}</p>
        <p class="text-xs text-white/25 mt-1">avg win ${{ number_format($avgWin, 2) }}</p>
    </div>

    {{-- Max Drawdown --}}
    <div class="stat-card bg-surface-2 border border-white/[0.06] rounded-xl p-4 transition-colors">
        <p class="text-xs text-white/40 mb-2">Max Drawdown</p>
        <p class="text-2xl font-semibold text-loss">-${{ number_format($maxDrawdown, 2) }}</p>
        <p class="text-xs text-white/25 mt-1">worst trade -${{ number_format(abs($worstTrade), 2) }}</p>
    </div>
</div>

{{-- ── Row 2 Stats ─────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">

    <div class="stat-card bg-surface-2 border border-white/[0.06] rounded-xl p-4 transition-colors">
        <p class="text-xs text-white/40 mb-2">Avg Trade</p>
        <p class="text-xl font-semibold font-mono {{ $avgTradePnl >= 0 ? 'text-win' : 'text-loss' }}">
            {{ $avgTradePnl >= 0 ? '+' : '' }}${{ number_format($avgTradePnl, 2) }}
        </p>
    </div>

    <div class="stat-card bg-surface-2 border border-white/[0.06] rounded-xl p-4 transition-colors">
        <p class="text-xs text-white/40 mb-2">Best Trade</p>
        <p class="text-xl font-semibold font-mono text-win">+${{ number_format($bestTrade, 2) }}</p>
    </div>

    <div class="stat-card bg-surface-2 border border-white/[0.06] rounded-xl p-4 transition-colors">
        <p class="text-xs text-white/40 mb-2">Current Streak</p>
        @if($currentStreak > 0)
        <p class="text-xl font-semibold {{ $streakType === 'win' ? 'text-win' : 'text-loss' }}">
            {{ $currentStreak }} {{ ucfirst($streakType) }}{{ $currentStreak > 1 ? 's' : '' }}
        </p>
        @else
        <p class="text-xl font-semibold text-white/30">—</p>
        @endif
    </div>

    <div class="stat-card bg-surface-2 border border-white/[0.06] rounded-xl p-4 transition-colors">
        <p class="text-xs text-white/40 mb-2">Avg Hold Time</p>
        <p class="text-xl font-semibold text-white">
            @php
                $h = floor($avgDuration / 3600);
                $m = floor(($avgDuration % 3600) / 60);
                echo $h > 0 ? "{$h}h {$m}m" : ($m > 0 ? "{$m}m" : round($avgDuration).'s');
            @endphp
        </p>
    </div>
</div>

{{-- ── Charts Row ──────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    {{-- Equity Curve - large --}}
    <div class="lg:col-span-2 bg-surface-2 border border-white/[0.06] rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-medium text-white/80">Equity Curve</h2>
            <span class="text-xs text-white/30">Cumulative P&L</span>
        </div>
        <div class="h-52">
            <canvas id="equityChart"></canvas>
        </div>
    </div>

    {{-- PnL by Instrument --}}
    <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-medium text-white/80">By Instrument</h2>
        </div>
        @if($pnlByInstrument->isEmpty())
        <p class="text-xs text-white/25 text-center mt-12">No data</p>
        @else
        <div class="space-y-2.5">
            @foreach($pnlByInstrument->take(7) as $instrument => $data)
            <div class="flex items-center gap-3">
                <span class="text-xs font-mono text-white/60 w-14 truncate">{{ $instrument }}</span>
                <div class="flex-1 h-1.5 bg-white/[0.05] rounded-full overflow-hidden">
                    @php
                        $max = $pnlByInstrument->max('pnl');
                        $min = abs($pnlByInstrument->min('pnl'));
                        $ref = max($max, $min, 1);
                        $pct = min(100, abs($data['pnl']) / $ref * 100);
                    @endphp
                    <div class="h-full rounded-full {{ $data['pnl'] >= 0 ? 'bg-win' : 'bg-loss' }}"
                         style="width: {{ $pct }}%"></div>
                </div>
                <span class="text-xs font-mono {{ $data['pnl'] >= 0 ? 'text-win' : 'text-loss' }} w-16 text-right">
                    {{ $data['pnl'] >= 0 ? '+' : '' }}${{ number_format($data['pnl'], 0) }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ── Daily PnL Bar chart ─────────────────────────────────────────────────── --}}
<div class="bg-surface-2 border border-white/[0.06] rounded-xl p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-medium text-white/80">Daily P&L</h2>
    </div>
    <div class="h-40">
        <canvas id="dailyChart"></canvas>
    </div>
</div>

{{-- ── Recent Trades ───────────────────────────────────────────────────────── --}}
<div class="bg-surface-2 border border-white/[0.06] rounded-xl">
    <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
        <h2 class="text-sm font-medium text-white/80">Recent Trades</h2>
        <a href="{{ route('trades.index') }}" class="text-xs text-accent-light hover:text-accent transition-colors">
            View all →
        </a>
    </div>
    @if($recentTrades->isEmpty())
    <div class="px-5 py-12 text-center">
        <p class="text-sm text-white/25">No trades yet.</p>
        <a href="{{ route('trades.create') }}" class="text-xs text-accent-light hover:text-accent mt-2 inline-block transition-colors">
            Add your first trade →
        </a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/[0.04]">
                    <th class="text-left px-5 py-3 text-xs text-white/30 font-medium">Date</th>
                    <th class="text-left px-3 py-3 text-xs text-white/30 font-medium">Instrument</th>
                    <th class="text-left px-3 py-3 text-xs text-white/30 font-medium">Dir</th>
                    <th class="text-right px-3 py-3 text-xs text-white/30 font-medium">Qty</th>
                    <th class="text-right px-3 py-3 text-xs text-white/30 font-medium">Entry</th>
                    <th class="text-right px-3 py-3 text-xs text-white/30 font-medium">Exit</th>
                    <th class="text-right px-5 py-3 text-xs text-white/30 font-medium">Net P&L</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTrades as $trade)
                <tr class="trade-row border-b border-white/[0.03] transition-colors">
                    <td class="px-5 py-3 text-xs text-white/40 font-mono">{{ $trade->entry_time->format('M d, H:i') }}</td>
                    <td class="px-3 py-3 text-sm font-medium text-white">{{ $trade->instrument }}</td>
                    <td class="px-3 py-3">
                        <span class="text-xs px-1.5 py-0.5 rounded font-medium
                            {{ $trade->direction === 'long'
                                ? 'bg-win/10 text-win'
                                : 'bg-loss/10 text-loss' }}">
                            {{ strtoupper($trade->direction) }}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-right text-sm font-mono text-white/60">{{ $trade->quantity }}</td>
                    <td class="px-3 py-3 text-right text-sm font-mono text-white/60">{{ number_format($trade->entry_price, 2) }}</td>
                    <td class="px-3 py-3 text-right text-sm font-mono text-white/60">
                        {{ $trade->exit_price ? number_format($trade->exit_price, 2) : '—' }}
                    </td>
                    <td class="px-5 py-3 text-right text-sm font-mono font-medium {{ $trade->net_pnl >= 0 ? 'text-win' : 'text-loss' }}">
                        {{ $trade->pnl_formatted }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    // Chart defaults
    Chart.defaults.color = 'rgba(255,255,255,0.3)';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 11;

    // ── Equity Curve ─────────────────────────────────────────────────────────
    @php
        $eq = collect($equityCurve);
    @endphp
    const eqLabels = @json($eq->pluck('date'));
    const eqValues = @json($eq->pluck('value'));
    const finalVal = eqValues.length > 0 ? eqValues[eqValues.length - 1] : 0;
    const eqColor  = finalVal >= 0 ? '#34d399' : '#f87171';

    new Chart(document.getElementById('equityChart'), {
        type: 'line',
        data: {
            labels: eqLabels,
            datasets: [{
                data: eqValues,
                borderColor: eqColor,
                borderWidth: 1.5,
                pointRadius: 0,
                tension: 0.3,
                fill: true,
                backgroundColor: (ctx) => {
                    const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, ctx.chart.height);
                    g.addColorStop(0, eqColor + '30');
                    g.addColorStop(1, 'transparent');
                    return g;
                }
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: {
                callbacks: { label: ctx => ' $' + ctx.raw.toFixed(2) }
            }},
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 6 } },
                y: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { callback: v => '$' + v.toLocaleString() }
                }
            }
        }
    });

    // ── Daily P&L ─────────────────────────────────────────────────────────────
    const dailyData = @json($dailyPnl);
    const dailyLabels = Object.keys(dailyData);
    const dailyValues = Object.values(dailyData);
    const dailyColors = dailyValues.map(v => v >= 0 ? 'rgba(52,211,153,0.65)' : 'rgba(248,113,113,0.65)');

    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                data: dailyValues,
                backgroundColor: dailyColors,
                borderRadius: 3,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: {
                callbacks: { label: ctx => ' $' + ctx.raw.toFixed(2) }
            }},
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } },
                y: {
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    ticks: { callback: v => '$' + v }
                }
            }
        }
    });
</script>
@endpush