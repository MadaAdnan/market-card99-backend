<?php

namespace App\Filament\Resources\PresintResource\Pages;

use App\Filament\Resources\PresintResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPresints extends ListRecords
{
    protected static string $resource = PresintResource::class;

    protected function getActions(): array
    {
        return [
//            Actions\CreateAction::make(),
        ];
    }
}
