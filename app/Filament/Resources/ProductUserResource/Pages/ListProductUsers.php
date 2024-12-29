<?php

namespace App\Filament\Resources\ProductUserResource\Pages;

use App\Filament\Resources\ProductUserResource;
use App\Models\ProductUser;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductUsers extends ListRecords
{
    protected static string $resource = ProductUserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];

    }
}
