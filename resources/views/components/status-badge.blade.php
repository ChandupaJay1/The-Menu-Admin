@php
    $map = [
        // Orders
        'pending'           => ['label' => 'Pending',          'cls' => 'bg-amber-50 text-amber-600 ring-amber-500/20'],
        'processing'        => ['label' => 'Processing',       'cls' => 'bg-blue-50 text-blue-600 ring-blue-500/20'],
        'completed'         => ['label' => 'Completed',        'cls' => 'bg-emerald-50 text-emerald-600 ring-emerald-500/20'],
        'cancelled'         => ['label' => 'Cancelled',        'cls' => 'bg-rose-50 text-rose-600 ring-rose-500/20'],
        'delivered'         => ['label' => 'Delivered',        'cls' => 'bg-emerald-50 text-emerald-600 ring-emerald-500/20'],
        'out_for_delivery'  => ['label' => 'Out for Delivery',  'cls' => 'bg-indigo-50 text-indigo-600 ring-indigo-500/20'],
        // Drivers
        'available'         => ['label' => 'Available',        'cls' => 'bg-emerald-50 text-emerald-600 ring-emerald-500/20'],
        'on_delivery'       => ['label' => 'On Delivery',      'cls' => 'bg-blue-50 text-blue-600 ring-blue-500/20'],
        'offline'           => ['label' => 'Offline',          'cls' => 'bg-gray-100 text-gray-500 ring-gray-400/20'],
        // Events
        'upcoming'          => ['label' => 'Upcoming',         'cls' => 'bg-amber-50 text-amber-600 ring-amber-500/20'],
        'ongoing'           => ['label' => 'Ongoing',          'cls' => 'bg-blue-50 text-blue-600 ring-blue-500/20'],
        'event_completed'   => ['label' => 'Completed',        'cls' => 'bg-emerald-50 text-emerald-600 ring-emerald-500/20'],
    ];

    $cfg = $map[$status]
        ?? ['label' => ucwords(str_replace('_', ' ', $status)), 'cls' => 'bg-gray-100 text-gray-600 ring-gray-400/20'];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full ring-1 ring-inset shadow-sm {{ $cfg['cls'] }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
    {{ $cfg['label'] }}
</span>
