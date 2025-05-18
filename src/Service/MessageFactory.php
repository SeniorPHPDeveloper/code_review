<?php declare(strict_types=1);

namespace App\Service;

use App\Dto\NotificationDto;
use App\Model\Message;

class MessageFactory
{
    public function create(NotificationDto $notificationDto, string $messageType): Message
    {
        $message = new Message();
        $message->setBody($notificationDto->body);
        $message->setType($messageType);

        return $message;
    }
}
