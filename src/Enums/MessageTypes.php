<?php

namespace App\Enums;

enum MessageTypes: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
}
