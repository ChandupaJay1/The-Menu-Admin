<?php

namespace App\Events;

use App\Models\Driver;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $driver;

    /**
     * Create a new event instance.
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('drivers'),
        ];
    }

    public static function safeDispatch(Driver $driver)
    {
        try {
            self::dispatch($driver);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcasting DriverStatusUpdated skipped: ' . $e->getMessage());
        }
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $drivers = Driver::all();
        $stats = [
            'total'        => $drivers->count(),
            'available'    => $drivers->where('status', 'available')->count(),
            'on_delivery'  => $drivers->where('status', 'on_delivery')->count(),
            'offline'      => $drivers->where('status', 'offline')->count(),
        ];

        return [
            'id' => $this->driver->id,
            'name' => $this->driver->name,
            'status' => $this->driver->status,
            'stats' => $stats,
        ];
    }
}
