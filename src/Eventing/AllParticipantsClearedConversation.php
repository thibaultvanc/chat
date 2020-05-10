<?php

namespace Thibaultvanc\Chat\Eventing;

use Thibaultvanc\Chat\Models\Conversation;

class AllParticipantsClearedConversation
{
    public $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }
}
