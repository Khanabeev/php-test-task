<?php

namespace Operations\Notification;

use Exception;
use Operations\Constants;

class ReturnOperation extends ReferencesOperation
{

    /**
     * @var ReturnOperationService
     */
    public const TYPE_NEW = Constants::TYPE_NEW;
    private ReturnOperationService $returnOperationService;
    private NotificationService $notificationService;

    public function __construct(ReturnOperationService $returnOperationService, NotificationService $notificationService)
    {
        $this->returnOperationService = $returnOperationService;
        $this->notificationService = $notificationService;
    }

    /**
     * @throws Exception
     */
    public function doOperation(): OperationResultDTO
    {
        $data = (array)$this->getRequest('data');
        $result = $this->returnOperationService->initializeResult();

        //validation for resellerId
        $resellerId = $this->returnOperationService->validateResellerId($data['resellerId'] ?? null);

        //validation for notification type
        $notificationType = $this->returnOperationService->validateNotificationType($data['notificationType'] ?? null);

        //fetch Client
        $client = $this->returnOperationService->fetchClient($data, $resellerId);

        //fetch Employee and expert
        $creator = $this->returnOperationService->fetchEmployee($data['creatorId'], 'Creator');
        $expert = $this->returnOperationService->fetchEmployee($data['expertId'], 'Expert');

        //prepare template data
        $templateData = $this->returnOperationService->prepareTemplateData($data, $client, $creator, $expert, $notificationType, $resellerId);

        //validation for template data
        $this->returnOperationService->validateTemplateData($templateData);

        //reseller email from
        $emailFrom = getResellerEmailFrom($resellerId);// here for more dynamically need to send reseller id for getting that reseller's email

        $emails = getEmailsByPermit($resellerId, 'tsGoodsReturn');

        // send notification to employee
        $this->notificationService->sendEmployeeNotifications($emails, $emailFrom, $templateData, $result, $resellerId); //send employee notifications

        // Only send client notifications if the status has changed
        if ($notificationType === self::TYPE_NEW) {
            $this->notificationService->sendClientNotifications($data, $client, $emailFrom, $templateData, $result, $resellerId);
        }

        return $result;
    }
}