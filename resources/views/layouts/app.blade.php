<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ── Public routes ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (Auth::check()) return redirect()->route('dashboard');
    return view('welcome');
})->name('home');

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
        return view('journal.index', compact('entries'));
    })->name('journal.index');

    // ── Accounts ─────────────────────────────────────────────────────────────
    Route::get('/accounts', function () {
        $accounts = \App\Models\Account::where('user_id', Auth::id())->get();
        return view('accounts.index', compact('accounts'));
    })->name('accounts.index');

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

    Route::get('/trades/import', function () {
        return view('trades.import');
    })->name('trades.import');

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