<?php

namespace App\Operations\Notifications;

use App\Dto\MessageResultDto;
use App\Enums\MessageTypes;
use App\Operations\Contractor;
use App\Operations\Enums\GoodsReturnType;
use App\Operations\Managers\GoodsReturnOperationManager;
use App\Operations\MessagesClient;
use App\Operations\NotificationEvents;
use App\Operations\Templates\GoodsReturnComplaintClientEmailTemplate;
use SplObserver;
use SplSubject;
use function App\Operations\__;
use function App\Operations\getResellerEmailFrom;

final class GoodsReturnComplaintClientEmail implements SplObserver
{
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof GoodsReturnOperationManager) {
            $messageResult = new MessageResultDto();

            $data = $subject->getData();

            $notificationType = GoodsReturnType::from($data['notificationType']);
            $client = Contractor::getById($data['clientId']);
            $emails = array_filter([$client->email]);
            $emails = array_unique(array_filter($emails));

            if ($notificationType === GoodsReturnType::CHANGE &&
                count($emails) > 0 &&
                !empty($data['differences']['to'])) {
                $resellerId = $data['resellerId'];
                $emailFrom = getResellerEmailFrom($resellerId);

                $template = new GoodsReturnComplaintClientEmailTemplate();
                $templateData = $template->render($data);
                foreach ($emails as $email) {
                    $message = MessagesClient::sendMessage([
                        [
                            'type' => MessageTypes::EMAIL,
                            'emailFrom' => $emailFrom,
                            'emailTo' => $email,
                            'subject' => __('complaintClientEmailSubject', $templateData, $resellerId),
                            'message' => __('complaintClientEmailBody', $templateData, $resellerId),
                        ],
                    ], $resellerId, NotificationEvents::CHANGE_RETURN_STATUS);

                    $messageResult->sent = true;
                    $messageResult->success = true;
                    $messageResult->message = $message;
                }
            }

            $subject->setClientMailNotification($messageResult);
        }
    }
}