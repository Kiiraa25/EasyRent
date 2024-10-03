<?php

namespace App\Enum;

enum PaymentMethodEnum: string
{
    case CARTE_BANCAIRE = 'carte_bancaire';
    case PAYPAL = 'paypal';
}