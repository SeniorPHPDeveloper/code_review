<?php

namespace App\Tests\Integrational;

use App\Enum\MessageTypeEnum;
use App\Exception\MessengerException;
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
     * @param string  $expectedOutput
     */
    public function shouldSendMessage(Message $message, $expectedOutput): void
    {
        ob_start();
        $this->messenger->send($message);
        $output = ob_get_clean();

        $this->assertSame($expectedOutput, $output);
    }

    /**
     * @test
     */
    public function shouldThrowAnExceptionIfMessageWithUnsupportedTypeIsGiven(): void
    {
        $type = 'unsupported message type';

        $this->expectException(MessengerException::class);
        $this->expectExceptionMessage(sprintf(
            'Unsupported message type %s',
            $type
        ));

        $this->messenger->send($this->createMessage($type));
    }

    public function validMessageProvider(): array
    {
        return [
            'Message to be sent by SMS' => [
                'message' => $this->createMessage(MessageTypeEnum::TYPE_SMS),

                'expectedOutput' => strtoupper(MessageTypeEnum::TYPE_SMS),
            ],

            'Message to be sent by Email' => [
                'message' => $this->createMessage(MessageTypeEnum::TYPE_EMAIL),

                'expectedOutput' => strtoupper(MessageTypeEnum::TYPE_EMAIL),
            ],
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
