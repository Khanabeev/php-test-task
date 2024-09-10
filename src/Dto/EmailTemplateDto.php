<?php

namespace App\Dto;

class EmailTemplateDto extends DataTransferObject
{
    public function __construct(
        public string $subject,
        public string $body,
    )
    {}
}