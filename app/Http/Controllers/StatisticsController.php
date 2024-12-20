<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // tong tien 
        $totalRevenue = Order::sum('total');

        // tong don
        $totalOrders = Order::count();


        // Số lượng và tổng tiền đơn hàng theo các trạng thái mới
        $totalShippingOrders = Order::where('status', 'shipping')->count();
        $totalShippedOrders = Order::where('status', 'shipped')->count();
        $totalDeliveredOrders = Order::where('status', 'delivered')->count();

        $totalShippingRevenue = Order::where('status', 'shipping')->sum('total');
        $totalShippedRevenue = Order::where('status', 'shipped')->sum('total');
        $totalDeliveredRevenue = Order::where('status', 'delivered')->sum('total');

        // tong luong khach tren web
        $totalCustomers = User::count();

        // tien theo tttt
        $totalCanceledRevenue = Order::where('status', 'canceled')->sum('total');
        $totalPendingRevenue = Order::where('status', 'pending')->sum('total');
        $totalPaidRevenue = Order::where('status', 'paid')->sum('total');
        $totalShippingRevenue = Order::where('status', 'shipping')->sum('total');
        $totalShippedRevenue = Order::where('status', 'shipped')->sum('total');
        $totalDeliveredRevenue = Order::where('status', 'delivered')->sum('total');

        // tien theo thang
        $monthlyRevenue = Order::select(DB::raw('SUM(total) as total, MONTH(created_at) as month'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
        $revenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueData[$i] = isset($monthlyRevenue[$i]) ? $monthlyRevenue[$i] : 0;
        }

        $months = range(1, 12);

        $paidRevenueMonthly = Order::select(DB::raw('SUM(total) as total, MONTH(created_at) as month'))
            ->where('status', 'paid')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $pendingRevenueMonthly = Order::select(DB::raw('SUM(total) as total, MONTH(created_at) as month'))
            ->where('status', 'pending')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $canceledRevenueMonthly = Order::select(DB::raw('SUM(total) as total, MONTH(created_at) as month'))
            ->where('status', 'canceled')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $deliveredRevenueMonthly = Order::select(DB::raw('SUM(total) as total, MONTH(created_at) as month'))
            ->where('status', 'delivered')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $shippedRevenueMonthly = Order::select(DB::raw('SUM(total) as total, MONTH(created_at) as month'))
            ->where('status', 'shipped')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $shippingRevenueMonthly = Order::select(DB::raw('SUM(total) as total, MONTH(created_at) as month'))
            ->where('status', 'shipping')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
        $shippingRevenueCount = [];
        $shippedRevenueCount = [];
        $deliveredRevenueCount = [];
        $paidRevenueCount = [];
        $pendingRevenueCount = [];
        $canceledRevenueCount = [];

        //doanh thu theo pttt
        $paymentMethodsRevenue = Order::select('payment_method', DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();
        for ($i = 1; $i <= 12; $i++) {
            $paidRevenueCount[$i] = Order::where('status', 'paid')->whereMonth('created_at', $i)->count();
            $pendingRevenueCount[$i] = Order::where('status', 'pending')->whereMonth('created_at', $i)->count();
            $canceledRevenueCount[$i] = Order::where('status', 'canceled')->whereMonth('created_at', $i)->count();
            $shippingRevenueCount[$i] = Order::where('status', 'shipping')->whereMonth('created_at', $i)->count();
            $shippedRevenueCount[$i] = Order::where('status', 'shipped')->whereMonth('created_at', $i)->count();
            $deliveredRevenueCount[$i] = Order::where('status', 'delivered')->whereMonth('created_at', $i)->count();
        }

        $paymentRevenueData = [
            'paid' => [],
            'pending' => [],
            'canceled' => [],
            'shipping' => [],
            'shipped' => [],
            'delivered' => []
        ];

        foreach (Order::all() as $order) {
            $paymentMethod = $order->payment_method;
            $revenue = $order->total;


            if ($order->status == 'paid') {
                if (!isset($paymentRevenueData['paid'][$paymentMethod])) {
                    $paymentRevenueData['paid'][$paymentMethod] = 0;
                }
                $paymentRevenueData['paid'][$paymentMethod] += $revenue;
            } elseif ($order->status == 'pending') {
                if (!isset($paymentRevenueData['pending'][$paymentMethod])) {
                    $paymentRevenueData['pending'][$paymentMethod] = 0;
                }
                $paymentRevenueData['pending'][$paymentMethod] += $revenue;
            } elseif ($order->status == 'canceled') {
                if (!isset($paymentRevenueData['canceled'][$paymentMethod])) {
                    $paymentRevenueData['canceled'][$paymentMethod] = 0;
                }
                $paymentRevenueData['canceled'][$paymentMethod] += $revenue;
            } elseif ($order->status == 'shipping') {
                if (!isset($paymentRevenueData['shipping'][$paymentMethod])) {
                    $paymentRevenueData['shipping'][$paymentMethod] = 0;
                }
                $paymentRevenueData['shipping'][$paymentMethod] += $revenue;
            } elseif ($order->status == 'shipped') {
                if (!isset($paymentRevenueData['shipped'][$paymentMethod])) {
                    $paymentRevenueData['shipped'][$paymentMethod] = 0;
                }
                $paymentRevenueData['shipped'][$paymentMethod] += $revenue;
            } elseif ($order->status == 'delivered') {
                if (!isset($paymentRevenueData['delivered'][$paymentMethod])) {
                    $paymentRevenueData['delivered'][$paymentMethod] = 0;
                }
                $paymentRevenueData['delivered'][$paymentMethod] += $revenue;
            }
        }
        return view('admin.statistics.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'revenueData',
            'months',
            'paidRevenueCount',
            'pendingRevenueCount',
            'canceledRevenueCount',
            'shippingRevenueCount',
            'shippedRevenueCount',
            'deliveredRevenueCount',

            'totalPaidRevenue',
            'totalCanceledRevenue',
            'totalPendingRevenue',
            'totalShippingRevenue',
            'totalShippedRevenue',
            'totalDeliveredRevenue',
            'paymentMethodsRevenue',

            'paidRevenueMonthly',
            'pendingRevenueMonthly',
            'canceledRevenueMonthly',
            'shippingRevenueMonthly',
            'shippedRevenueMonthly',
            'deliveredRevenueMonthly',


            'paymentRevenueData',
            'totalShippingOrders',
            'totalShippedOrders',
            'totalDeliveredOrders',

        ));
    }
}
