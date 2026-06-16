@extends('layouts.app')
@section('title', 'Import CSV')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-8">

    @if(session('success'))
    <div class="mb-5 px-4 py-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-5 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-white">Import Trades from CSV</h1>
        <p class="text-sm text-white/50 mt-1">Bulk-import your trade history from a broker export file.</p>
    </div>

    @if($errors->any())
    <div class="mb-5 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Upload Form --}}
    <form method="POST" action="{{ route('trades.import.store') }}" enctype="multipart/form-data"
          class="bg-white/[0.03] border border-white/[0.06] rounded-xl p-5 space-y-5">
        @csrf

        <div>
            <label class="block text-xs text-white/40 mb-2">Account *</label>
            <select name="account_id" required
                    class="w-full bg-white/[0.03] border border-white/[0.06] rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:border-blue-500/50">
                <option value="">Select account</option>
                @foreach($accounts as $acc)
                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                @endforeach
            </select>
            <p class="text-[11px] text-white/30 mt-1.5">All imported trades will be assigned to this account.</p>
        </div>

        <div>
            <label class="block text-xs text-white/40 mb-2">CSV File *</label>
            <label for="csvFile"
                   class="flex flex-col items-center justify-center w-full min-h-[160px] border-2 border-dashed border-white/[0.12] rounded-xl cursor-pointer bg-white/[0.02] hover:bg-white/[0.04] hover:border-blue-500/40 transition p-4">
                <div id="csvPlaceholder" class="flex flex-col items-center justify-center text-center">
                    <div class="w-11 h-11 rounded-xl bg-blue-500/10 flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v12m0-12l-4 4m4-4l4 4"/>
                        </svg>
                    </div>
                    <p class="text-sm text-white/70"><span class="text-blue-400 font-medium">Click to upload</span> your CSV</p>
                    <p class="text-[11px] text-white/30 mt-1">.csv file, up to 10MB</p>
                </div>
                <p id="csvFileName" class="hidden text-sm text-white/70 font-medium"></p>
                <input id="csvFile" type="file" name="csv_file" accept=".csv,text/csv" class="hidden" onchange="showCsvName(event)">
            </label>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                Import Trades
            </button>
        </div>
    </form>

    {{-- Format Guide --}}
    <div class="mt-5 bg-white/[0.03] border border-white/[0.06] rounded-xl p-5">
        <h3 class="text-sm font-medium text-white mb-1">Expected columns</h3>
        <p class="text-xs text-white/40 mb-4">The first row must be a header. These column names are recognized (case-insensitive). Unknown columns are ignored.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-white/40 text-left border-b border-white/[0.06]">
                        <th class="py-2 pr-4 font-medium">Field</th>
                        <th class="py-2 font-medium">Accepted header names</th>
                    </tr>
                </thead>
                <tbody class="text-white/70">
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">instrument *</td><td class="py-1.5">instrument, symbol, ticker, pair</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">direction</td><td class="py-1.5">direction, side, type (long/short)</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">quantity</td><td class="py-1.5">quantity, qty, size, volume, lots</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">entry_price</td><td class="py-1.5">entry_price, entry, open_price</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">exit_price</td><td class="py-1.5">exit_price, exit, close_price</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">entry_time</td><td class="py-1.5">entry_time, open_time, date</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">exit_time</td><td class="py-1.5">exit_time, close_time</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">gross_pnl</td><td class="py-1.5">gross_pnl, pnl, profit, p&l</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">commission</td><td class="py-1.5">commission, fee, fees</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">setup</td><td class="py-1.5">setup, setup_tag, strategy</td></tr>
                    <tr class="border-b border-white/[0.04]"><td class="py-1.5 pr-4 text-white">status</td><td class="py-1.5">status (open/closed/cancelled)</td></tr>
                    <tr><td class="py-1.5 pr-4 text-white">rating</td><td class="py-1.5">rating, score (1-5)</td></tr>
                </tbody>
            </table>
        </div>

        <p class="text-xs text-white/40 mt-4 mb-2">Example file:</p>
        <pre class="bg-black/40 border border-white/[0.06] rounded-lg p-3 text-[11px] text-white/60 overflow-x-auto">instrument,direction,quantity,entry_price,exit_price,entry_time,exit_time,gross_pnl,commission,setup,status
NQ,long,2,20.00,22.00,2026-06-16 09:24,2026-06-17 09:24,200,0,Breakout,closed
ES,short,1,5400,5395,2026-06-15 14:00,2026-06-15 15:30,250,2.5,Reversal,closed</pre>
        <p class="text-[11px] text-white/30 mt-2">Note: net_pnl is calculated automatically as gross_pnl minus commission.</p>
    </div>

</div>

<script>
    function showCsvName(e) {
        var file = e.target.files[0];
        if (!file) { return; }
        var placeholder = document.getElementById('csvPlaceholder');
        var nameEl = document.getElementById('csvFileName');
        placeholder.classList.add('hidden');
        nameEl.textContent = file.name;
        nameEl.classList.remove('hidden');
    }
</script>
@endsection
