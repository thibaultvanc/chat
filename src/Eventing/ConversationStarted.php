<?php

namespace Thibaultvanc\Chat\Eventing;

use Illuminate\Broadcasting\Channel;
use Thibaultvanc\Chat\Models\Conversation;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ConversationStarted extends Event implements ShouldBroadcast
{
    /**
     * @var Conversation
     */
    public $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function broadcastOn()
    {
        return new Channel('mc-chat-new-conversation');
    }

    public function broadcastWith()
    {
        return [
            'conversation' => [
                'id' => $this->conversation->id,
            ],
        ];
    }


}
