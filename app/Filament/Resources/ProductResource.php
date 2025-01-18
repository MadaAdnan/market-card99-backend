<?php

namespace App\Filament\Resources;

use App\Enums\ActivateStatusBoolEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Jobs\OneSignalAllUserJob;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Support\Actions\Action;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $label = 'المنتجات';
    protected static ?string $navigationLabel = 'المنتجات';
    protected static ?string $pluralLabel = 'المنتجات';
    protected static ?string $navigationGroup = 'الأقسام والمنتجات';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المنتجات')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->label('صورة المنتج')->image()->imageCropAspectRatio('1:1')->required(),
                    Forms\Components\Select::make('category_id')->options(Category::pluck('name', 'id'))->searchable()->label('القسم')->required(),
                    Forms\Components\TextInput::make('name')->label('اسم المنتجات')->required(),
                    Forms\Components\Textarea::make('info')->label('وصف المنتج'),
                    Forms\Components\Radio::make('currency')->options([
                        'usd' => 'دولار',
                        'tr' => 'ليرة تركية',
                        'syr'=>'ليرة سورية'
                    ])->label('العملة')->inline()->required()->default('usd'),
                    Forms\Components\TextInput::make('cost')->label('سعر التكلفة')->numeric()->required()->step(0.001),

                    Forms\Components\Toggle::make('is_free')->label('طلب حر')->reactive(),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('amount')->label('الكمية')->required()->default(0)->numeric(),
                        Forms\Components\TextInput::make('min_amount')->label('أقل كمية')->required()->default(0)->numeric(),
                        Forms\Components\TextInput::make('max_amount')->label('أقصى كمية')->required()->default(0)->numeric(),
                    ])->visible(fn($get) => $get('is_free')),
                    Forms\Components\Radio::make('type')->options([
                        'default' => 'عادي',
                        'account' => 'يحتاج معلومات حساب',
                        'id' => 'يحتاج ID',
                        'phone' => 'رقم هاتف',
                        'url' => 'رابط ',
                    ])->label('نوع المنتج')->inline()->required()->default('id'),
//                    Forms\Components\Toggle::make('is_url')->label('رابط صفحة'),
                    Forms\Components\Toggle::make('is_active_api')->label('حالة الطلب من api')->reactive(),
                    Forms\Components\Fieldset::make('معلومات Api')->schema([
                        Forms\Components\TextInput::make('count')->label('كمية الطلب من API')->required(),
                        Forms\Components\Radio::make('api')->options([
                            'life-cash' => 'لايف كاش',
                            'juneed' => 'جنيد',
                            'speed-card' => 'سبيد كارد',
                            'eko' => 'إيكو',
                            'drd3' => 'DRD3',
                            'cash-mm' => 'CashSmm',
                            'saud' => 'ابو سعود',
                            'as7ab' => 'أصحاب',
                            'mazaya' => 'مزايا',
                            'cache-back'=>'كاش باك',
                        ])->label('حدد الموقع')->required()->inline(),
                        Forms\Components\TextInput::make('code')->label('كود لايف كاش'),
                        Forms\Components\TextInput::make('codes_api.eko')->label('كود إيكو'),
                        Forms\Components\TextInput::make('codes_api.speed_card')->label('كود سبيد كارد'),
                        Forms\Components\TextInput::make('codes_api.drd3')->label('كود DRD3'),
                        Forms\Components\TextInput::make('codes_api.cash-mm')->label('كود CashSmm'),
                        Forms\Components\TextInput::make('codes_api.saud')->label('كود السعود'),
                        Forms\Components\TextInput::make('codes_api.as7ab')->label('كود أصحاب'),
                        Forms\Components\TextInput::make('codes_api.mazaya')->label('كود مزايا'),
                        Forms\Components\TextInput::make('codes_api.cache-back')->label('كود كاش باك'),
                        Forms\Components\TextInput::make('codes_api.juneed')->label('كود جنيد'),
                    ])->columns(1)->visible(fn($get) => $get('is_active_api') == true),
                    Forms\Components\Toggle::make('is_discount')->label('حالة العرض'),
                    Forms\Components\Toggle::make('is_offer')->label('البيع بسعر التكلفة'),
                    Forms\Components\Toggle::make('is_available')->label('حالة التوفر'),
                    Forms\Components\Toggle::make('is_notify')->label('إرسال إشعار عند تغيير السعر أو حالة التوفر'),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort', 'asc')
            ->reorderable('sort')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('webp')->label('الصورة')->circular(),
                Tables\Columns\TextColumn::make('name')->label('اسم المنتج')->searchable()->disableClick(),
                Tables\Columns\TextColumn::make('items_count')->label('عدد الأكواد')->sortable()->disableClick(),
                Tables\Columns\TextColumn::make('category.name')->label('القسم')->sortable()->disableClick(),
                Tables\Columns\BadgeColumn::make('type')->enum([
                    'default' => 'عادي',
                    'account' => 'يحتاج معلومات حساب',
                    'id' => 'يحتاج ID',
                    'phone' => 'رقم هاتف',
                    'url' => 'رابط ',
                ])->label('نوع المنتج')->sortable()->disableClick(),
                Tables\Columns\BadgeColumn::make('currency')->enum([
                    'usd' => 'دولار',
                    'tr' => 'ليرة تركية',
                    'syr'=>'ليرة سورية'
                ])->label('العملة')->disableClick(),
                Tables\Columns\TextColumn::make('cost')->label('سعر التكلفة')->disableClick(),
                Tables\Columns\ToggleColumn::make('active')->label('الحالة')->disableClick(),
//                Tables\Columns\ToggleColumn::make('is_available')->label('متوفر')->disableClick()-,

                Tables\Columns\TextColumn::make('api')->label('Api')->disableClick()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')->options(Category::pluck('name', 'id'))->searchable()->label('القسم'),

                Tables\Filters\SelectFilter::make('api')->options([
                    'life-cash' => 'لايف كاش',
                    'juneed' => 'جنيد',
                    'speed-card' => 'سبيد كارد',
                    'eko' => 'إيكو',
                    'drd3' => 'DRD3',
                    'cash-mm' => 'CashSmm',
                    'saud' => 'ابو سعود',
                    'as7ab' => 'أصحاب',
                    'mazaya' => 'مزايا',
                    'cache-back'=>'كاش باك'
                ])->label('حدد الموقع'),
                Tables\Filters\SelectFilter::make('is_available')->label('حالة المنتجات')->options([
                    ActivateStatusBoolEnum::ACTIVE->value => 'متوفر',
                    ActivateStatusBoolEnum::INACTIVE->value => 'غير متوفر',
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button(),
//                Tables\Actions\Action::make('unavailable')->label('تبديل إلى غير متوفر')->action(fn($record) => $record->update(['is_available' => false]))->visible(fn($record) => $record->is_available == true)->requiresConfirmation()->button()->color('warning'),
                /*    Tables\Actions\ActionGroup::make([
                        Tables\Actions\Action::make('custom-edit')->form(function ($record) {
                            return [
                                Forms\Components\Toggle::make('is_price')->label('تعديل السعر')->reactive(),
                                Forms\Components\Toggle::make('is_qty')->label('تعديل الكمية')->reactive(),
                                Forms\Components\Toggle::make('is_available_change')->label('تعديل الحالة')->reactive(),
                                Forms\Components\Fieldset::make('تعديل السعر')->schema([
                                    Forms\Components\TextInput::make('cost')->label('السعر')->required()->numeric()->default($record->cost),

                                ])->visible(fn($get) => $get('is_price')),
                                Forms\Components\Fieldset::make('تعديل الكمية')->schema([
                                    Forms\Components\TextInput::make('amount')->label('الكمية')->required()->numeric()->default($record->amount),

                                ])->visible(fn($get) => $get('is_qty')),
                                Forms\Components\Fieldset::make('تعديل حالة التوفر')->schema([
                                    Forms\Components\Toggle::make('is_available')->label('حالة التوفر')->default($record->is_available),

                                ])->visible(fn($get) => $get('is_available_change')),
                                Forms\Components\Toggle::make('send_notifications')->label('إرسال إشعار')
                            ];

                        })->action(function($record,$data){
                            $is_update_cost=false;
                            $is_update_qty=false;
                            if(isset($data['cost']) ){
                                $is_update_cost=$record->cost!=$data['cost'];
                                $record->update([
                                    'cost'=>$data['cost'],
                                ]);

                            }

                            if(isset($data['amount'])){
                                $is_update_qty=$record->amount!=$data['amount'];
                                $record->update([
                                    'amount'=>$data['amount'],
                                ]);

                            }
                            if($is_update_qty||$is_update_cost){
                                if($data['send_notifications']){
                                    $arr['route'] = '';
                                    $arr['title'] = 'تعديل سعر';
                                    $arr['img']=$record->getImage();
                                    $arr['body'] = 'تم تحديث سعر المنتج ' . $record->name . 'يرجى الإطلاع على السعر الجديد';
                                    $job = new OneSignalAllUserJob( $arr);
                                    dispatch($job);
                                }
                            }

                            if(isset($data['is_available'])){
                                $is_update=$record->is_available!=$data['is_available'];
                                $record->update([
                                    'is_available'=>$data['is_available'],
                                ]);
                                if($data['send_notifications'] && $is_update){
                                    $arr['route'] = '';
                                    $arr['title'] = 'تعديل الحالة';
                                    $arr['img']=$record->getImage();
                                    if ($is_update&& $record->is_available) {
                                        $arr['body'] = 'أصبح المنتج ' . $record->name . ' مـتـ✓ـوفر الآن يمكنك الشراء';
                                    } else {
                                        $arr['body'] = 'للأسف أصبح المنتج ' . $record->name . ' غير متـ✘ــوفر الآن سيتم توفره في أسرع وقت';
                                    }
                                    // \Notification::send($users, new SendNotificationDB($data));
                                    $job = new OneSignalAllUserJob( $arr);
                                    dispatch($job);
                                }

                            }
                            Notification::make('success')->body('تم التعديل بنجاح')->success()->send();
                        })->button()
                            ->label('تعديل السعر/ الحالة'),
                        Tables\Actions\Action::make('available')->label('تبديل إلى متوفر')->action(fn($record) => $record->update(['is_available' => true]))->visible(fn($record) => $record->is_available == false)->requiresConfirmation()->button()->color('success'),

                    ]),*/

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
