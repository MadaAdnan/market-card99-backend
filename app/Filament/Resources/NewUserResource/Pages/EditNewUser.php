<?php

namespace App\Filament\Resources\NewUserResource\Pages;

use App\Filament\Resources\NewUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewUser extends EditRecord
{
    protected static string $resource = NewUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
