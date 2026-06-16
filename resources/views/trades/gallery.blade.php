@extends('layouts.app')
@section('title', 'Trade Gallery')
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
           class="px-3 py-1.5 text-sm rounded-lg text-white/50 hover:text-white/80 hover:bg-white/[0.04] transition-colors">Calendar</a>
        <a href="{{ route('trades.gallery') }}"
           class="px-3 py-1.5 text-sm rounded-lg bg-white/[0.06] text-white font-medium">Gallery</a>
    </div>

    @if($trades->isEmpty())
    <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-12 text-center">
        <p class="text-white/60 text-sm">No screenshots yet</p>
        <p class="text-white/30 text-xs mt-1">Add a trade with before/after screenshots to see them here.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($trades as $trade)
        <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <span class="text-sm font-semibold text-white">{{ $trade->instrument }}</span>
                    <span class="ml-2 text-[10px] uppercase tracking-wide px-1.5 py-0.5 rounded {{ $trade->direction === 'long' ? 'bg-emerald-400/10 text-emerald-400' : 'bg-red-400/10 text-red-400' }}">{{ $trade->direction }}</span>
                </div>
                <span class="text-sm font-mono font-semibold {{ $trade->net_pnl >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ ($trade->net_pnl >= 0 ? '+' : '') . '$' . number_format($trade->net_pnl, 2) }}
                </span>
            </div>
            <p class="text-xs text-white/30 mb-3">{{ optional($trade->entry_time)->format('d M Y, H:i') }}</p>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-[10px] uppercase tracking-wide text-white/30 mb-1.5">Before</p>
                    @if($trade->screenshot_before)
                    <a href="{{ asset('storage/' . $trade->screenshot_before) }}" target="_blank">
                        <img src="{{ asset('storage/' . $trade->screenshot_before) }}" alt="Before entry"
                             class="w-full h-40 object-cover rounded-lg border border-white/[0.06] hover:opacity-90 transition-opacity">
                    </a>
                    @else
                    <div class="w-full h-40 rounded-lg border border-dashed border-white/[0.08] flex items-center justify-center text-white/20 text-xs">No image</div>
                    @endif
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wide text-white/30 mb-1.5">After</p>
                    @if($trade->screenshot_after)
                    <a href="{{ asset('storage/' . $trade->screenshot_after) }}" target="_blank">
                        <img src="{{ asset('storage/' . $trade->screenshot_after) }}" alt="After entry"
                             class="w-full h-40 object-cover rounded-lg border border-white/[0.06] hover:opacity-90 transition-opacity">
                    </a>
                    @else
                    <div class="w-full h-40 rounded-lg border border-dashed border-white/[0.08] flex items-center justify-center text-white/20 text-xs">No image</div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
