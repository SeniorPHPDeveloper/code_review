<?php declare(strict_types=1);

namespace App\Model;

use App\Enum\MessageTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class Message
{

    /**
     * @Assert\NotBlank(message="body cannot be empty", payload="101")
     */
    public string $body = '';

    /**
     * @Assert\NotBlank(message="type cannot be blank", payload="102")
     */
    public string $type = MessageTypeEnum::TYPE_EMAIL;

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
