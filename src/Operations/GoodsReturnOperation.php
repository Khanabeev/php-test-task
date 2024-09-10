<?php

namespace App\Operations;

use App\Exceptions\ValidationException;
use App\Operations\Managers\GoodsReturnOperationManager;
use App\Operations\Notifications\GoodsReturnComplaintClientEmail;
use App\Operations\Notifications\GoodsReturnComplaintEmployeeEmail;
use App\Operations\Notifications\GoodsReturnStatusChangeClientSms;
use App\Operations\Validators\GoodsReturnValidator;

class GoodsReturnOperation extends ReferencesOperation
{
    /**
     * @throws ValidationException|\Exception
     */
    public function doOperation(): array
    {
        $data = (array)$this->getRequest('data');

        $validator = GoodsReturnValidator::init($data);
        $validator->validate();

        $validatedData = $validator->getValidated();
        $manager = new GoodsReturnOperationManager($validatedData);
        $manager->attach(new GoodsReturnComplaintEmployeeEmail());
        $manager->attach(new GoodsReturnComplaintClientEmail());
        $manager->attach(new GoodsReturnStatusChangeClientSms());
        $manager->notify();
        $result = $manager->getResult();

        return $result->toArray();
    }
}