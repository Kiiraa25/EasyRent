<?php

namespace App\Enum;

enum VehicleStatusEnum: string
{
    case WAITING_FOR_VALIDATION = 'en_attente_validation';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case ARCHIVED = 'archived';
}

