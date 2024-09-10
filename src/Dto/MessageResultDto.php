<?php

namespace App\Dto;

class MessageResultDto extends DataTransferObject
{
    public function __construct(
        public bool $sent = false,
        public bool $success = false,
        public string $message = '',
    )
    {}
}