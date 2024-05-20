<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCommentEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postName;

    public $authorName;

    public $content;

    public function __construct(Comment $comment)
    {
        $this->postName = $comment->post->title;
        $this->authorName = $comment->user->name;
        $this->content = $comment->content;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('comments'),
        ];
    }

    public function broadcastAs()
    {
        return 'new-comment';
    }
}
