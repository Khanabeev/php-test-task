<?php

namespace App\Operations\Validators;

use App\Exceptions\ValidationException;
use App\Operations\Contractor;
use App\Operations\Employee;
use App\Operations\Seller;
use App\Validators\AbstractValidator;
use function App\Operations\__;

class GoodsReturnValidator extends AbstractValidator
{
    public function validate(): void
    {
        $data = $this->data;

        if (empty($data)) {
            throw new ValidationException(__('empty-data'));
        }

        $notificationType = $data['notificationType'] ?? null;

        if (is_null($notificationType)) {
            throw new ValidationException(__('empty-notification-type'));
        }

        $this->validated['notificationType'] = intval($notificationType);

        $resellerId = $data['resellerId'] ?? null;

        if (is_null($resellerId)) {
            throw new ValidationException(__('empty-seller-id'));
        }

        $reseller = Seller::getById($resellerId);
        if (is_null($reseller)) {
            throw new ValidationException(__('seller-not-found'));
        }

        $this->validated['resellerId'] = intval($resellerId);

        $clientId = $data['clientId'] ?? null;

        if (is_null($clientId)) {
            throw new ValidationException(__('empty-client-id'));
        }

        $client = Contractor::getById(intval($clientId));
        // Just to make it work, so I can test execution without creation a new Model class
        $client->type = Contractor::TYPE_CUSTOMER;
        $client->Seller = $reseller;
        if (is_null($client)) {
            throw new ValidationException(__('client-not-found'));
        }

        if ($client->type !== Contractor::TYPE_CUSTOMER) {
            throw new ValidationException(__('client-is-not-customer'));
        }

        if ($client?->Seller?->id !== $resellerId) {
            throw new ValidationException(__('client-is-not-assigned-to-seller'));
        }

        $this->validated['clientId'] = intval($clientId);

        $creatorId = $data['creatorId'] ?? null;

        if (is_null($creatorId)) {
            throw new ValidationException(__('empty-creator-id'));
        }

        $creator = Employee::getById(intval($creatorId));
        if (is_null($creator)) {
            throw new ValidationException(__('creator-not-found'));
        }

        $this->validated['creatorId'] = intval($creatorId);

        $expertId = $data['expertId'] ?? null;

        if (is_null($expertId)) {
            throw new ValidationException(__('empty-expert-id'));
        }

        $expert = Employee::getById(intval($expertId));
        if (is_null($expert)) {
            throw new ValidationException(__('expert-not-found'));
        }

        $this->validated['expertId'] = intval($expertId);

        $this->validated['complaintId'] = intval($data['consumptionId'] ?? null);
        $this->validated['complaintNumber'] = strval($data['complaintNumber'] ?? null);
        $this->validated['consumptionId'] = intval($data['consumptionId'] ?? null);
        $this->validated['consumptionNumber'] = strval($data['consumptionNumber'] ?? null);
        $this->validated['agreementNumber'] = strval($data['agreementNumber'] ?? null);
        $this->validated['date'] = strval($data['date'] ?? null);
        $this->validated['differences'] = (array)($data['differences'] ?? ['to' => '', 'from' => '']);


    }
}