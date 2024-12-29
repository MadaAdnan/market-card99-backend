<?php

namespace App\Filament\Resources\BalanceResource\Pages;

use App\Filament\Resources\BalanceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBalance extends EditRecord
{
    protected static string $resource = BalanceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
