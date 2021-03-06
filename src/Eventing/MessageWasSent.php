<?php

namespace Thibaultvanc\Chat\Eventing;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Thibaultvanc\Chat\Models\Message;

class MessageWasSent extends Event implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        
        return new Channel('mc-chat-conversation.'.$this->message->conversation_id);
        //return new PrivateChannel('mc-chat-conversation.'.$this->message->conversation_id);
    }

    public function broadcastWith()
    {
        $sender = $this->message->sender;
        $sender->class_type = get_class($sender);

        return [
            'message' => [
                'id'              => $this->message->getKey(),
                'body'            => $this->message->body,
                'conversation_id' => $this->message->conversation_id,
                'type'            => $this->message->type,
                'created_at'      => $this->message->created_at,
                'sender'          => $sender,
            ],
        ];
    }
}
