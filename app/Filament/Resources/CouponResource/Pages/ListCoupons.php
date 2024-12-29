<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use App\Models\Coupon;
use App\Models\Setting;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoupons extends ListRecords
{
    protected static string $resource = CouponResource::class;

    protected function getActions(): array
    {
        return [
//            Actions\CreateAction::make(),
            Actions\Action::make('create')->form([
                TextInput::make('count')->label('عدد الكوبونات')->numeric()->default(1)->required()->minValue(1),
                TextInput::make('price')->label('سعر الكوبون')->numeric()->default(5)->required()->minValue(1),
                TextInput::make('token')->label('كلمة المرور')->required(),
            ])->label('إضافة كوبونات جديدة')->action(function ($data) {
                $setting = Setting::first();
                if (\Hash::check($data['token'], $setting->token_balance)) {
                    for ($i = 0; $i < $data['count']; $i++) {
                        Coupon::firstOrCreate([
                            'code' => \Str::upper(\Str::random(8)),
                        ], [
                            'price' => $data['price'],
                            'status' => true,
                        ]);
                    }
                    Notification::make('success')->success()->title('نجاح العملية')->body('تم توليد الكوبونات')->send();

                } else {
                    Notification::make('error')->danger()->title('خطأ')->body('كلمة المرور خاطئة')->send();

                }
            })
        ];
    }
}
