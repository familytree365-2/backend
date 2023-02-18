<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

class MessagePosted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $conversation;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, User $user, Conversation $conversation)
    {
        $this->message = $message;
        $this->user = $user;
        $this->conversation = $conversation;
    }
    /**
     * Determine if this event should broadcast
     * 
     * @return bool
     */
    public function broadcastWhen()
    {
        return $this->conversation->status == 1;
    }
    /**
     * Get the data to broadcast
     * 
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'user' => $this->user,
            'message' => $this->message,
            'conversation' => $this->conversation
        ];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-'.$this->conversation->id);
    }
}
