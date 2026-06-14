@extends('layouts.app')
@section('title', 'Add Trade')
@section('page-title', 'Add Trade')

@section('content')
<div class="max-w-2xl">
    <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-6">

        <form method="POST" action="{{ route('trades.store') }}" class="space-y-5">
            @csrf

            {{-- Account & Instrument --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Account *</label>
                    <select name="account_id" required
                            class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5">
                        @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}" {{ old('account_id') == $acc->id ? 'selected':'' }}>{{ $acc->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Instrument *</label>
                    <input type="text" name="instrument" value="{{ old('instrument') }}" required
                           placeholder="ES, NQ, BTC…"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5 placeholder-white/20">
                </div>
            </div>

            {{-- Direction & Quantity --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Direction *</label>
                    <select name="direction" required
                            class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5">
                        <option value="long"  {{ old('direction')=='long'  ? 'selected':'' }}>Long</option>
                        <option value="short" {{ old('direction')=='short' ? 'selected':'' }}>Short</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Quantity *</label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" step="0.0001" min="0" required
                           placeholder="1"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5 placeholder-white/20">
                </div>
            </div>

            {{-- Entry time & price --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Entry Time *</label>
                    <input type="datetime-local" name="entry_time" value="{{ old('entry_time') }}" required
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5">
                </div>
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Entry Price *</label>
                    <input type="number" name="entry_price" value="{{ old('entry_price') }}" step="0.000001" required
                           placeholder="0.00"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5 placeholder-white/20">
                </div>
            </div>

            {{-- Exit time & price --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Exit Time</label>
                    <input type="datetime-local" name="exit_time" value="{{ old('exit_time') }}"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5">
                </div>
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Exit Price</label>
                    <input type="number" name="exit_price" value="{{ old('exit_price') }}" step="0.000001"
                           placeholder="0.00"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5 placeholder-white/20">
                </div>
            </div>

            {{-- PnL --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Gross P&L *</label>
                    <input type="number" name="gross_pnl" value="{{ old('gross_pnl') }}" step="0.01" required
                           placeholder="e.g. 250.00 or -120.00"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5 placeholder-white/20"
                           id="grossPnl" oninput="calcNet()">
                </div>
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Commission</label>
                    <input type="number" name="commission" value="{{ old('commission', 0) }}" step="0.01" min="0"
                           placeholder="0.00"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5 placeholder-white/20"
                           id="commission" oninput="calcNet()">
                </div>
            </div>

            {{-- Net PnL preview --}}
            <div class="bg-white/[0.03] rounded-lg px-4 py-2.5 flex items-center justify-between">
                <span class="text-xs text-white/40">Net P&L</span>
                <span class="text-sm font-mono font-semibold" id="netPreview">—</span>
            </div>

            {{-- Status --}}
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Status *</label>
                    <select name="status" required
                            class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5">
                        <option value="closed" {{ old('status','closed')=='closed' ? 'selected':'' }}>Closed</option>
                        <option value="open"   {{ old('status')=='open'   ? 'selected':'' }}>Open</option>
                        <option value="cancelled" {{ old('status')=='cancelled' ? 'selected':'' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Setup Tag</label>
                    <input type="text" name="setup_tag" value="{{ old('setup_tag') }}"
                           placeholder="breakout, reversal…"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5 placeholder-white/20">
                </div>
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Rating (1–5)</label>
                    <input type="number" name="rating" value="{{ old('rating') }}" min="1" max="5"
                           placeholder="—"
                           class="w-full bg-white/[0.04] border border-white/[0.08] text-white/80 text-sm rounded-lg px-3 py-2.5 placeholder-white/20">
                </div>
            </div>

            @if($errors->any())
            <div class="text-xs text-red-400 bg-red-400/5 border border-red-400/20 rounded-lg px-4 py-3">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-accent hover:bg-accent-dark text-white text-sm font-medium rounded-lg transition-colors">
                    Save Trade
                </button>
                <a href="{{ route('trades.index') }}"
                   class="px-6 py-2.5 text-white/40 hover:text-white/70 text-sm rounded-lg border border-white/[0.06] hover:bg-white/[0.04] transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calcNet() {
    const gross = parseFloat(document.getElementById('grossPnl').value) || 0;
    const comm  = parseFloat(document.getElementById('commission').value) || 0;
    const net   = gross - comm;
    const el    = document.getElementById('netPreview');
    el.textContent = (net >= 0 ? '+' : '') + '$' + net.toFixed(2);
    el.className = 'text-sm font-mono font-semibold ' + (net >= 0 ? 'text-emerald-400' : 'text-red-400');
}
</script>
@endpush