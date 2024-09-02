<?php

namespace Operations\Notification;

class NotificationService
{
    public function sendEmployeeNotifications(
        array                 $emails,
        string                $emailFrom,
        TemplateDataDTO       $templateData,
        NotificationResultDTO &$result,
        int                   $resellerId
    ): void
    {
        if (!empty($emailFrom) && count($emails) > 0) {
            foreach ($emails as $email) {
                MessagesClient::sendMessage([
                    [
                        'emailFrom' => $emailFrom,
                        'emailTo' => $email,
                        'subject' => __('complaintEmployeeEmailSubject', (array)$templateData, $resellerId),
                        'message' => __('complaintEmployeeEmailBody', (array)$templateData, $resellerId),
                    ],
                ], $resellerId, NotificationEvents::CHANGE_RETURN_STATUS);
                $result->notificationEmployeeByEmail = true;
            }
        }
    }

    public function sendClientNotifications(
        array                 $data,
                              $client,
        string                $emailFrom,
        TemplateDataDTO       $templateData,
        NotificationResultDTO &$result,
        int                   $resellerId
    ): void
    {
        if (!empty($emailFrom) && !empty($client->email)) {
            MessagesClient::sendMessage([
                [
                    'emailFrom' => $emailFrom,
                    'emailTo' => $client->email,
                    'subject' => __('complaintClientEmailSubject', (array)$templateData, $resellerId),
                    'message' => __('complaintClientEmailBody', (array)$templateData, $resellerId),
                ],
            ], $resellerId, $client->id, NotificationEvents::CHANGE_RETURN_STATUS, (int)$data['differences']['to']);
            $result->notificationClientByEmail = true;
        }

        if (!empty($client->mobile)) {
            $error = '';
            $res = NotificationManager::send(
                $resellerId,
                $client->id,
                NotificationEvents::CHANGE_RETURN_STATUS,
                (int)$data['differences']['to'],
                (array)$templateData,
                $error
            );
            if ($res) {
                $result->notificationClientBySms->isSent = true;
            }
            if (!empty($error)) {
                $result->notificationClientBySms->message = $error;
            }
        }
    }
}
