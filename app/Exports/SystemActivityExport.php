<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SystemActivityExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): \Illuminate\Support\Collection
    {
        return Activity::where('subject_type', '!=', \App\Models\Order::class)
            ->orWhereNull('subject_type')
            ->with('causer')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date & Time',
            'Subject Type',
            'Action',
            'Performed By',
            'Description',
            'Properties (Changes)'
        ];
    }

    public function map($activity): array
    {
        $subject = $activity->subject_type ? class_basename($activity->subject_type) : 'System';

        return [
            $activity->created_at->format('Y-m-d H:i:s'),
            $subject,
            ucfirst($activity->event),
            $activity->causer ? $activity->causer->name : 'System',
            $activity->description,
            json_encode($activity->properties)
        ];
    }
}
