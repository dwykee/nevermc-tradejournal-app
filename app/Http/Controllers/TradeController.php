<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index()
    {
        $trades = Trade::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('trades.index', compact('trades'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('trades.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id'        => ['required', 'exists:accounts,id'],
            'instrument'        => ['required', 'string', 'max:255'],
            'direction'         => ['required', 'in:long,short'],
            'quantity'          => ['required', 'numeric', 'min:0'],
            'entry_time'        => ['required', 'date'],
            'entry_price'       => ['required', 'numeric'],
            'exit_time'         => ['nullable', 'date'],
            'exit_price'        => ['nullable', 'numeric'],
            'gross_pnl'         => ['required', 'numeric'],
            'commission'        => ['nullable', 'numeric', 'min:0'],
            'status'            => ['required', 'in:closed,open,cancelled'],
            'setup_tag'         => ['nullable', 'string', 'max:255'],
            'rating'            => ['nullable', 'integer', 'min:1', 'max:5'],
            'screenshot_before' => ['nullable', 'image', 'max:5120'],
            'screenshot_after'  => ['nullable', 'image', 'max:5120'],
        ]);

        $commission = $validated['commission'] ?? 0;
        $netPnl     = $validated['gross_pnl'] - $commission;

        // Simpan gambar ke storage/app/public/trades (butuh: php artisan storage:link)
        $beforePath = $request->hasFile('screenshot_before')
            ? $request->file('screenshot_before')->store('trades', 'public')
            : null;

        $afterPath = $request->hasFile('screenshot_after')
            ? $request->file('screenshot_after')->store('trades', 'public')
            : null;

        Trade::create([
            'user_id'           => auth()->id(),
            'account_id'        => $validated['account_id'],
            'instrument'        => $validated['instrument'],
            'direction'         => $validated['direction'],
            'quantity'          => $validated['quantity'],
            'entry_time'        => $validated['entry_time'],
            'entry_price'       => $validated['entry_price'],
            'exit_time'         => $validated['exit_time'] ?? null,
            'exit_price'        => $validated['exit_price'] ?? null,
            'gross_pnl'         => $validated['gross_pnl'],
            'commission'        => $commission,
            'net_pnl'           => $netPnl,
            'status'            => $validated['status'],
            'setup'             => $validated['setup_tag'] ?? null,
            'rating'            => $validated['rating'] ?? null,
            'screenshot_before' => $beforePath,
            'screenshot_after'  => $afterPath,
        ]);

        return redirect()
            ->route('trades.index')
            ->with('success', 'Trade saved successfully.');
    }

    public function calendar(Request $request)
    {
        $monthParam = $request->query('month');

        try {
            $current = $monthParam
                ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
                : Carbon::now()->startOfMonth();
        } catch (\Exception $e) {
            $current = Carbon::now()->startOfMonth();
        }

        $trades = Trade::where('user_id', auth()->id())
            ->whereBetween('entry_time', [
                $current->copy()->startOfMonth(),
                $current->copy()->endOfMonth()->endOfDay(),
            ])
            ->get();

        $byDate = $trades->groupBy(function ($trade) {
            return Carbon::parse($trade->entry_time)->format('Y-m-d');
        });

        return view('trades.calendar', compact('current', 'byDate'));
    }

    public function gallery()
    {
        $trades = Trade::where('user_id', auth()->id())
            ->where(function ($query) {
                $query->whereNotNull('screenshot_before')
                    ->orWhereNotNull('screenshot_after');
            })
            ->latest()
            ->get();

        return view('trades.gallery', compact('trades'));
    }
}
