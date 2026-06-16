@extends('layouts.app')
@section('title', 'Add Trade')
@section('page-title', '')

@section('content')
<div class="max-w-5xl mx-auto">    <div class="bg-surface-2 border border-white/[0.06] rounded-xl p-6">
        <div class="mb-6 pb-4 border-b border-white/[0.06]">
            <h2 class="text-lg font-semibold text-white">
                Add Trade
            </h2>

            <p class="text-sm text-white/40 mt-1">
                Record a completed trade for analysis and review.
            </p>
        </div>

        <form method="POST" action="{{ route('trades.store') }}" class="space-y-5" enctype="multipart/form-data">
            @csrf

            <!-- Screenshots: before & after entry -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Screenshot Before Entry</label>
                    <label for="screenshotBefore"
                           class="flex flex-col items-center justify-center w-full min-h-[140px] border-2 border-dashed border-white/[0.12] rounded-xl cursor-pointer bg-white/[0.02] hover:bg-white/[0.04] hover:border-blue-500/40 transition p-4">
                        <div id="phBefore" class="flex flex-col items-center justify-center text-center">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v12m0-12l-4 4m4-4l4 4"/>
                                </svg>
                            </div>
                            <p class="text-xs text-white/70"><span class="text-blue-400 font-medium">Click to upload</span></p>
                            <p class="text-[10px] text-white/30 mt-1">PNG or JPG</p>
                        </div>
                        <img id="prevBefore" class="hidden max-h-32 rounded-lg object-contain" alt="Preview">
                        <p id="phBeforeName" class="hidden text-[10px] text-white/40 mt-2"></p>
                        <input id="screenshotBefore" type="file" name="screenshot_before" accept="image/*" class="hidden js-upload" data-preview="prevBefore" data-placeholder="phBefore" data-name="phBeforeName">
                    </label>
                </div>
                <div>
                    <label class="block text-xs text-white/40 mb-1.5">Screenshot After Entry</label>
                    <label for="screenshotAfter"
                           class="flex flex-col items-center justify-center w-full min-h-[140px] border-2 border-dashed border-white/[0.12] rounded-xl cursor-pointer bg-white/[0.02] hover:bg-white/[0.04] hover:border-blue-500/40 transition p-4">
                        <div id="phAfter" class="flex flex-col items-center justify-center text-center">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v12m0-12l-4 4m4-4l4 4"/>
                                </svg>
                            </div>
                            <p class="text-xs text-white/70"><span class="text-blue-400 font-medium">Click to upload</span></p>
                            <p class="text-[10px] text-white/30 mt-1">PNG or JPG</p>
                        </div>
                        <img id="prevAfter" class="hidden max-h-32 rounded-lg object-contain" alt="Preview">
                        <p id="phAfterName" class="hidden text-[10px] text-white/40 mt-2"></p>
                        <input id="screenshotAfter" type="file" name="screenshot_after" accept="image/*" class="hidden js-upload" data-preview="prevAfter" data-placeholder="phAfter" data-name="phAfterName">
                    </label>
                </div>
            </div>

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
<script>
function calcNet() {
    const gross = parseFloat(document.getElementById('grossPnl').value) || 0;
    const comm  = parseFloat(document.getElementById('commission').value) || 0;
    const net   = gross - comm;
    const el    = document.getElementById('netPreview');
    el.textContent = (net >= 0 ? '+' : '') + '$' + net.toFixed(2);
    el.className = 'text-sm font-mono font-semibold ' + (net >= 0 ? 'text-emerald-400' : 'text-red-400');
}

document.querySelectorAll('.js-upload').forEach(function (input) {
    input.addEventListener('change', function () {
        var file = this.files && this.files[0];
        if (!file) { return; }
        var preview = document.getElementById(this.dataset.preview);
        var placeholder = document.getElementById(this.dataset.placeholder);
        var nameEl = document.getElementById(this.dataset.name);
        var reader = new FileReader();
        reader.onload = function (ev) {
            if (preview) { preview.src = ev.target.result; preview.classList.remove('hidden'); }
            if (placeholder) { placeholder.classList.add('hidden'); }
            if (nameEl) { nameEl.textContent = file.name; nameEl.classList.remove('hidden'); }
        };
        reader.readAsDataURL(file);
    });
});

</script>
@endsection