<?php declare(strict_types=1);

namespace App\Service\MessageSenders;

use App\Enum\MessageTypeEnum;
use App\Model\Message;

class EmailSender implements SenderInterface
{
    public function supports(Message $message): bool
    {
        return $message->type === MessageTypeEnum::TYPE_EMAIL;
    }

    public function send(Message $message): void
    {
        print "EMAIL";
    }
}
