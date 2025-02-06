<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Jobs\OneSignalJob;
use App\Jobs\SendNotificationJob;
use App\Models\Balance;
use App\Models\Group;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\SendNotificationDB;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $label = 'المستخدمين';
    protected static ?string $navigationLabel = 'المستخدمين';
    protected static ?string $pluralLabel = 'المستخدمين';
    protected static ?string $navigationGroup = 'أساسي';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المستخدمين')->schema([
                    Forms\Components\TextInput::make('name')->label('الاسم')->required(),
                    Forms\Components\TextInput::make('username')->label('اسم المستخدم')->unique(ignoreRecord: true)->required(),
                    Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email()->unique(ignoreRecord: true)->required(),
                    Forms\Components\TextInput::make('password')->required(fn($context) => $context == 'create')->label('كلمة المرور')->dehydrated(fn($state) => filled($state))->dehydrateStateUsing(fn($state) => bcrypt($state))->password(),
                    Forms\Components\TextInput::make('phone')->label('رقم الهاتف')->required(),
                    Forms\Components\TextInput::make('address')->label('العنوان')->required(),
//                    Forms\Components\TextInput::make('ratio')->label('نسبة الربح')->required()->default(0)->numeric()->minValue(0)->visible(auth()->user()->hasRole('partner')),
//                    Forms\Components\TextInput::make('ratio_online')->label('نسبة الربح من Online')->required()->default(0)->numeric()->minValue(0)->visible(auth()->user()->hasRole('partner')),
                    Forms\Components\Toggle::make('active')->label('الحالة')->visible(auth()->user()->hasRole('super_admin')),
                    Forms\Components\Select::make('user_id')->options(User::whereHas('roles', fn($q) => $q->where('name', 'partner'))->pluck('name', 'id')->toArray())->searchable()->label('الوكيل')->visible(auth()->user()->hasRole('super_admin')),
                    Forms\Components\Select::make('group_id')->options(Group::selectRaw('id,price,sort,concat(name," (",ratio_delegate*100,"% )") as name')->orderBy('price')->when(!auth()->user()->hasRole('super_admin'),
                        fn($q) => $q->where('sort', '<', auth()->user()->group?->sort))->pluck('name', 'id')->toArray())->searchable()->label('الفئة'),
                    Forms\Components\CheckboxList::make('roles')->relationship('roles', 'name')->label('الصلاحيات')->visible(auth()->user()->hasRole('super_admin')),
//                    Forms\Components\Toggle::make('is_show')->label('وكيل معتمد')->visible(auth()->user()->hasRole('super_admin')),
                    Forms\Components\Toggle::make('is_affiliate')->label('تفعيل الربح من الإحالة')->visible(auth()->user()->hasRole('super_admin')),
                    Forms\Components\Toggle::make('is_fixed_group')->label('تثبيت الفئة')->visible(auth()->user()->hasRole('super_admin')),
                    Forms\Components\Toggle::make('is_active_hook')->label('تفعيل الإشعار للموقع المربوط')->reactive()->visible(auth()->user()->hasRole('super_admin')),
                    Forms\Components\Toggle::make('is_branch')->label('تفعيل كفرع')->visible(auth()->user()->hasRole('super_admin')),
                    Forms\Components\TextInput::make('hook_api')->label('رابط Api  الموقع المربوط')->url()->visible(fn($get)=>auth()->user()->hasRole('super_admin')&& $get('is_active_hook')),
                    Forms\Components\TextInput::make('order_hook')->label('رابط Api  لتعديل حالة الطلب')->url()->visible(fn($get)=>auth()->user()->hasRole('super_admin')&& $get('is_active_hook')),
                    Forms\Components\Toggle::make('is_check_name')->label('تفعيل الفحص')->visible(fn($get)=>auth()->user()->hasRole('super_admin')),
                    Forms\Components\DatePicker::make('expired_date')->label('نهاية الإشتراك')->visible(fn($get)=>auth()->user()->hasRole('super_admin'))
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->disableClick()->label('#'),
                Tables\Columns\TextColumn::make('name')->disableClick()->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('email')->disableClick()->label('البريد الإلكتروني')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('رقم الهاتف')->url(fn($record) => 'https://wa.me/' . ltrim(ltrim($record->phone, '+'), '00'), true),
                Tables\Columns\TextColumn::make('group.name')->disableClick()->label('الفئة'),
                Tables\Columns\TextColumn::make('balance')->disableClick()->label('الرصيد')->color('danger'),
                Tables\Columns\TextColumn::make('points')->disableClick()->formatStateUsing(fn($record) => number_format($record->getTotalPoint(), 2))->label('النقاط')->color('success')->visible(auth()->user()->hasRole('super_admin')),
                Tables\Columns\TextColumn::make('user.name')->disableClick()->label('الوكيل'),
                Tables\Columns\TextColumn::make('roles')->disableClick()->formatStateUsing(fn($record) => implode(',', $record->roles->pluck('name')->toArray()))->label('الصلاحيات'),
//                Tables\Columns\TextColumn::make('affiliate')->disableClick()->label('رابط الإحالة'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->options(User::whereHas('roles', fn($q) => $q->where('roles.name', 'partner'))->pluck('name', 'users.id')->toArray())->searchable()->label('الوكيل')->visible(auth()->user()->hasRole('super_admin')),
                Tables\Filters\SelectFilter::make('group_id')->relationship('group', 'name')->label('الفئة')->visible(auth()->user()->hasRole('super_admin')),
                Tables\Filters\SelectFilter::make('roles')->relationship('roles', 'name')->searchable()->label('الصلاحية')->visible(auth()->user()->hasRole('super_admin')),
            ])
            ->actions([

                Tables\Actions\ActionGroup::make([

                    Tables\Actions\Action::make('balance_w')->label('إضافة رصيد')->form([
                        Forms\Components\Placeholder::make('name')->label(fn($record) => 'اسم المستخدم : ' . $record->name),
                        Forms\Components\TextInput::make('value')->label('القيمة')->numeric()->required(),
                        Forms\Components\TextInput::make('info')->label('الملاحظات'),
                        Forms\Components\TextInput::make('token')->label('كلمة المرور')->required()->visible(auth()->user()->hasRole('super_admin')),
                    ])
                        ->action(function ($livewire, $data) {
                            $record = User::find($livewire->mountedTableActionRecord);

                            if (!$record) {
                                Notification::make('error')->danger()->title('خطأ')->body('لم يتم التعرف على المستخدم')->send();
                                return;
                            }
                            if ($data['value'] <= 0) {
                                Notification::make('error')->danger()->title('خطأ')->body('يرجى إدخال قيمة صالحة')->send();

                                return;
                            }
                            $setting = Setting::first();
                            if (auth()->user()->hasRole('super_admin')) {
                                if (Hash::check($data['token'], $setting->token_balance)) {
                                    Balance::create([
                                        'user_id' => $record->id,
                                        'credit' => $data['value'],
                                        'debit' => 0,
                                        'info' => $data['info'] . ' شحن عن طريق المدير ',
                                        'total' => $record->balance + $data['value'],
                                        'ratio' => 0,
                                    ]);
                                    Notification::make('success')->success()->title('نجاح')->body('تم شحن الرصيد بنجاح')->send();
                                } else {
                                    Notification::make('error')->danger()->title('خطأ')->body('خطأ في كلمة المرور')->send();
                                    return;
                                }


                            } elseif (auth()->user()->balance > $data['value']) {
                                \DB::beginTransaction();
                                try {
                                    Balance::create([
                                        'user_id' => auth()->id(),
                                        'debit' => $data['value'],
                                        'credit' => 0,
                                        'info' => $data['info'] . ' تحويل إلى ' . $record->name,
                                        'total' => auth()->user()->balance - $data['value'],
                                        'ratio' => 0,
                                    ]);
                                    Balance::create([
                                        'user_id' => $record->id,
                                        'credit' => $data['value'],
                                        'debit' => 0,
                                        'info' => $data['info'] . ' تحويل من ' . auth()->user()->name,
                                        'total' => $record->balance + $data['value'],
                                        'ratio' => 0,
                                    ]);
                                    \DB::commit();
                                    Notification::make('success')->success()->title('نجاح')->body('تم شحن الرصيد بنجاح')->send();
                                } catch (\Exception | \Error $e) {
                                    \DB::rollBack();
                                    Notification::make('error')->danger()->title('خطأ')->body($e->getMessage())->send();

                                }
                            } else {
                                Notification::make('error')->danger()->title('خطأ')->body('للأسف لا تملك رصيد كافي')->send();
                                return;
                            }
                        }),
                    Tables\Actions\Action::make('balance_min')->label('سحب رصيد')->form([
                        Forms\Components\TextInput::make('value')->label('القيمة')->numeric()->required(),
                        Forms\Components\TextInput::make('info')->label('الملاحظات'),
                        Forms\Components\TextInput::make('token')->label('كلمة المرور')->required()->visible(auth()->user()->hasRole('super_admin')),
                    ])
                        ->action(function ($livewire, $data) {
                            $record = User::find($livewire->mountedTableActionRecord);
                            if (!$record) {
                                Notification::make('error')->danger()->title('خطأ')->body('لم يتم التعرف على المستخدم')->send();
                                return;
                            }
                            if ($data['value'] <= 0) {
                                Notification::make('error')->danger()->title('خطأ')->body('يرجى إدخال قيمة صالحة')->send();

                                return;
                            }
                            $setting = Setting::first();
                            if (auth()->user()->hasRole('super_admin') && Hash::check($data['token'], $setting->token_balance)) {
                                Balance::create([
                                    'user_id' => $record->id,
                                    'debit' => $data['value'],
                                    'credit' => 0,
                                    'info' => $data['info'] . ' سحب عن طريق المدير ',
                                    'total' => $record->balance - $data['value'],
                                    'ratio' => 0,
                                ]);

                                Notification::make('success')->success()->title('نجاح')->body('تم السحب من الرصيد بنجاح')->send();

                            }
                        })->visible(auth()->user()->hasRole('super_admin')),

                    Tables\Actions\Action::make('reset-hash')->form([
                        Forms\Components\TextInput::make('hash')->label('كلمة تأكيد الشراء')->required(),
                    ])->action(function($data,$record){
                        $record->update(['hash'=>$data['hash']]);
                        Notification::make('success')->success()->body('تم تغيير كلمة مرور الشراء بنجاح')->send();
                    })->visible(auth()->user()->hasRole('super_admin'))->label('تغيير كلمة مرور الشراء')
                ]),
                Tables\Actions\Action::make('token')->button()
                    ->visible(auth()->user()->hasRole('super_admin'))
                    ->action(function ($record) {
                        /**
                         * @var $record User
                         */
                        $record->tokens()->delete();
                        $token = $record->createToken('user')->plainTextToken;
                        $record->update([
                            'token' => $token
                        ]);
                        Notification::make('success')->success()->title('نجاح')->body('تم تسجيل الخروج من جميع الأجهزة')->send();

                    })->label('تسجيل الخروج')->requiresConfirmation(),
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button(),
                Tables\Actions\Action::make('notifications')->label('إرسال إشعار')->form([
                    Forms\Components\FileUpload::make('img')->disk('public')->directory('images')->image(),
                    Forms\Components\TextInput::make('title')->label('عنوان الرسالة')->required(),
                    Forms\Components\Textarea::make('body')->label(' الرسالة')->required(),
                    Forms\Components\Toggle::make('admin')->label('تظهر في الرئيسية')
                ])->action(function ($data, $record) {
                    $data['route'] = '';
                    if (!$data['admin']) {
                        unset($data['admin']);
                    }
                    \Notification::send($record, new SendNotificationDB($data));
                    $job = new OneSignalJob($record->email, $data);
                    dispatch($job);
                    Notification::make('success')->success()->body('تم إرسال الإشعارات بنجاح')->send();
                })->button()->color('success')->visible(auth()->user()->hasRole('super_admin')),

            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('notifications-all')->label('إرسال إشعار')->form([
                    Forms\Components\FileUpload::make('img')->disk('public')->directory('images')->image(),
                    Forms\Components\TextInput::make('title')->label('عنوان الرسالة')->required(),
                    Forms\Components\Textarea::make('body')->label(' الرسالة')->required(),
                    Forms\Components\Toggle::make('admin')->label('تظهر في الرئيسية')

                ])->action(function ($data, $records) {
                    $data['route'] = '';
                    if (!$data['admin']) {
                        unset($data['admin']);
                    }
                    \Notification::send($records, new SendNotificationDB($data));
                    foreach ($records as $record){
                        $job = new OneSignalJob($record->email, $data);
                        dispatch($job)->delay(5);
                    }

                    Notification::make('success')->body('تم إرسال الإشعارات بنجاح')->send();
                })->visible(auth()->user()->hasRole('super_admin')),
                Tables\Actions\BulkAction::make('reset-password')->action(function ($records) {
                    User::whereIn('id', $records->pluck('id')->toArray())->update(['force_reset_password' => 1]);
                })->label('إجبار تغيير كلمة المرور')->requiresConfirmation()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BalancesRelationManager::class,
            RelationManagers\PointsRelationManager::class,
            RelationManagers\BillsRelationManager::class,
            RelationManagers\OrdersRelationManager::class,
            RelationManagers\AffiliatesRelationManager::class
        ];
    }

//
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
