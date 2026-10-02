<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderHistoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): \Illuminate\Support\Collection
    {
        return Activity::where('subject_type', \App\Models\Order::class)
            ->with('causer', 'subject')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date & Time',
            'Order ID',
            'Action',
            'Performed By',
            'Description',
            'Properties (Changes)'
        ];
    }

    public function map($activity): array
    {
        $subject = $activity->subject;
        $orderId = $subject ? ($subject->order_number ?? $subject->id) : 'Unknown';

        return [
            $activity->created_at->format('Y-m-d H:i:s'),
            $orderId,
            ucfirst($activity->event),
            $activity->causer ? $activity->causer->name : 'System',
            $activity->description,
            json_encode($activity->properties)
        ];
    }
}
