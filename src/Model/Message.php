<?php declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    public const TYPE_SMS = 'sms';
    public const TYPE_EMAIL = 'email';

    /**
     * @Assert\NotBlank(message="body cannot be empty", payload="101")
     */
    public string $body = '';

    /**
     * @Assert\NotBlank(message="type cannot be blank", payload="102")
     */
    public string $type = self::TYPE_EMAIL;

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
