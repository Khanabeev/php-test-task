<?php

namespace Operations\Notification;

use Exception;
use Operations\Constants;

class ReturnOperationService
{
    public const TYPE_CHANGE = Constants::TYPE_CHANGE;
    public const TYPE_NEW = Constants::TYPE_NEW;

    public function initializeResult(): OperationResultDTO
    {
        return new OperationResultDTO();
    }

    public function validateResellerId($resellerId): int
    {
        if (empty((int)$resellerId)) {
            throw new Exception('Empty resellerId', 400);
        }
        return (int)$resellerId;
    }

    /**
     * @throws Exception
     */
    public function validateNotificationType($notificationType): int
    {
        if (empty((int)$notificationType)) {
            throw new Exception('Empty notificationType', 400);
        }
        return (int)$notificationType;
    }

    /**
     * @throws Exception
     */
    public function fetchClient(array $data, int $resellerId): Contractor
    {
        $client = Contractor::getById((int)$data['clientId']);
        if ($client->type !== Contractor::TYPE_CUSTOMER || $client->Seller->id !== $resellerId) {
            throw new Exception('Client not found!', 400);
        }
        return $client;
    }

    /**
     * @throws Exception
     */
    public function fetchEmployee($employeeId, $role): Contractor
    {
        $employee = Employee::getById((int)$employeeId);
        if (!$employee) {
            throw new Exception("{$role} not found!", 400);
        }
        return $employee;
    }

    public function prepareTemplateData(
        array $data,
              $client,
              $creator,
              $expert,
              $notificationType,
              $resellerId
    ): TemplateDataDTO {
        $cFullName = $client->getFullName() ?: $client->name;

        $differences = '';
        if ($notificationType === self::TYPE_NEW) {
            $differences = __('NewPositionAdded', null, $resellerId);
        } elseif ($notificationType === self::TYPE_CHANGE && !empty($data['differences'])) {
            $differences = __('PositionStatusHasChanged', [
                'FROM' => Status::getName((int)$data['differences']['from']),
                'TO' => Status::getName((int)$data['differences']['to']),
            ], $resellerId);
        }

        return new TemplateDataDTO(
            (int)$data['complaintId'],
            (string)$data['complaintNumber'],
            (int)$data['creatorId'],
            $creator->getFullName(),
            (int)$data['expertId'],
            $expert->getFullName(),
            (int)$data['clientId'],
            $cFullName,
            (int)$data['consumptionId'],
            (string)$data['consumptionNumber'],
            (string)$data['agreementNumber'],
            (string)$data['date'],
            $differences
        );
    }

    public function validateTemplateData(TemplateDataDTO $templateData): void
    {
        foreach (get_object_vars($templateData) as $key => $value) {
            if (empty($value)) {
                throw new Exception("Template Data ({$key}) is empty!", 500);
            }
        }
    }
}