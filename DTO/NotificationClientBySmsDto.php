<?php

namespace Operations\Notification;

class NotificationClientBySmsDTO
{
    public bool $isSent = false;
    public string $message = '';

    public function __construct(bool $isSent = false, string $message = '')
    {
        $this->isSent = $isSent;
        $this->message = $message;
    }
}