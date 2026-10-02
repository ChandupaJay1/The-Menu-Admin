<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with(['user', 'driver', 'items'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Order Number',
            'Customer Name',
            'Customer Phone',
            'Total Amount',
            'Status',
            'Payment Method',
            'Payment Status',
            'Scheduled Date',
            'Created At',
        ];
    }

    public function map($order): array
    {
        $scheduledDate = $order->items->min('scheduled_date') ? \Carbon\Carbon::parse($order->items->min('scheduled_date'))->format('Y-m-d') : 'N/A';

        return [
            $order->id,
            $order->order_number,
            $order->customer_name ?? ($order->user->name ?? 'N/A'),
            $order->customer_phone ?? ($order->user->phone ?? 'N/A'),
            'Rs. ' . number_format($order->total_amount, 2),
            ucfirst($order->status),
            $order->payment_method,
            ucfirst(str_replace('_', ' ', $order->payment_status)),
            $scheduledDate,
            $order->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
