<?php

namespace App\Filament\Resources\ChargeResource\Pages;

use App\Filament\Resources\ChargeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCharge extends EditRecord
{
    protected static string $resource = ChargeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
