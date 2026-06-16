<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index()
{
    // Ini cuma contoh data dummy supaya view-nya nggak error
    $data = [
        'accountId' => null,
        'accounts' => collect([]),
        'period' => 7,
        'totalNetPnl' => 0,
        'totalCommission' => 0,
        'winRate' => 0,
        'wins' => 0,
        'losses' => 0,
        'totalTrades' => 0,
        'profitFactor' => 0,
        'avgWin' => 0,
        'maxDrawdown' => 0,
        'worstTrade' => 0,
        'avgTradePnl' => 0,
        'bestTrade' => 0,
        'currentStreak' => 0,
        'streakType' => 'win',
        'avgDuration' => 0,
        'equityCurve' => [],
        'pnlByInstrument' => collect([]),
        'dailyPnl' => [],
        'recentTrades' => collect([]),
    ];

    return view('dashboard.index', $data);
}
}