<?php

namespace App\Filament\Resources\RegistrationPayments;

use App\Filament\Resources\RegistrationPayments\Pages\CreateRegistrationPayment;
use App\Filament\Resources\RegistrationPayments\Pages\EditRegistrationPayment;
use App\Filament\Resources\RegistrationPayments\Pages\ListRegistrationPayments;
use App\Filament\Resources\RegistrationPayments\Pages\ViewRegistrationPayment;
use App\Filament\Resources\RegistrationPayments\Schemas\RegistrationPaymentForm;
use App\Filament\Resources\RegistrationPayments\Tables\RegistrationPaymentsTable;
use App\Models\RegistrationPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class RegistrationPaymentResource extends Resource
{
    protected static ?string $model = RegistrationPayment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan Jamaah';

    protected static ?string $navigationLabel = 'Pembayaran Jamaah';

    protected static ?string $modelLabel = 'Pembayaran Jamaah';

    protected static ?string $pluralModelLabel = 'Pembayaran Jamaah';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return RegistrationPaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationPaymentsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'registration',
                'registration.package',
                'registration.payments',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegistrationPayments::route('/'),
            'create' => CreateRegistrationPayment::route('/create'),
            'view' => ViewRegistrationPayment::route('/{record}'),
            'edit' => EditRegistrationPayment::route('/{record}/edit'),
        ];
    }
}