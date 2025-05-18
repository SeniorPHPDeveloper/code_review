<?php declare(strict_types=1);

namespace App\Exception;

use Exception;

class MessengerException extends Exception
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}
