<?php

namespace App\Filament\Resources\AskResource\Pages;

use App\Filament\Resources\AskResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsks extends ListRecords
{
    protected static string $resource = AskResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
