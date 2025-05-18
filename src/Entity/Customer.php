<?php declare(strict_types=1);

namespace App\Entity;

use App\Model\Message;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="`id`", type="integer", nullable=false)
     */
    public int $id;

    /**
     * @ORM\Column(name="`customer_code`", type="string", length=32, nullable=false)
     */
    public string $code;

    /**
     * @ORM\Column(name="`notification_type`", type="string", length=32)
     */
    public string $notificationType = Message::TYPE_EMAIL;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }
    public function setNotificationType(string $notificationType): void
    {
        $this->notificationType = $notificationType;
    }
}
