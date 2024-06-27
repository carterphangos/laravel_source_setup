<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportantAnnouncementCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public $userId;

    public function __construct(string $message, string $userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('go-news'),
        ];
    }

    public function broadcastAs()
    {
        return 'new-important';
    }
}
