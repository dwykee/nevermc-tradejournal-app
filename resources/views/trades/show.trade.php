@extends('layouts.app')
@section('title', $trade->instrument . ' Trade')
@section('page-title', $trade->instrument . ' — Trade Detail')

@section('header-actions')
<a href="{{ route('trades.edit', $trade) }}"
   class="px-3 py-1.5 text-sm text-white/60 border border-white/[0.08] rounded-lg hover:bg-white/[0.04] transition-colors">
    Edit
</a>
<form method="POST" action="{{ route('trades.destroy', $trade) }}"
      onsubmit="return confirm('Delete this trade?')" class="inline">
    @csrf @method('DELETE')
    <button type="submit" class="px-3 py-1.5 text-sm text-red-400/60 hover:text-red-400 border border-red-400/20 rounded-lg hover:bg-red-400/5 transition-colors">
        Delete
    </button>
</form>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left col: core info --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Header card --}}
        <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-2xl font-semibold text-white">{{ $trade->instrument }}</h2>
                        <span class="text-sm px-2 py-0.5 rounded font-medium
                            {{ $trade->direction === 'long' ? 'bg-win/10 text-win' : 'bg-loss/10 text-loss' }}">
                            {{ strtoupper($trade->direction) }}
                        </span>
                        @if($trade->outcome)
                        <span class="text-xs px-2 py-0.5 rounded font-medium
                            {{ $trade->outcome === 'win' ? 'bg-win/10 text-win' : ($trade->outcome === 'loss' ? 'bg-loss/10 text-loss' : 'bg-white/10 text-white/50') }}">
                            {{ ucfirst($trade->outcome) }}
                        </span>
                        @endif
                    </div>
                    <p class="text-sm text-white/40">{{ $trade->account->name ?? '—' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-semibold font-mono {{ $trade->net_pnl >= 0 ? 'text-win' : 'text-loss' }}">
                        {{ $trade->pnl_formatted }}
                    </p>
                    <p class="text-xs text-white/30 mt-1">net P&L</p>
                </div>
            </div>
        </div>

        {{-- Details grid --}}
        <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-6">
            <h3 class="text-xs text-white/30 uppercase tracking-wider mb-4">Trade Details</h3>
            <div class="grid grid-cols-2 gap-x-8 gap-y-4">

                @php
                $details = [
                    ['Entry Time',   $trade->entry_time->format('M d, Y H:i:s')],
                    ['Exit Time',    $trade->exit_time ? $trade->exit_time->format('M d, Y H:i:s') : '—'],
                    ['Entry Price',  number_format($trade->entry_price, 2)],
                    ['Exit Price',   $trade->exit_price ? number_format($trade->exit_price, 2) : '—'],
                    ['Quantity',     $trade->quantity],
                    ['Duration',     $trade->duration_formatted],
                    ['Gross P&L',    '$'.number_format($trade->gross_pnl, 2)],
                    ['Commission',   '$'.number_format($trade->commission, 2)],
                    ['Net P&L',      '$'.number_format($trade->net_pnl, 2)],
                    ['Setup',        $trade->setup_tag ?: '—'],
                    ['Timeframe',    $trade->timeframe ?: '—'],
                    ['Session',      $trade->session ?: '—'],
                    ['Rating',       $trade->rating ? str_repeat('★', $trade->rating) . str_repeat('☆', 5 - $trade->rating) : '—'],
                    ['Status',       ucfirst($trade->status)],
                ];
                @endphp

                @foreach($details as [$label, $value])
                <div>
                    <p class="text-xs text-white/30 mb-0.5">{{ $label }}</p>
                    <p class="text-sm font-mono text-white/80">{{ $value }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right col: journal --}}
    <div class="space-y-4">
        <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs text-white/30 uppercase tracking-wider">Journal Notes</h3>
                <a href="{{ route('journal.create', ['trade_id' => $trade->id]) }}"
                   class="text-xs text-accent-light hover:text-accent transition-colors">+ Add note</a>
            </div>
            @if($trade->journalEntries->isEmpty())
            <p class="text-xs text-white/25 text-center py-6">No notes for this trade.</p>
            @else
            <div class="space-y-3">
                @foreach($trade->journalEntries as $entry)
                <div class="bg-white/[0.03] rounded-lg p-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs text-white/40">{{ $entry->entry_date->format('M d, Y') }}</span>
                        <span class="text-sm">{{ $entry->mood_emoji }}</span>
                    </div>
                    <p class="text-sm text-white/70 line-clamp-3">{{ strip_tags($entry->content) }}</p>
                    <a href="{{ route('journal.show', $entry) }}"
                       class="text-xs text-accent-light hover:text-accent mt-1.5 inline-block transition-colors">Read more →</a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection