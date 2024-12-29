<?php

namespace App\Filament\Resources\BlackResource\Pages;

use App\Filament\Resources\BlackResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlack extends EditRecord
{
    protected static string $resource = BlackResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
