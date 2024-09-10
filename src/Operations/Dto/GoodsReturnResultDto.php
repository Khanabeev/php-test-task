<?php

namespace App\Operations\Dto;

use App\Dto\DataTransferObject;
use App\Dto\MessageResultDto;

class GoodsReturnResultDto extends DataTransferObject
{
    public function __construct(
        public ?MessageResultDto $employeeMailNotification = null,
        public ?MessageResultDto $clientMailNotification = null,
        public ?MessageResultDto $clientSmsNotification = null
    )
    {}
}