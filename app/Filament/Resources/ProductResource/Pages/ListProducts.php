<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Jobs\OneSignalAllUserJob;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Livewire\Features\Placeholder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('send_notify')
                ->form([
                    TextInput::make('body')->label('الرسالة')->required(),
                    \Filament\Forms\Components\Placeholder::make('text')->content('تم تعديل سعر المنتج []  يرجى الإطلاع عللى السعر الجديد'),
                    \Filament\Forms\Components\Placeholder::make('text-qty')->content('المنتج []  غير متوفر حاليا سيتم توفره في أقرب وقت')
                ])
                ->action(function ($data) {
                    $job = new OneSignalAllUserJob($data);
                    dispatch($job);
                })->label('إرسال جماعي')
        ];
    }
}
