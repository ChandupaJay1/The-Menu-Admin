<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Exports\OrderHistoryExport;
use App\Exports\SystemActivityExport;
use Maatwebsite\Excel\Facades\Excel;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Order History Logs
        $orderLogs = Activity::where('subject_type', \App\Models\Order::class)
            ->with('causer')
            ->latest()
            ->paginate(15, ['*'], 'order_page');

        // System Activity Logs (Users, Food items, etc.)
        $systemLogs = Activity::where('subject_type', '!=', \App\Models\Order::class)
            ->orWhereNull('subject_type')
            ->with('causer')
            ->latest()
            ->paginate(15, ['*'], 'system_page');

        return view('logs.index', compact('orderLogs', 'systemLogs'));
    }

    public function exportOrderHistory()
    {
        return Excel::download(new OrderHistoryExport, 'order_history.xlsx');
    }

    public function exportSystemActivity()
    {
        return Excel::download(new SystemActivityExport, 'system_activity.xlsx');
    }
}
