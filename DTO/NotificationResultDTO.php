<?php

namespace Operations\Notification;

use NotificationClientBySmsDTO;

class NotificationResultDTO
{
    public bool $notificationEmployeeByEmail = false;
    public bool $notificationClientByEmail = false;
    public NotificationClientBySmsDTO $notificationClientBySms;

    public function __construct()
    {
        $this->notificationClientBySms = new NotificationClientBySmsDTO();
    }
}


