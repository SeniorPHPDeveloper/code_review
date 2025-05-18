<?php

namespace App\Service;

use App\Model\Message;

class SMSSender implements SenderInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(Message $message): bool
    {
        return $message->type === Message::TYPE_EMAIL;
    }
    public function send(Message $message): void
    {
        print "SMS";
    }
}
