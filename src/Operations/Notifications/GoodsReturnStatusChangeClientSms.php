<?php

namespace App\Operations\Notifications;

use App\Dto\MessageResultDto;
use App\Operations\Contractor;
use App\Operations\Enums\GoodsReturnType;
use App\Operations\Managers\GoodsReturnOperationManager;
use App\Operations\NotificationEvents;
use App\Operations\NotificationManager;
use App\Operations\Templates\GoodsReturnComplaintClientEmailTemplate;
use SplObserver;
use SplSubject;

final class GoodsReturnStatusChangeClientSms implements SplObserver
{
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof GoodsReturnOperationManager) {
            $messageResult = new MessageResultDto();

            $data = $subject->getData();

            $notificationType = GoodsReturnType::from($data['notificationType']);
            $client = Contractor::getById($data['clientId']);

            if ($notificationType === GoodsReturnType::CHANGE &&
                !empty($data['differences']['to']) &&
                !empty($client->mobile)) {
                $resellerId = $data['resellerId'];

                $template = new GoodsReturnComplaintClientEmailTemplate();
                $templateData = $template->render($data);

                $res = NotificationManager::send($resellerId, $client->id, NotificationEvents::CHANGE_RETURN_STATUS, (int)$data['differences']['to'], $templateData, $error);

                $messageResult->sent = true;

                if ($res) {
                    $messageResult->success = true;
                }

                if (!empty($error)) {
                    $messageResult->message = $error;
                }
            }

            $subject->setClientSmsNotification($messageResult);
        }
    }
}