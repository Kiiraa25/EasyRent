<?php

namespace App\Enum;

enum VehicleStatusEnum: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case ARCHIVED = 'archived';
}

