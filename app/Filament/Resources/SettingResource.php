<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $label = 'الإعدادات';
    protected static ?string $navigationLabel = 'الإعدادات';
    protected static ?string $pluralLabel = 'الإعدادات';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)->schema([
                    Forms\Components\Card::make([
                        Forms\Components\Wizard::make([
                            Forms\Components\Wizard\Step::make('معلومات الموقع')->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->label('لوغو الموقع'),
                                Forms\Components\TextInput::make('name')->label('اسم الموقع')->required(),
                                Forms\Components\TextInput::make('email')->label('بريد الموقع')->email()->required(),
//                                Forms\Components\TextInput::make('phone')->label('رقم الواتسآب')->required(),
                                Forms\Components\Textarea::make('news')->label('الاخبار'),
                                Forms\Components\TextInput::make('whats_activate')->label('رقم موظف تفعيل الواتس'),
                                Forms\Components\Textarea::make('info_present')->label('تعليمات طلب الوكالة'),
                                Forms\Components\Toggle::make('is_notify')->label('حالة إشعارات تغيرات المنتج'),
                            ]),
                            Forms\Components\Wizard\Step::make('معلومات Api')->schema([
//                                Forms\Components\TextInput::make('api_sim90')->label('API SIM90'),
                                Forms\Components\TextInput::make('apis.speed_card')->label('API SPEED-CARD'),
                                Forms\Components\TextInput::make('apis.eko')->label('API EKO'),
                                Forms\Components\TextInput::make('apis.life')->label('API LifeCash'),
                                Forms\Components\TextInput::make('apis.drd3')->label('API Drdm3'),
                                Forms\Components\TextInput::make('apis.cash-mm')->label('API CashSmm'),
                                Forms\Components\TextInput::make('apis.saud')->label('ابو السعود API'),
                                Forms\Components\TextInput::make('apis.as7ab')->label('أصحاب API'),
                                Forms\Components\TextInput::make('apis.mazaya')->label('مزايا API'),
                                Forms\Components\TextInput::make('apis.cache-back')->label('كاش باك API'),
                            ]),

                            Forms\Components\Wizard\Step::make('نسب الربح والحسم')->schema([
                                Forms\Components\TextInput::make('usd_price')->label('سعر الدولار')->numeric()->required(),
//                                Forms\Components\TextInput::make('win_sim90_ratio')->label('نسبة الربح من Sim90')->numeric()->required(),
                                /*Forms\Components\Radio::make('is_active_sim90')->options([
                                    'active' => 'مفعل',
                                    'inactive' => 'غير مفعل',
                                ])->required()->inline()->label('حالة Sim90'),*/

                                Forms\Components\TextInput::make('discount_online')->label('نسبة حسم الأرقام لل Api')->numeric()->required(),
                                Forms\Components\TextInput::make('discount_delegate_online')->label('نسبة حسم الوكلاء من Online')->numeric()->required(),
                                Forms\Components\TextInput::make('fixed_ratio')->label('نسبة ربح الوكلاء المخفيين')->numeric()->required(),


                            ]),
                            Forms\Components\Wizard\Step::make('التواصل')->schema([

                                Forms\Components\TextInput::make('social.whatsapp')->label('رقم واتسآب'),
                                Forms\Components\TextInput::make('social.telegram')->url()->label('تلغرام'),
                                Forms\Components\TextInput::make('social.facebook')->url()->label('فيس بوك'),
                                Forms\Components\TextInput::make('social.instagram')->url()->label('انستغرام'),
                            ]),
                            Forms\Components\Wizard\Step::make('البيع بالعمولة')->schema([

                                Forms\Components\Toggle::make('is_affiliate')->label('تفعيل البيع بالعمولة'),
                                Forms\Components\TextInput::make('affiliate_ratio')->numeric()->step(0.001)->label('نسبة الربح من البيع بالعمولة')->required()
                            ]),

                            Forms\Components\Wizard\Step::make('Widgets')->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('widget_img1')->collection('widget1')->label('صورة 1')->imageCropAspectRatio('1:1')->conversion('webp'),
                                Forms\Components\Textarea::make('widget1')->label('الويدجت الأولى'),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('widget_img2')->collection('widget2')->label('صورة 2')->imageCropAspectRatio('1:1')->conversion('webp'),
                                Forms\Components\Textarea::make('widget2')->label('الويدجت الثانية'),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('widget_img3')->collection('widget3')->label('صورة 3')->imageCropAspectRatio('1:1')->conversion('webp'),
                                Forms\Components\Textarea::make('widget3')->label('الويدجت الثالثة'),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('widget_img4')->collection('widget4')->label('صورة 4')->imageCropAspectRatio('1:1')->conversion('webp'),
                                Forms\Components\Textarea::make('widget4')->label('الويدجت الرابعة'),
                                Forms\Components\Textarea::make('about')->label('عن الموقع'),
                            ]),

                            Forms\Components\Wizard\Step::make('إغلاق الموقع')->schema([
                                Forms\Components\Toggle::make('is_open')->label('فتح الموقع')->reactive(),
                                Forms\Components\Textarea::make('msg_close')->label('رسالة إغلاق الموقع')->visible(fn($get) => !$get('is_open'))
                            ])

                        ])->skippable(),
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('اسم الموقع'),
                Tables\Columns\TextColumn::make('email')->label('بريد الموقع'),
                Tables\Columns\TextColumn::make('news')->label('الاخبار'),
                Tables\Columns\TextColumn::make('usd_price')->label('سعر الدولار'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            //  'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
