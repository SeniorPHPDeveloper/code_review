<?php declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class NotificationDto
{
    /**
     * @Assert\NotNull
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    public ?string $body = null;
}
