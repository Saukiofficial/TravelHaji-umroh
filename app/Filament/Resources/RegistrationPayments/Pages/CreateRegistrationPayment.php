<?php

namespace App\Filament\Resources\RegistrationPayments\Pages;

use App\Filament\Resources\RegistrationPayments\RegistrationPaymentResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateRegistrationPayment extends CreateRecord
{
    protected static string $resource = RegistrationPaymentResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}