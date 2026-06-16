@extends('layouts.app')
@section('title', 'Trade Detail')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8">

    {{-- Back --}}
    <a href="{{ route('trades.index') }}"
       class="inline-flex items-center gap-1 text-sm text-white/50 hover:text-white/80 mb-5 transition-colors">
        ← Back to Trades
    </a>

    {{-- Header --}}
    <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-6 mb-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl font-semibold text-white">{{ $trade->instrument }}</h1>
                    <span class="text-[11px] uppercase tracking-wide px-2 py-0.5 rounded {{ $trade->direction === 'long' ? 'bg-emerald-400/10 text-emerald-400' : 'bg-red-400/10 text-red-400' }}">{{ $trade->direction }}</span>
                    <span class="bg-white/[0.06] text-white/70 text-[11px] px-2 py-1 rounded-md capitalize">{{ $trade->status ?? 'closed' }}</span>
                </div>
                <p class="text-sm text-white/40 mt-2">
                    {{ optional($trade->account)->name ?? '—' }} · {{ optional($trade->entry_time)->format('d M Y, H:i') }}
                </p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-[10px] uppercase tracking-[0.15em] text-white/40">Net P&L</p>
                <p class="text-3xl font-mono font-semibold {{ $trade->net_pnl >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                    {{ $trade->net_pnl >= 0 ? '+' : '-' }}${{ number_format(abs($trade->net_pnl), 2) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Details --}}
    <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-6 mb-5">
        <h3 class="text-sm font-medium text-white mb-4">Trade Details</h3>
        @php
            $details = [
                'Quantity'    => $trade->quantity,
                'Entry Price' => $trade->entry_price !== null ? '$' . number_format($trade->entry_price, 2) : '—',
                'Exit Price'  => $trade->exit_price !== null ? '$' . number_format($trade->exit_price, 2) : '—',
                'Entry Time'  => optional($trade->entry_time)->format('d M Y, H:i') ?? '—',
                'Exit Time'   => optional($trade->exit_time)->format('d M Y, H:i') ?? '—',
                'Gross P&L'   => $trade->gross_pnl !== null ? '$' . number_format($trade->gross_pnl, 2) : '—',
                'Commission'  => '$' . number_format($trade->commission ?? 0, 2),
                'Setup'       => $trade->setup ?: '—',
                'Rating'      => $trade->rating ? str_repeat('★', $trade->rating) : '—',
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4">
            @foreach($details as $label => $value)
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-white/40 mb-1">{{ $label }}</p>
                <p class="text-sm text-white/80">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Screenshots --}}
    @if($trade->screenshot_before || $trade->screenshot_after)
    <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-6 mb-5">
        <h3 class="text-sm font-medium text-white mb-4">Screenshots</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-[10px] uppercase tracking-wide text-white/30 mb-1.5">Before Entry</p>
                @if($trade->screenshot_before)
                <a href="{{ asset('storage/' . $trade->screenshot_before) }}" target="_blank">
                    <img src="{{ asset('storage/' . $trade->screenshot_before) }}" alt="Before entry"
                         class="w-full rounded-lg border border-white/[0.06] hover:opacity-90 transition">
                </a>
                @else
                <div class="w-full h-40 rounded-lg border border-dashed border-white/[0.08] flex items-center justify-center text-white/20 text-xs">No image</div>
                @endif
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-wide text-white/30 mb-1.5">After Entry</p>
                @if($trade->screenshot_after)
                <a href="{{ asset('storage/' . $trade->screenshot_after) }}" target="_blank">
                    <img src="{{ asset('storage/' . $trade->screenshot_after) }}" alt="After entry"
                         class="w-full rounded-lg border border-white/[0.06] hover:opacity-90 transition">
                </a>
                @else
                <div class="w-full h-40 rounded-lg border border-dashed border-white/[0.08] flex items-center justify-center text-white/20 text-xs">No image</div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Notes --}}
    @if($trade->notes)
    <div class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-6 mb-5">
        <h3 class="text-sm font-medium text-white mb-2">Notes</h3>
        <p class="text-sm text-white/70 whitespace-pre-line">{{ $trade->notes }}</p>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex items-center gap-3">
        <form method="POST" action="{{ route('trades.destroy', $trade->id) }}"
              onsubmit="return confirm('Delete this trade?');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="px-4 py-2 text-sm text-red-400 border border-red-400/20 rounded-lg hover:bg-red-400/10 transition">
                Delete Trade
            </button>
        </form>
    </div>

</div>
@endsection
