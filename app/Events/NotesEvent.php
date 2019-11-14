<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotesEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notes;

    public function __construct($notes)
    {
        $this->note = $notes;
    }

    public function broadcastOn() {
        return new Channel('notes');
    }

    public function broadcastWith() {
        return [
            'title' => $this->note->title,
            'description' => $this->note->description,
        ];
    }
}
