<?php

namespace Operations\Notification;


class OperationResultDTO
{
    public bool $notificationEmployeeByEmail = false;
    public bool $notificationClientByEmail = false;
    public NotificationClientBySmsDTO $notificationClientBySms;

    public function __construct()
    {
        $this->notificationClientBySms = new NotificationClientBySmsDTO();
    }

    public function toArray(): array
    {
        return [
            'notificationEmployeeByEmail' => $this->notificationEmployeeByEmail,
            'notificationClientByEmail' => $this->notificationClientByEmail,
            'notificationClientBySms' => [
                'isSent' => $this->notificationClientBySms->isSent,
                'message' => $this->notificationClientBySms->message,
            ],
        ];
    }
}
