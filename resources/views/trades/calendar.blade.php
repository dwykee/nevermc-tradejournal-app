@extends('layouts.app')
@section('title', 'Trade Calendar')
@section('page-title', '')

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-white">Trades</h2>
            <p class="text-sm text-white/40 mt-1">Manage and review all your trades.</p>
        </div>
        <a href="{{ route('trades.create') }}"
           class="px-4 py-2 bg-accent hover:bg-accent-dark text-white text-sm font-medium rounded-lg transition-colors">
            + Add Trade
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-6 border-b border-white/[0.06] pb-3">
        <a href="{{ route('trades.index') }}"
           class="px-3 py-1.5 text-sm rounded-lg text-white/50 hover:text-white/80 hover:bg-white/[0.04] transition-colors">List</a>
        <a href="{{ route('trades.calendar') }}"
           class="px-3 py-1.5 text-sm rounded-lg bg-white/[0.06] text-white font-medium">Calendar</a>
        <a href="{{ route('trades.gallery') }}"
           class="px-3 py-1.5 text-sm rounded-lg text-white/50 hover:text-white/80 hover:bg-white/[0.04] transition-colors">Gallery</a>
    </div>

    <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-6">
        @php
            $startOfGrid = $current->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $endOfGrid   = $current->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
            $day         = $startOfGrid->copy();
            $prevMonth   = $current->copy()->subMonthNoOverflow()->format('Y-m');
            $nextMonth   = $current->copy()->addMonthNoOverflow()->format('Y-m');
            $monthTotal  = 0;
            foreach ($byDate as $dayTrades) { $monthTotal += $dayTrades->sum('net_pnl'); }
        @endphp

        {{-- Month nav --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-white">{{ $current->format('F Y') }}</h3>
            <div class="flex items-center gap-3">
                <span class="text-sm font-mono font-semibold {{ $monthTotal >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ ($monthTotal >= 0 ? '+' : '') . '$' . number_format($monthTotal, 2) }}
                </span>
                <a href="{{ route('trades.calendar', ['month' => $prevMonth]) }}"
                   class="px-2.5 py-1 text-sm text-white/50 hover:text-white rounded-lg border border-white/[0.06] hover:bg-white/[0.04]">&lsaquo;</a>
                <a href="{{ route('trades.calendar') }}"
                   class="px-2.5 py-1 text-sm text-white/50 hover:text-white rounded-lg border border-white/[0.06] hover:bg-white/[0.04]">Today</a>
                <a href="{{ route('trades.calendar', ['month' => $nextMonth]) }}"
                   class="px-2.5 py-1 text-sm text-white/50 hover:text-white rounded-lg border border-white/[0.06] hover:bg-white/[0.04]">&rsaquo;</a>
            </div>
        </div>

        {{-- Day-of-week header --}}
        <div class="grid grid-cols-7 gap-1 mb-1">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dow)
            <div class="text-center text-xs text-white/30 py-1">{{ $dow }}</div>
            @endforeach
        </div>

        {{-- Day cells --}}
        <div class="grid grid-cols-7 gap-1">
            @while($day <= $endOfGrid)
                @php
                    $key       = $day->format('Y-m-d');
                    $dayTrades = $byDate->get($key, collect());
                    $netSum    = $dayTrades->sum('net_pnl');
                    $count     = $dayTrades->count();
                    $inMonth   = $day->isSameMonth($current);
                    $isToday   = $day->isToday();
                @endphp
                <div class="min-h-[92px] rounded-lg border border-white/[0.04] p-2 {{ $inMonth ? 'bg-white/[0.02]' : 'opacity-30' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs {{ $isToday ? 'bg-accent text-white rounded-full w-5 h-5 flex items-center justify-center' : 'text-white/40' }}">{{ $day->day }}</span>
                    </div>
                    @if($count > 0)
                    <div class="mt-2 text-sm font-mono font-semibold {{ $netSum >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ ($netSum >= 0 ? '+' : '') . '$' . number_format($netSum, 2) }}
                    </div>
                    <div class="text-[10px] text-white/30 mt-0.5">{{ $count }} {{ \Illuminate\Support\Str::plural('trade', $count) }}</div>
                    @endif
                </div>
                @php $day->addDay(); @endphp
            @endwhile
        </div>
    </div>
</div>
@endsection
