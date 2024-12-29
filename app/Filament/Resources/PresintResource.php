<?php

namespace App\Filament\Resources;

use App\Enums\ActivateStatusBoolEnum;
use App\Filament\Resources\PresintResource\Pages;
use App\Filament\Resources\PresintResource\RelationManagers;
use App\Models\Ask;
use App\Models\Presint;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PresintResource extends Resource
{
    protected static ?string $model = Presint::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $label = 'طلبات الوكالة';
    protected static ?string $navigationLabel = 'طلبات الوكالة';
    protected static ?string $pluralLabel = 'طلبات الوكالة';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('طلب وكالة')->schema([
                    Forms\Components\Select::make('user_id')->relationship('user', 'name')->label('المستخدم'),
                    Forms\Components\Fieldset::make('إجابات الأسئلة')->schema([
                        Forms\Components\Repeater::make('asks')->relationship('asks')->schema([
                            Forms\Components\Select::make('ask_id')->options(Ask::pluck('ask', 'id'))->label('السؤال'),
                            Forms\Components\TextInput::make('answer')->label('الإجابة')
                        ])->columns(2),
                    ])->columns(1)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('العنوان'),
                Tables\Columns\TextColumn::make('user.name')->label('المستخدم'),
                Tables\Columns\TextColumn::make('status')->formatStateUsing(fn($record) => ActivateStatusBoolEnum::tryFrom($record->status)->status())->color(fn($record) => ActivateStatusBoolEnum::tryFrom($record->status)->color())->label('المستخدم'),
                Tables\Columns\TextColumn::make('info')
                   ->label('ملاحظات'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button(),
                Tables\Actions\Action::make('success')->form([
                    Forms\Components\TextInput::make('info')->label('ملاحظات')->required()
                ])
                    ->action(function ($livewire, $data) {
                        $record = Presint::find($livewire->mountedTableActionRecord);
                        $record->update(['info' => $data['info'], 'status' => 0]);
                        Notification::make('success')->success()->title('نجاح')->body('تم قبول الطلب بنجاح')->send();
                    })->color('success')->button()->requiresConfirmation()->label('قبول الطلب'),
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
            'index' => Pages\ListPresints::route('/'),
//            'create' => Pages\CreatePresint::route('/create'),
//            'edit' => Pages\EditPresint::route('/{record}/edit'),
        ];
    }
}
