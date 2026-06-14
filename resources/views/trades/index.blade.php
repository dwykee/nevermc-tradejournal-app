@extends('layouts.app')
@section('title', 'Trades')
@section('page-title', 'Trades')

@section('header-actions')
<a href="{{ route('trades.import') }}"
   class="flex items-center gap-2 px-3 py-1.5 text-sm text-white/60 border border-white/[0.08] rounded-lg hover:bg-white/[0.04] transition-colors">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
    </svg>
    Import CSV
</a>
<a href="{{ route('trades.create') }}"
   class="flex items-center gap-2 px-3 py-1.5 text-sm bg-accent hover:bg-accent-dark text-white rounded-lg transition-colors">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
    </svg>
    Add Trade
</a>
@endsection

@section('content')

{{-- ── Filters ──────────────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('trades.index') }}"
      class="bg-surface-2 border border-white/[0.06] rounded-xl p-4 mb-4 flex flex-wrap gap-3 items-end">

    <div class="flex flex-col gap-1">
        <label class="text-[10px] text-white/30 uppercase tracking-wider">Account</label>
        <select name="account_id" class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 focus:outline-none">
            <option value="">All</option>
            @foreach($accounts as $acc)
            <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected':'' }}>{{ $acc->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col gap-1">
        <label class="text-[10px] text-white/30 uppercase tracking-wider">Instrument</label>
        <input type="text" name="instrument" value="{{ request('instrument') }}" placeholder="ES, NQ…"
               class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 w-28 focus:outline-none">
    </div>

    <div class="flex flex-col gap-1">
        <label class="text-[10px] text-white/30 uppercase tracking-wider">Direction</label>
        <select name="direction" class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 focus:outline-none">
            <option value="">All</option>
            <option value="long"  {{ request('direction')=='long'  ? 'selected':'' }}>Long</option>
            <option value="short" {{ request('direction')=='short' ? 'selected':'' }}>Short</option>
        </select>
    </div>

    <div class="flex flex-col gap-1">
        <label class="text-[10px] text-white/30 uppercase tracking-wider">Outcome</label>
        <select name="outcome" class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 focus:outline-none">
            <option value="">All</option>
            <option value="win"       {{ request('outcome')=='win'       ? 'selected':'' }}>Win</option>
            <option value="loss"      {{ request('outcome')=='loss'      ? 'selected':'' }}>Loss</option>
            <option value="breakeven" {{ request('outcome')=='breakeven' ? 'selected':'' }}>Breakeven</option>
        </select>
    </div>

    <div class="flex flex-col gap-1">
        <label class="text-[10px] text-white/30 uppercase tracking-wider">From</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 focus:outline-none">
    </div>

    <div class="flex flex-col gap-1">
        <label class="text-[10px] text-white/30 uppercase tracking-wider">To</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 focus:outline-none">
    </div>

    @if($setupTags->isNotEmpty())
    <div class="flex flex-col gap-1">
        <label class="text-[10px] text-white/30 uppercase tracking-wider">Setup</label>
        <select name="setup_tag" class="bg-white/[0.04] border border-white/[0.08] text-white/70 text-sm rounded-lg px-3 py-1.5 focus:outline-none">
            <option value="">All</option>
            @foreach($setupTags as $tag)
            <option value="{{ $tag }}" {{ request('setup_tag')==$tag ? 'selected':'' }}>{{ $tag }}</option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="flex gap-2 ml-auto">
        <button type="submit"
                class="px-4 py-1.5 bg-accent hover:bg-accent-dark text-white text-sm rounded-lg transition-colors">
            Filter
        </button>
        <a href="{{ route('trades.index') }}"
           class="px-4 py-1.5 text-white/40 hover:text-white/70 text-sm rounded-lg border border-white/[0.06] hover:bg-white/[0.04] transition-colors">
            Clear
        </a>
    </div>
</form>

{{-- ── Table ────────────────────────────────────────────────────────────────── --}}
<div class="bg-surface-2 border border-white/[0.06] rounded-xl overflow-hidden">

    <div class="flex items-center justify-between px-5 py-3.5 border-b border-white/[0.06]">
        <span class="text-xs text-white/35">{{ $trades->total() }} trades</span>
        @if($trades->total() > 0)
        <form method="POST" action="{{ route('trades.bulk-destroy') }}" id="bulkForm">
            @csrf @method('DELETE')
            <input type="hidden" name="ids[]" id="bulkIds">
            <button type="button" onclick="confirmBulkDelete()"
                    class="text-xs text-red-400/60 hover:text-red-400 transition-colors" id="bulkBtn" style="display:none">
                Delete selected
            </button>
        </form>
        @endif
    </div>

    @if($trades->isEmpty())
    <div class="py-20 text-center">
        <p class="text-white/25 text-sm mb-3">No trades found.</p>
        <a href="{{ route('trades.create') }}" class="text-accent-light text-sm hover:text-accent transition-colors">Add a trade</a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tradesTable">
            <thead>
                <tr class="border-b border-white/[0.04]">
                    <th class="px-5 py-3">
                        <input type="checkbox" id="selectAll" class="accent-accent"
                               onchange="toggleAll(this.checked)">
                    </th>
                    <th class="text-left px-3 py-3 text-xs text-white/30 font-medium">Date</th>
                    <th class="text-left px-3 py-3 text-xs text-white/30 font-medium">Instrument</th>
                    <th class="text-left px-3 py-3 text-xs text-white/30 font-medium">Dir</th>
                    <th class="text-right px-3 py-3 text-xs text-white/30 font-medium">Qty</th>
                    <th class="text-right px-3 py-3 text-xs text-white/30 font-medium">Entry</th>
                    <th class="text-right px-3 py-3 text-xs text-white/30 font-medium">Exit</th>
                    <th class="text-right px-3 py-3 text-xs text-white/30 font-medium">Duration</th>
                    <th class="text-left px-3 py-3 text-xs text-white/30 font-medium">Setup</th>
                    <th class="text-right px-3 py-3 text-xs text-white/30 font-medium">Commission</th>
                    <th class="text-right px-5 py-3 text-xs text-white/30 font-medium">Net P&L</th>
                    <th class="px-3 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($trades as $trade)
                <tr class="trade-row border-b border-white/[0.03] transition-colors">
                    <td class="px-5 py-3">
                        <input type="checkbox" class="row-check accent-accent" value="{{ $trade->id }}"
                               onchange="updateBulk()">
                    </td>
                    <td class="px-3 py-3 text-xs text-white/40 font-mono whitespace-nowrap">
                        {{ $trade->entry_time->format('M d, Y') }}<br>
                        <span class="text-white/25">{{ $trade->entry_time->format('H:i') }}</span>
                    </td>
                    <td class="px-3 py-3">
                        <a href="{{ route('trades.show', $trade) }}"
                           class="text-sm font-medium text-white hover:text-accent-light transition-colors">
                            {{ $trade->instrument }}
                        </a>
                        @if($trade->account)
                        <p class="text-[10px] text-white/25">{{ $trade->account->name }}</p>
                        @endif
                    </td>
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
                    <td class="px-3 py-3 text-right text-xs text-white/40 font-mono">{{ $trade->duration_formatted }}</td>
                    <td class="px-3 py-3 text-xs text-white/40">
                        {{ $trade->setup_tag ?: '—' }}
                    </td>
                    <td class="px-3 py-3 text-right text-xs font-mono text-white/40">
                        ${{ number_format($trade->commission, 2) }}
                    </td>
                    <td class="px-5 py-3 text-right text-sm font-mono font-semibold {{ $trade->net_pnl >= 0 ? 'text-win' : 'text-loss' }}">
                        {{ $trade->pnl_formatted }}
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('trades.edit', $trade) }}"
                               class="text-white/25 hover:text-white/70 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('trades.destroy', $trade) }}"
                                  onsubmit="return confirm('Delete this trade?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-white/25 hover:text-red-400 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($trades->hasPages())
    <div class="px-5 py-4 border-t border-white/[0.04] flex items-center justify-between">
        <span class="text-xs text-white/30">
            Showing {{ $trades->firstItem() }}–{{ $trades->lastItem() }} of {{ $trades->total() }}
        </span>
        <div class="flex items-center gap-1">
            @if($trades->onFirstPage())
            <span class="px-3 py-1 text-xs text-white/20 border border-white/[0.05] rounded-lg">← Prev</span>
            @else
            <a href="{{ $trades->previousPageUrl() }}"
               class="px-3 py-1 text-xs text-white/50 hover:text-white/80 border border-white/[0.06] rounded-lg hover:bg-white/[0.04] transition-colors">← Prev</a>
            @endif

            @if($trades->hasMorePages())
            <a href="{{ $trades->nextPageUrl() }}"
               class="px-3 py-1 text-xs text-white/50 hover:text-white/80 border border-white/[0.06] rounded-lg hover:bg-white/[0.04] transition-colors">Next →</a>
            @else
            <span class="px-3 py-1 text-xs text-white/20 border border-white/[0.05] rounded-lg">Next →</span>
            @endif
        </div>
    </div>
    @endif
    @endif
</div>

@endsection

@push('scripts')
<script>
function toggleAll(checked) {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = checked);
    updateBulk();
}

function updateBulk() {
    const checked = document.querySelectorAll('.row-check:checked');
    const btn = document.getElementById('bulkBtn');
    if (btn) btn.style.display = checked.length > 0 ? 'block' : 'none';
}

function confirmBulkDelete() {
    const ids = [...document.querySelectorAll('.row-check:checked')].map(c => c.value);
    if (!ids.length) return;
    if (!confirm(`Delete ${ids.length} trades?`)) return;

    const form = document.getElementById('bulkForm');
    // Remove old hidden inputs
    form.querySelectorAll('input[name="ids[]"]').forEach(e => e.remove());
    ids.forEach(id => {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = id;
        form.appendChild(inp);
    });
    form.submit();
}
</script>
@endpush