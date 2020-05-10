<?php

namespace Thibaultvanc\Chat\Commanding;

interface CommandHandler
{
    public function handle($command);
}
