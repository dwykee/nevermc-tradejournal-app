<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TradeController;

// ── Public routes ──────────────────────────────────────────────────────────────
Route::get('/welcome', function () {
    return view('landing');
})->name('landing');

Route::get('/login', function () {
    if (Auth::check()) return redirect()->route('dashboard');
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    if (Auth::check()) return redirect()->route('dashboard');
    return view('auth.register');
})->name('register');

// ── Auth POST routes ───────────────────────────────────────────────────────────
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
})->name('login.submit');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'email', 'unique:users'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    $user = \App\Models\User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => bcrypt($request->password),
    ]);

    Auth::login($user);
    return redirect()->route('dashboard');
})->name('register.submit');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// ── Authenticated routes ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function (Request $request) {
        $user      = Auth::user();
        $period    = $request->get('period', '30');
        $accountId = $request->get('account_id');

        // Ambil semua accounts user
        $accounts = \App\Models\Account::where('user_id', $user->id)->get();

        // Query trades
        $query = \App\Models\Trade::where('user_id', $user->id)
            ->whereNotNull('exit_price');

        if ($accountId) {
            $query->where('account_id', $accountId);
        }

        if ($period !== 'all') {
            $query->where('entry_time', '>=', now()->subDays((int) $period));
        }

        $trades = $query->orderBy('entry_time')->get();

        // ── Stats ──────────────────────────────────────────────────────────────
        $totalTrades    = $trades->count();
        $wins           = $trades->where('net_pnl', '>', 0)->count();
        $losses         = $trades->where('net_pnl', '<=', 0)->count();
        $winRate        = $totalTrades > 0 ? round($wins / $totalTrades * 100, 1) : 0;
        $totalNetPnl    = $trades->sum('net_pnl');
        $totalCommission = $trades->sum('commission');
        $grossWin       = $trades->where('net_pnl', '>', 0)->sum('net_pnl');
        $grossLoss      = abs($trades->where('net_pnl', '<', 0)->sum('net_pnl'));
        $profitFactor   = $grossLoss > 0 ? round($grossWin / $grossLoss, 2) : ($grossWin > 0 ? '∞' : 0);
        $avgWin         = $wins > 0 ? $grossWin / $wins : 0;
        $avgTradePnl    = $totalTrades > 0 ? $totalNetPnl / $totalTrades : 0;
        $bestTrade      = $trades->max('net_pnl') ?? 0;
        $worstTrade     = $trades->min('net_pnl') ?? 0;

        // Avg hold time (seconds)
        $avgDuration = $trades->filter(fn($t) => $t->exit_time && $t->entry_time)
            ->avg(fn($t) => $t->entry_time->diffInSeconds($t->exit_time)) ?? 0;

        // Max drawdown
        $peak = 0; $equity = 0; $maxDrawdown = 0;
        foreach ($trades as $t) {
            $equity += $t->net_pnl;
            if ($equity > $peak) $peak = $equity;
            $dd = $peak - $equity;
            if ($dd > $maxDrawdown) $maxDrawdown = $dd;
        }

        // Current streak
        $sorted       = $trades->sortByDesc('entry_time')->values();
        $currentStreak = 0;
        $streakType   = 'win';
        if ($sorted->isNotEmpty()) {
            $streakType = $sorted[0]->net_pnl > 0 ? 'win' : 'loss';
            foreach ($sorted as $t) {
                $isWin = $t->net_pnl > 0;
                if (($streakType === 'win' && $isWin) || ($streakType === 'loss' && !$isWin)) {
                    $currentStreak++;
                } else {
                    break;
                }
            }
        }

        // Equity curve
        $equityCurve = [];
        $cumulative  = 0;
        foreach ($trades->groupBy(fn($t) => $t->entry_time->toDateString()) as $date => $dayTrades) {
            $cumulative += $dayTrades->sum('net_pnl');
            $equityCurve[] = ['date' => $date, 'value' => round($cumulative, 2)];
        }

        // Daily PnL
        $dailyPnl = $trades->groupBy(fn($t) => $t->entry_time->toDateString())
            ->map(fn($g) => round($g->sum('net_pnl'), 2))
            ->toArray();

        // PnL by instrument
        $pnlByInstrument = $trades->groupBy('instrument')
            ->map(fn($g) => ['pnl' => round($g->sum('net_pnl'), 2), 'count' => $g->count()])
            ->sortByDesc('pnl');

        // Recent trades
        $recentTrades = \App\Models\Trade::where('user_id', $user->id)
            ->when($accountId, fn($q) => $q->where('account_id', $accountId))
            ->orderByDesc('entry_time')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'accounts', 'period', 'accountId',
            'totalTrades', 'wins', 'losses', 'winRate',
            'totalNetPnl', 'totalCommission',
            'profitFactor', 'avgWin',
            'avgTradePnl', 'bestTrade', 'worstTrade',
            'maxDrawdown', 'avgDuration',
            'currentStreak', 'streakType',
            'equityCurve', 'dailyPnl', 'pnlByInstrument',
            'recentTrades'
        ));
    })->name('dashboard');

    // ── Journal ──────────────────────────────────────────────────────────────
    Route::get('/journal', function () {
        $entries = \App\Models\JournalEntry::where('user_id', Auth::id())
            ->orderByDesc('date')
            ->paginate(20);

        $all = \App\Models\JournalEntry::where('user_id', Auth::id())->get();

        $stats = [
            'entries'  => $all->count(),
            'lessons'  => $all->filter(fn ($e) => filled($e->lessons))->count(),
            'mistakes' => $all->filter(fn ($e) => filled($e->went_wrong))->count(),
            'winning'  => $all->filter(fn ($e) => filled($e->went_well))->count(),
        ];

        return view('journal.index', compact('entries', 'stats'));
    })->name('journal.index');

    Route::post('/journal', function (Request $request) {
        $request->validate([
            'date'       => 'required|date',
            'title'      => 'nullable|string|max:255',
            'mood'       => 'nullable|string|max:50',
            'went_well'  => 'nullable|string',
            'went_wrong' => 'nullable|string',
            'lessons'    => 'nullable|string',
            'image'      => 'nullable|image|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('journal', 'public');
        }

        $content = collect([
            $request->went_well  ? 'What went well: ' . $request->went_well : null,
            $request->went_wrong ? 'What went wrong: ' . $request->went_wrong : null,
            $request->lessons    ? 'Lessons: ' . $request->lessons : null,
        ])->filter()->implode("\n\n");

        \App\Models\JournalEntry::create([
            'user_id'    => Auth::id(),
            'date'       => $request->date,
            'title'      => $request->title,
            'mood'       => $request->mood,
            'image'      => $imagePath,
            'went_well'  => $request->went_well,
            'went_wrong' => $request->went_wrong,
            'lessons'    => $request->lessons,
            'content'    => $content !== '' ? $content : '-',
        ]);

        return redirect()->route('journal.index')->with('success', 'Journal entry added!');
    })->name('journal.store');

    Route::delete('/journal/{id}', function ($id) {
        $entry = \App\Models\JournalEntry::where('user_id', Auth::id())->findOrFail($id);

        if ($entry->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($entry->image);
        }

        $entry->delete();

        return redirect()->route('journal.index')->with('success', 'Journal entry deleted.');
    })->name('journal.destroy');

    // ── Accounts ─────────────────────────────────────────────────────────────
    Route::get('/accounts', function () {
        $accounts = \App\Models\Account::where('user_id', Auth::id())->get();
        return view('accounts.index', compact('accounts'));
    })->name('accounts.index');

        Route::get('/accounts/create', function () {
        return view('accounts.create');
    })->name('accounts.create');

    Route::post('/accounts', function (\Illuminate\Http\Request $request) {

        $request->validate([
            'name' => 'required|max:255',
            'broker' => 'nullable|max:255',
            'type' => 'required',
            'starting_balance' => 'required|numeric'
        ]);

        \App\Models\Account::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'broker' => $request->broker,
            'type' => $request->type,
            'starting_balance' => $request->starting_balance,
            'balance' => $request->starting_balance,
        ]);

        return redirect()->route('accounts.index');

    })->name('accounts.store');

    // ── Trades ───────────────────────────────────────────────────────────────
    Route::get('/trades', function (Request $request) {
        $accounts  = \App\Models\Account::where('user_id', Auth::id())->get();
        $setupTags = collect();

        $trades = \App\Models\Trade::where('user_id', Auth::id())
            ->orderByDesc('entry_time')
            ->paginate(25);

        return view('trades.index', compact('trades', 'accounts', 'setupTags'));
    })->name('trades.index');

    Route::get('/trades/create', function () {
        $accounts = \App\Models\Account::where('user_id', Auth::id())->get();
        return view('trades.create', compact('accounts'));
    })->name('trades.create');

    // ── Trade Calendar ──────────────────────────────────────────────────────
    Route::get('/trades/calendar', function (Request $request) {
        $monthParam = $request->query('month');
        try {
            $current = $monthParam
                ? \Carbon\Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
                : \Carbon\Carbon::now()->startOfMonth();
        } catch (\Exception $e) {
            $current = \Carbon\Carbon::now()->startOfMonth();
        }

        $trades = \App\Models\Trade::where('user_id', Auth::id())
            ->whereBetween('entry_time', [
                $current->copy()->startOfMonth(),
                $current->copy()->endOfMonth()->endOfDay(),
            ])
            ->get();

        $byDate = $trades->groupBy(fn($t) => \Carbon\Carbon::parse($t->entry_time)->format('Y-m-d'));

        return view('trades.calendar', compact('current', 'byDate'));
        })->name('trades.calendar');

    // ── Trade Gallery ───────────────────────────────────────────────────────
    Route::get('/trades/gallery', function () {
        $trades = \App\Models\Trade::where('user_id', Auth::id())
            ->where(function ($q) {
                $q->whereNotNull('screenshot_before')->orWhereNotNull('screenshot_after');
            })
            ->orderByDesc('entry_time')
            ->get();

        return view('trades.gallery', compact('trades'));
    })->name('trades.gallery');

    // trades closure
    Route::post('/trades', function (Request $request) {
        $grossPnl   = (float) $request->gross_pnl;
        $commission = (float) ($request->commission ?? 0);
        $netPnl     = $grossPnl - $commission;

        \App\Models\Trade::create([
            'user_id'           => Auth::id(),
            'account_id'        => $request->account_id,
            'instrument'        => $request->instrument,
            'direction'         => $request->direction,
            'quantity'          => $request->quantity,
            'entry_price'       => $request->entry_price,
            'exit_price'        => $request->exit_price,
            'entry_time'        => $request->entry_time,
            'exit_time'         => $request->exit_time,
            'gross_pnl'         => $grossPnl,
            'commission'        => $commission,
            'net_pnl'           => $netPnl,
            'status'            => $request->status,
            'setup'             => $request->setup_tag,
            'rating'            => $request->rating,
            'notes'             => $request->notes,
            'screenshot_before' => $request->hasFile('screenshot_before')
                ? $request->file('screenshot_before')->store('trades', 'public') : null,
            'screenshot_after'  => $request->hasFile('screenshot_after')
                ? $request->file('screenshot_after')->store('trades', 'public') : null,
        ]);

        return redirect()->route('trades.index')->with('success', 'Trade added successfully.');
    })->name('trades.store');

    // ── Import CSV ─────────────────────────────────────────────────────────────
    Route::get('/trades/import', function () {
        $accounts = \App\Models\Account::where('user_id', Auth::id())->get();
        return view('trades.import', compact('accounts'));
    })->name('trades.import');

    Route::post('/trades/import', function (Request $request) {
        $request->validate([
            'csv_file'   => 'required|file|mimes:csv,txt|max:10240',
            'account_id' => 'required|exists:accounts,id',
        ]);

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        if ($handle === false) {
            return back()->with('error', 'Gagal membaca file. Coba lagi.');
        }

        $aliases = [
            'instrument'  => ['instrument', 'symbol', 'ticker', 'pair', 'market'],
            'direction'   => ['direction', 'side', 'type', 'position'],
            'quantity'    => ['quantity', 'qty', 'size', 'volume', 'lots', 'contracts'],
            'entry_price' => ['entry_price', 'entry', 'entryprice', 'open_price', 'price_in', 'buy_price'],
            'exit_price'  => ['exit_price', 'exit', 'exitprice', 'close_price', 'price_out', 'sell_price'],
            'entry_time'  => ['entry_time', 'entry_date', 'open_time', 'open_date', 'date', 'opened'],
            'exit_time'   => ['exit_time', 'exit_date', 'close_time', 'close_date', 'closed'],
            'gross_pnl'   => ['gross_pnl', 'pnl', 'p&l', 'p_l', 'profit', 'gross', 'realized_pnl'],
            'commission'  => ['commission', 'commissions', 'fee', 'fees', 'cost'],
            'setup'       => ['setup', 'setup_tag', 'strategy', 'tag', 'tags'],
            'status'      => ['status', 'state'],
            'rating'      => ['rating', 'score', 'stars'],
            'notes'       => ['notes', 'note', 'comment', 'comments', 'remark'],
        ];

        $num = function ($v) {
            if ($v === null || $v === '') return null;
            $v = str_replace(',', '', (string) $v);
            $v = preg_replace('/[^0-9.\-]/', '', $v);
            return $v === '' ? null : (float) $v;
        };

        $parseDate = function ($v) {
            if (!$v) return null;
            try { return \Carbon\Carbon::parse($v); } catch (\Exception $e) { return null; }
        };

        $header = null;
        $imported = 0;
        $skipped = 0;
        $accountId = $request->input('account_id');

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === 1 && trim((string) ($row[0] ?? '')) === '') continue;

            if ($header === null) {
                $header = array_map(fn ($h) => strtolower(trim(str_replace([' ', '-'], '_', (string) $h))), $row);
                continue;
            }

            $row = array_pad($row, count($header), '');
            $assoc = [];
            foreach ($header as $i => $key) {
                $assoc[$key] = is_string($row[$i]) ? trim($row[$i]) : $row[$i];
            }

            $pick = function (string $field) use ($aliases, $assoc) {
                foreach ($aliases[$field] as $a) {
                    if (isset($assoc[$a]) && $assoc[$a] !== '') return $assoc[$a];
                }
                return null;
            };

            $instrument = $pick('instrument');
            if (!$instrument) { $skipped++; continue; }

            $dirRaw = strtolower((string) $pick('direction'));
            $direction = (str_contains($dirRaw, 'sell') || str_contains($dirRaw, 'short')) ? 'short' : 'long';

            $gross = $num($pick('gross_pnl')) ?? 0;
            $commission = $num($pick('commission')) ?? 0;

            $statusRaw = strtolower((string) $pick('status'));
            $status = in_array($statusRaw, ['open', 'closed', 'cancelled']) ? $statusRaw : 'closed';

            $rating = $num($pick('rating'));
            if ($rating !== null) { $rating = max(1, min(5, (int) $rating)); }

            \App\Models\Trade::create([
                'user_id'     => Auth::id(),
                'account_id'  => $accountId,
                'instrument'  => $instrument,
                'direction'   => $direction,
                'quantity'    => $num($pick('quantity')),
                'entry_price' => $num($pick('entry_price')),
                'exit_price'  => $num($pick('exit_price')),
                'entry_time'  => $parseDate($pick('entry_time')),
                'exit_time'   => $parseDate($pick('exit_time')),
                'gross_pnl'   => $gross,
                'commission'  => $commission,
                'net_pnl'     => $gross - $commission,
                'setup'       => $pick('setup'),
                'notes'       => $pick('notes'),
                'status'      => $status,
                'rating'      => $rating,
            ]);
            $imported++;
        }
        fclose($handle);

        return redirect()->route('trades.index')
            ->with('success', "Import selesai: {$imported} trade berhasil, {$skipped} baris dilewati.");
    })->name('trades.import.store');

    Route::delete('/trades/bulk-destroy', function () {
        return back();
    })->name('trades.bulk-destroy');

    Route::get('/trades/{id}', function ($id) {
        $trade = \App\Models\Trade::where('user_id', Auth::id())->findOrFail($id);
        return view('trades.show', compact('trade'));
    })->name('trades.show');

    Route::get('/trades/{id}/edit', function ($id) {
        $trade    = \App\Models\Trade::where('user_id', Auth::id())->findOrFail($id);
        $accounts = \App\Models\Account::where('user_id', Auth::id())->get();
        return view('trades.edit', compact('trade', 'accounts'));
    })->name('trades.edit');

    Route::delete('/trades/{id}', function ($id) {
        \App\Models\Trade::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect()->route('trades.index')->with('success', 'Trade deleted.');
    })->name('trades.destroy');
});




