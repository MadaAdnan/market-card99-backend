<?php

namespace App\Filament\Resources\NewUserResource\Pages;

use App\Filament\Resources\NewUserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewUser extends CreateRecord
{
    protected static string $resource = NewUserResource::class;
}
