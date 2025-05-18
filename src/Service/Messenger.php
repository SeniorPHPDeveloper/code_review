<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Message;
use App\Service\MessageSenders\SenderInterface;

class Messenger
{
    /**
     * @var SenderInterface[]
     */
    protected iterable $senders = [];

    /**
     * @param SenderInterface[] $senders
     */
    public function __construct(iterable $senders = [])
    {
        $this->senders = $senders;
    }

    public function send(Message $message): void
    {
        foreach ($this->senders as $sender) {
            if ($sender->supports($message)) {
                $sender->send($message);

                return;
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'Unsupported message type %s',
            $message->getType(),
        ));
    }
}
