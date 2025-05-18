<?php

namespace App\Tests\Integrational;

use App\Model\Message;
use App\Service\Messenger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessengerTest extends KernelTestCase
{
    private ?Messenger $messenger;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->messenger = self::$kernel->getContainer()->get(Messenger::class);
    }

    /**
     * @dataProvider validMessageProvider
     * @test
     * @doesNotThrowException
     *
     * @param Message $message
     */
    public function shouldSendMessage(Message $message): void
    {
        ob_start();
        $this->messenger->send($message);
        $output = ob_get_clean();

        $this->assertSame($message->getType(), strtolower($output));
    }

    public function validMessageProvider(): array
    {
        return [
            'Message to be sent by SMS' => [$this->createMessage(Message::TYPE_SMS)],
            'Message to be sent by Email' => [$this->createMessage(Message::TYPE_EMAIL)],
        ];
    }

    private function createMessage(string $type): Message
    {
        $message = new Message();
        $message->setType($type);
        $message->setBody('any string');

        return $message;
    }
}
