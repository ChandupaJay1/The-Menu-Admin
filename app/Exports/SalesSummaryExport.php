<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesSummaryExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        $grouped = $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m-d');
        });

        $exportData = [];
        $totalOrders = 0;
        $totalRevenue = 0;
        $totalCompleted = 0;
        $totalPending = 0;
        $totalCancelled = 0;

        foreach ($grouped as $date => $dayOrders) {
            $dayTotalOrders = $dayOrders->count();
            $dayTotalRevenue = $dayOrders->sum('total_amount');
            $dayCompleted = $dayOrders->whereIn('status', ['completed', 'delivered'])->count();
            $dayPending = $dayOrders->whereIn('status', ['pending', 'processing'])->count();
            $dayCancelled = $dayOrders->where('status', 'cancelled')->count();

            $exportData[] = [
                $date,
                $dayTotalOrders,
                $dayTotalRevenue,
                $dayCompleted,
                $dayPending,
                $dayCancelled,
            ];

            $totalOrders += $dayTotalOrders;
            $totalRevenue += $dayTotalRevenue;
            $totalCompleted += $dayCompleted;
            $totalPending += $dayPending;
            $totalCancelled += $dayCancelled;
        }

        // Add an empty row for separation
        $exportData[] = ['', '', '', '', '', ''];
        
        // Add Grand Total row
        $exportData[] = [
            'GRAND TOTAL',
            $totalOrders,
            $totalRevenue,
            $totalCompleted,
            $totalPending,
            $totalCancelled,
        ];

        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Total Orders',
            'Total Revenue (Rs.)',
            'Completed Orders',
            'Processing/Pending Orders',
            'Cancelled Orders',
        ];
    }
}
