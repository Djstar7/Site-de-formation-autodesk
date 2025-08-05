<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function indexDashboard()
    {
        try {
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();

            // Totaux
            $totalUsers = DB::table('usersone')->count();
            $totalTrainings = DB::table('trainings')->count();
            $totalPlans = DB::table('plans')->count();
            $totalOrders = DB::table('orders')->count();
            $startOfCurrentMonth = Carbon::now()->startOfMonth();
            $endOfCurrentMonth = Carbon::now()->endOfMonth();
            $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
            $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();

            // Ventes du jour
            $salesToday = DB::table('orders')
                ->whereDate('created_at', $today)
                ->where('status_orders', 'completed')
                ->sum('total_price_orders');

            // Ventes d'hier
            $salesYesterday = DB::table('orders')
                ->whereDate('created_at', $yesterday)
                ->where('status_orders', 'completed')
                ->sum('total_price_orders');

            // Ventes par jour du mois actuel
            $salesByDay = DB::table('orders')
                ->selectRaw('DATE(created_at) as date, SUM(total_price_orders) as total')
                ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
                ->where('status_orders', 'completed')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date', 'ASC')
                ->get();

            // Total ventes mois actuel
            $currentMonthSales = DB::table('orders')
                ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
                ->where('status_orders', 'completed')
                ->sum('total_price_orders');

            // Total ventes mois précédent
            $previousMonthSales = DB::table('orders')
                ->whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])
                ->where('status_orders', 'completed')
                ->sum('total_price_orders');

            return response()->json([
                'message' => 'Statistiques du tableau de bord',
                'data' => [
                    'totals' => [
                        'users' => $totalUsers,
                        'trainings' => $totalTrainings,
                        'plans' => $totalPlans,
                        'orders' => $totalOrders,
                    ],
                    'sales' => [
                        'today' => $salesToday,
                        'yesterday' => $salesYesterday,
                        'salesByDay' => $salesByDay,
                        'currentMonthSales' => $currentMonthSales,
                        'previousMonthSales' => $previousMonthSales,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des statistiques',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
