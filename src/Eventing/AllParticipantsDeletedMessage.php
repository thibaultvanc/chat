<?php

namespace Thibaultvanc\Chat\Eventing;

use Thibaultvanc\Chat\Models\Message;

class AllParticipantsDeletedMessage extends Event
{
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }
}
