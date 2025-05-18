<?php declare(strict_types=1);

namespace App\Service\MessageSenders;

use App\Model\Message;

interface SenderInterface
{
    public function supports(Message $message): bool;
    public function send(Message $message): void;
}
