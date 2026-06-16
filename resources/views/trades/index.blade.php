@extends('layouts.app')
@section('title', 'Trades')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                Trades
            </h1>
            <p class="text-sm text-white/50 mt-1">
                Manage and review all your trades.
            </p>
        </div>
        <a href="{{ route('trades.create') }}"
           class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-medium px-4 py-2 rounded-lg transition">
            + Add Trade
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-6 border-b border-white/[0.06] pb-3">
        <a href="{{ route('trades.index') }}"
           class="px-3 py-1.5 text-sm rounded-lg bg-white/[0.06] text-white font-medium">List</a>
        <a href="{{ route('trades.calendar') }}"
           class="px-3 py-1.5 text-sm rounded-lg text-white/50 hover:text-white/80 hover:bg-white/[0.04] transition-colors">Calendar</a>
        <a href="{{ route('trades.gallery') }}"
           class="px-3 py-1.5 text-sm rounded-lg text-white/50 hover:text-white/80 hover:bg-white/[0.04] transition-colors">Gallery</a>
    </div>

    {{-- Main Card --}}
    <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl overflow-hidden">
        <div class="px-5 py-3 border-b border-white/[0.06]">
            <h3 class="text-sm font-medium text-white">
                Trade History
            </h3>
            <p class="text-xs text-white/40 mt-1">
                All imported and manually added trades.
            </p>
        </div>

        @if($trades->count() > 0)

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/[0.06]">
                            <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider text-white/40">Instrument</th>
                            <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider text-white/40">Direction</th>
                            <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider text-white/40">Account</th>
                            <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider text-white/40">Entry Time</th>
                            <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider text-white/40">Net P&L</th>
                            <th class="px-5 py-3 text-left text-[11px] uppercase tracking-wider text-white/40">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trades as $trade)
                        <tr onclick="window.location='{{ route('trades.show', $trade->id) }}'"
                            class="border-b border-white/[0.04] hover:bg-white/[0.03] transition cursor-pointer">
                            <td class="px-5 py-4 text-sm font-medium text-white">{{ $trade->instrument }}</td>
                            <td class="px-5 py-4 text-sm">
                                <span class="text-[11px] uppercase tracking-wide px-2 py-0.5 rounded {{ $trade->direction === 'long' ? 'bg-emerald-400/10 text-emerald-400' : 'bg-red-400/10 text-red-400' }}">{{ $trade->direction }}</span>
                            </td>
                            <td class="px-5 py-4 text-sm text-white/60">{{ optional($trade->account)->name ?? '—' }}</td>
                            <td class="px-5 py-4 text-sm text-white/60">{{ optional($trade->entry_time)->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-4 text-sm font-mono font-semibold {{ $trade->net_pnl >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $trade->net_pnl >= 0 ? '+' : '-' }}${{ number_format(abs($trade->net_pnl), 2) }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="bg-white/[0.06] text-white/70 text-[11px] px-2 py-1 rounded-md capitalize">{{ $trade->status ?? 'closed' }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($trades->hasPages())
            <div class="px-5 py-3 border-t border-white/[0.06]">
                {{ $trades->links() }}
            </div>
            @endif

        @else

            {{-- Empty State --}}
            <div class="py-10 px-6 text-center">
                <div
                    class="w-12 h-12 mx-auto rounded-xl bg-blue-500/10 flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-6 h-6 text-blue-400"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="1.8"
                              d="M9 17v-6m3 6V7m3 10v-4m4 8H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-white">
                    No trades yet
                </h2>
                <p class="text-sm text-white/50 mt-2 max-w-sm mx-auto">
                    Start tracking your trading performance by adding your first trade.
                </p>
                <a href="{{ route('trades.create') }}"
                   class="inline-flex mt-5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    + Create First Trade
                </a>
            </div>

        @endif
    </div>
</div>
@endsection
