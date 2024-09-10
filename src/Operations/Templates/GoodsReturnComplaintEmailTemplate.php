<?php

namespace App\Operations\Templates;

use App\Dto\EmailTemplateDto;
use App\Exceptions\ValidationException;
use App\Operations\Contractor;
use App\Operations\Employee;
use App\Operations\Enums\GoodsReturnType;
use App\Operations\Status;
use function App\Operations\__;

abstract class GoodsReturnComplaintEmailTemplate
{
    /**
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    protected function prepare(array $data): array
    {
        $notificationType = GoodsReturnType::from($data['notificationType']);
        $resellerId = $data['resellerId'];

        $creator = Employee::getById((int)$data['creatorId']);

        $differences = '';
        if ($notificationType === GoodsReturnType::NEW) {
            $differences = __('NewPositionAdded', null, $resellerId);
        } else if ($notificationType === GoodsReturnType::CHANGE && !empty($data['differences'])) {
            $differences = __('PositionStatusHasChanged', [
                'FROM' => Status::getName((int)$data['differences']['from']),
                'TO'   => Status::getName((int)$data['differences']['to']),
            ], $resellerId);
        }

        $expert = Employee::getById((int)$data['expertId']);

        $client = Contractor::getById((int)$data['clientId']);
        $clientFullName = $client->getFullName();
        if (empty($client->getFullName())) {
            $clientFullName = $client->name;
        }

        $templateData = [
            'COMPLAINT_ID'       => intval($data['complaintId'] ?? null),
            'COMPLAINT_NUMBER'   => strval($data['complaintNumber'] ?? null),
            'CREATOR_ID'         => intval($data['creatorId'] ?? null),
            'CREATOR_NAME'       => $creator->getFullName(),
            'EXPERT_ID'          => intval($data['expertId'] ?? null),
            'EXPERT_NAME'        => $expert->getFullName(),
            'CLIENT_ID'          => intval($data['clientId'] ?? null),
            'CLIENT_NAME'        => $clientFullName,
            'CONSUMPTION_ID'     => intval($data['consumptionId'] ?? null),
            'CONSUMPTION_NUMBER' => strval($data['consumptionNumber'] ?? null),
            'AGREEMENT_NUMBER'   => strval($data['agreementNumber'] ?? null),
            'DATE'               => strval($data['date'] ?? null),
            'DIFFERENCES'        => $differences,
        ];

        $this->validateTemplateData($templateData);

        return $templateData;
    }

    abstract public function render(array $data): EmailTemplateDto;

    /**
     * @throws ValidationException
     */
    protected function validateTemplateData(array $templateData): void
    {
        //  If even one variable for the template is not set, do not send notifications.
        foreach ($templateData as $key => $tempData) {
            if (empty($tempData)) {
                throw new ValidationException("template-data-{$key}-is-empty");
            }
        }
    }
}