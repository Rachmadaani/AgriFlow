<?php

namespace App\Http\Controllers;

use App\Models\Harvest;
use App\Models\Expense;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data untuk dashboard
        $todayHarvests = Harvest::where('user_id', $user->id)
            ->whereDate('date', today())
            ->with('plant')
            ->get();

        $weeklyHarvests = Harvest::where('user_id', $user->id)
            ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();

        $totalWeeklyKg = $weeklyHarvests->sum('weight_kg');
        $totalWeeklyRevenue = $weeklyHarvests->sum(function ($h) {
            return $h->weight_kg * $h->price_per_kg;
        });

        $recentHarvests = Harvest::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->with('plant')
            ->get();

        $plants = Plant::where('user_id', $user->id)->get();

        // Chart data (7 hari terakhir)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyHarvest = Harvest::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->sum('weight_kg');
            $chartData[] = [
                'date' => $date->format('d/m'),
                'kg' => $dailyHarvest
            ];
        }

        return view('dashboard', compact(
            'todayHarvests',
            'totalWeeklyKg',
            'totalWeeklyRevenue',
            'recentHarvests',
            'plants',
            'chartData'
        ));
    }

    public function profitReport()
    {
        $user = Auth::user();

        $totalRevenue = Harvest::where('user_id', $user->id)
            ->get()
            ->sum(function ($h) {
                return $h->weight_kg * $h->price_per_kg;
            });

        $totalExpense = Expense::where('user_id', $user->id)->sum('amount');

        $netProfit = $totalRevenue - $totalExpense;

        return view('reports.profit', compact('totalRevenue', 'totalExpense', 'netProfit'));
    }
}
