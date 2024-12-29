<?php

namespace App\Filament\Resources\BlackResource\Pages;

use App\Filament\Resources\BlackResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlacks extends ListRecords
{
    protected static string $resource = BlackResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
