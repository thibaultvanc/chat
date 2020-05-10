<?php

namespace Thibaultvanc\Chat\Http\Controllers;

use Chat;
use Thibaultvanc\Chat\Http\Requests\ClearConversation;
use Thibaultvanc\Chat\Http\Requests\DeleteMessage;
use Thibaultvanc\Chat\Http\Requests\GetParticipantMessages;
use Thibaultvanc\Chat\Http\Requests\StoreMessage;

class ConversationMessageController extends Controller
{
    protected $messageTransformer;

    public function __construct()
    {
        $this->setUp();
    }

    private function setUp()
    {
        if ($messageTransformer = config('Thibaultvanc_chat.transformers.message')) {
            $this->messageTransformer = app($messageTransformer);
        }
    }

    private function itemResponse($message)
    {
        if ($this->messageTransformer) {
            return fractal($message, $this->messageTransformer)->respond();
        }

        return response($message);
    }

    public function index(GetParticipantMessages $request, $conversationId)
    {
        $conversation = Chat::conversations()->getById($conversationId);
        $message = $conversation->messages;
        if ($this->messageTransformer) {
            return fractal($message, $this->messageTransformer)->respond();
        }
         /*  $message = Chat::conversation($conversation)
             ->setParticipant($request->getParticipant())
             ->setPaginationParams($request->getPaginationParams())
             ->getMessages(); */
        return  response(['data'=>$message]);
    }

    public function show(GetParticipantMessages $request, $conversationId, $messageId)
    {
        $message = Chat::messages()->getById($messageId);

        return $this->itemResponse($message);
    }

    public function store(StoreMessage $request, $conversationId)
    {
        $conversation = Chat::conversations()->getById($conversationId);
        $message = Chat::message($request->getMessageBody())
            ->from($request->getParticipant())
            ->to($conversation)
            ->send();

        return $this->itemResponse($message);
    }

    public function deleteAll(ClearConversation $request, $conversationId)
    {
        $conversation = Chat::conversations()->getById($conversationId);
        Chat::conversation($conversation)
            ->setParticipant($request->getParticipant())
            ->clear();

        return response('');
    }

    public function destroy(DeleteMessage $request, $conversationId, $messageId)
    {
        $message = Chat::messages()->getById($messageId);
        Chat::message($message)
            ->setParticipant($request->getParticipant())
            ->delete();

        return response('');
    }
}
