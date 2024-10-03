<?php

namespace App\Enum;

enum CancelledByEnum: string
{
    case OWNER = 'owner';
    case RENTER = 'renter';
}