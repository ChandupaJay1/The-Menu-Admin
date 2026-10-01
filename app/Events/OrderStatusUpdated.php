<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $action;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, string $action = 'updated')
    {
        $this->order = $order;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
        ];
    }

    public static function safeDispatch(Order $order, string $action = 'updated')
    {
        try {
            self::dispatch($order, $action);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcasting OrderStatusUpdated skipped: ' . $e->getMessage());
        }
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $counts = Order::selectRaw('status, count(*) as total')
                       ->groupBy('status')
                       ->pluck('total', 'status');

        $totalAll = $counts->sum();
        $pendingCount = $counts->get('pending', 0);
        $processingCount = $counts->get('processing', 0) + $counts->get('on_delivery', 0) + $counts->get('on_the_way', 0) + $counts->get('picked_up', 0);
        $completedCount = $counts->get('completed', 0) + $counts->get('delivered', 0);

        return [
            'id' => $this->order->id,
            'status' => $this->order->status,
            'action' => $this->action,
            'driver' => $this->order->driver ? [
                'id' => $this->order->driver->id,
                'name' => $this->order->driver->name,
                'status' => $this->order->driver->status,
                'phone' => $this->order->driver->phone,
                'vehicle_type' => $this->order->driver->vehicle_type,
                'vehicle_number' => $this->order->driver->vehicle_number,
            ] : null,
            'customer_name' => $this->order->user->name ?? 'Guest',
            'stats' => [
                'total' => $totalAll,
                'pending' => $pendingCount,
                'processing' => $processingCount,
                'completed' => $completedCount,
            ],
        ];
    }
}
