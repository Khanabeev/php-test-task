<?php

namespace App\Operations\Notifications;

use App\Dto\MessageResultDto;
use App\Enums\Events;
use App\Enums\MessageTypes;
use App\Operations\Managers\GoodsReturnOperationManager;
use App\Operations\MessagesClient;
use App\Operations\NotificationEvents;
use App\Operations\Templates\GoodsReturnComplaintEmployeeEmailTemplate;
use SplObserver;
use SplSubject;
use function App\Operations\__;
use function App\Operations\getEmailsByPermit;
use function App\Operations\getResellerEmailFrom;

final class GoodsReturnComplaintEmployeeEmail implements SplObserver
{
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof GoodsReturnOperationManager) {
            $messageResult = new MessageResultDto();

            $data = $subject->getData();
            $resellerId = $data['resellerId'];
            $emailFrom = getResellerEmailFrom($resellerId);
            // Retrieve employees' email addresses from the settings.
            $emails = getEmailsByPermit($resellerId, Events::GOODS_RETURN);
            $emails = array_unique(array_filter($emails));
            if (!empty($emailFrom) && count($emails) > 0) {
                $template = new GoodsReturnComplaintEmployeeEmailTemplate();
                $templateData = $template->render($data);
                foreach ($emails as $email) {
                    $message = MessagesClient::sendMessage([
                        [
                            'type' => MessageTypes::EMAIL,
                            'emailFrom' => $emailFrom,
                            'emailTo' => $email,
                            'subject' => __('complaintEmployeeEmailSubject', $templateData, $resellerId),
                            'message' => __('complaintEmployeeEmailBody', $templateData, $resellerId),
                        ],
                    ], $resellerId, NotificationEvents::CHANGE_RETURN_STATUS);

                    $messageResult->sent = true;
                    $messageResult->success = true;
                    $messageResult->message = $message;
                }
            }

            $subject->setEmployeeMailNotification($messageResult);
        }
    }
}