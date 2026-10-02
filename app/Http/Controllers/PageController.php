<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('onboarding');
    }

    public function dashboard()
    {
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        // Real-time stats from DB
        $totalOrdersToday = \App\Models\Order::whereDate('created_at', $today)->count();
        $totalRevenueToday = \App\Models\Order::whereDate('created_at', $today)
            ->whereIn('status', ['completed', 'delivered', 'processing'])
            ->sum('total_price');
        $totalRevenue = \App\Models\Order::whereIn('status', ['completed', 'delivered', 'processing'])->sum('total_price');
        $newCustomersThisWeek = \App\Models\User::where('created_at', '>=', now()->startOfWeek())->count();

        // Upcoming orders alert for dashboard
        $todayUpcomingOrders = \App\Models\Order::with(['user', 'driver', 'items.food'])
            ->whereIn('status', ['pending', 'processing'])
            ->whereHas('items', function ($q) use ($today) {
                $q->whereDate('scheduled_date', $today);
            })
            ->latest()
            ->take(5)
            ->get();

        $todayScheduledCount = \App\Models\Order::whereHas('items', function ($q) use ($today) {
            $q->whereDate('scheduled_date', $today);
        })->count();

        // Top Upcoming Delivery Days (Next 30 days)
        $startDate = now()->startOfDay();
        $endDate30 = now()->addDays(30)->endOfDay();
        $endDate20 = now()->addDays(20)->endOfDay();

        $upcomingDeliveriesData = \App\Models\OrderItem::whereBetween('scheduled_date', [$startDate, $endDate30])
            ->whereNotNull('scheduled_date')
            ->selectRaw('DATE(scheduled_date) as date, count(*) as total')
            ->groupBy('date')
            ->get();

        $topUpcomingDays = $upcomingDeliveriesData
            ->filter(function($item) use ($endDate20) {
                return \Carbon\Carbon::parse($item->date)->lte($endDate20);
            })
            ->sortByDesc('total')
            ->take(5);

        // Last 7 days order counts for the mini chart (Mon → Sun relative to today)
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            return [
                'label' => now()->subDays($daysAgo)->format('D'),
                'count' => \App\Models\Order::whereDate('created_at', $date)->count(),
            ];
        });

        // Status breakdown for donut
        $statusCounts = \App\Models\Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pendingCount     = $statusCounts->get('pending', 0);
        $processingCount  = $statusCounts->get('processing', 0);
        $completedCount   = $statusCounts->get('completed', 0) + $statusCounts->get('delivered', 0);
        $recentOrders     = \App\Models\Order::with(['user', 'items.food'])->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalOrdersToday', 'totalRevenueToday', 'totalRevenue',
            'newCustomersThisWeek', 'last7Days', 'pendingCount',
            'processingCount', 'completedCount', 'recentOrders',
            'todayUpcomingOrders', 'todayScheduledCount', 'topUpcomingDays'
        ));
    }

    public function exportSalesSummary()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SalesSummaryExport, 'sales_summary_report.xlsx');
    }

    public function categories()
    {
        $categories = [
            ['name' => 'Breakfast', 'icon' => '🍳', 'type' => 'Breakfast', 'items' => \App\Models\Food::where('type', 'Breakfast')->count(), 'slug' => 'breakfast'],
            ['name' => 'Lunch',     'icon' => '☀️', 'type' => 'Lunch',     'items' => \App\Models\Food::where('type', 'Lunch')->count(),     'slug' => 'lunch'],
            ['name' => 'Dinner',    'icon' => '🌙', 'type' => 'Dinner',    'items' => \App\Models\Food::where('type', 'Dinner')->count(),    'slug' => 'dinner'],
            ['name' => 'All Dishes', 'icon' => '🍽️', 'type' => 'All',      'items' => \App\Models\Food::count(),                              'slug' => 'all'],
        ];

        return view('categories', compact('categories'));
    }

    public function orders()
    {
        return redirect()->route('orders');
    }

    public function drivers()
    {
        return view('drivers');
    }

    public function notifications(Request $request)
    {
        $query = \App\Models\UserNotification::with('user')->latest();

        $category = $request->query('category', 'all');
        if ($category !== 'all') {
            $query->where('type', $category);
        }

        $status = $request->query('status', 'all');
        if ($status === 'unread') {
            $query->where('is_read', false);
        }

        $notifications = $query->paginate(20)->withQueryString();
        $unreadCount = \App\Models\UserNotification::where('is_read', false)->count();

        return view('notifications', compact('notifications', 'unreadCount', 'category', 'status'));
    }

    public function category($slug)
    {
        $type = in_array(strtolower($slug), ['breakfast', 'lunch', 'dinner']) ? ucfirst(strtolower($slug)) : 'all';
        return redirect()->route('foods.index', ['type' => $type]);
    }

    public function messages()
    {
        return view('messages');
    }

    public function bills(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = \App\Models\Order::with(['user', 'driver', 'items.food'])->latest();

        if ($status === 'paid' || $status === 'completed') {
            $query->whereIn('status', ['completed', 'delivered']);
        } elseif ($status === 'pending') {
            $query->where('status', 'pending');
        }

        $bills = $query->paginate(15)->withQueryString();
        $totalBilled = \App\Models\Order::whereIn('status', ['completed', 'delivered'])->sum('total_price');

        return view('bills', compact('bills', 'totalBilled', 'status'));
    }

    public function checkoutSettings()
    {
        return view('settings.checkout');
    }

    public function securitySettings()
    {
        return view('settings.security');
    }

    public function markAllNotificationsRead()
    {
        \App\Models\UserNotification::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function markNotificationRead($id)
    {
        \App\Models\UserNotification::where('id', $id)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}
