<?php

namespace App\Operations;

/**
 * @property Seller $Seller
 */
class Contractor
{
    const TYPE_CUSTOMER = 0;
    public $id = null;
    public $type = null;
    public $name = null;
    public $email = null;
    public $mobile = null;
    public ?Seller $Seller = null;

    public static function getById(int $contractorId): ?static
    {
        $contractor = new static($contractorId); // fakes the getById method
        $contractor->id = $contractorId;
        // Just to make it work, so I can test execution without creation a new Model class
        $contractor->email = 'test@example.com';
        $contractor->mobile = '0979898989';
        return $contractor;
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->id;
    }
}

class Seller extends Contractor
{
}

class Employee extends Contractor
{
}

class Status
{
    public $id, $name;

    public static function getName(int $id): string
    {
        $a = [
            0 => 'Completed',
            1 => 'Pending',
            2 => 'Rejected',
        ];

        return $a[$id];
    }
}

abstract class ReferencesOperation
{
    abstract public function doOperation(): array;

    public function getRequest($pName)
    {
        return $_REQUEST[$pName] ?? [];
    }
}

function getResellerEmailFrom($resellerId)
{
    return 'contractor@example.com';
}

function getEmailsByPermit($resellerId, $event)
{
    // fakes the method
    return ['someemeil@example.com', 'someemeil2@example.com'];
}

function __(string $message, ...$data): string {
    /** TODO: process data */
    return $message;
}

class NotificationEvents
{
    const CHANGE_RETURN_STATUS = 'changeReturnStatus';
    const NEW_RETURN_STATUS    = 'newReturnStatus';
}

class NotificationManager
{
    public static function send(
        $resellerId,
        $clientid,
        $event,
        $notificationSubEvent,
        $templateData,
        &$errorText,
        $locale = null
    ) {
        // fakes the method
        return true;
    }
}

class MessagesClient
{
    static function sendMessage(
        $sendMessages,
        $resellerId = 0,
        $customerId = 0,
        $notificationEvent = 0,
        $notificationSubEvent = ''
    ) {
        return '';
    }
}