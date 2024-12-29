<?php

namespace App\Filament\Resources;

use App\Enums\ActivateStatusBoolEnum;
use App\Enums\ActivateStatusStrEnum;
use App\Filament\Resources\BankResource\Pages;
use App\Filament\Resources\BankResource\RelationManagers;
use App\Models\Bank;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static ?string $navigationIcon = 'heroicon-o-library';
    protected static ?string $label = 'طرق التحويل';
    protected static ?string $navigationLabel = 'طرق التحويل';
    protected static ?string $pluralLabel = 'طرق التحويل';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('طرق الدفع')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->enableOpen()
                        ->imageCropAspectRatio('1:1')->label('صورة وسيلة الدفع'),
                    Forms\Components\TextInput::make('name')->label('اسم وسيلة الدفع')->required(),
                    Forms\Components\TextInput::make('iban')->label('رقم الحفظة / الحساب'),
                    Forms\Components\Textarea::make('info')->label('شرح طريقة التحويل'),
                    Forms\Components\Toggle::make('is_active')->label('الحالة')

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('طريقة التحويل'),
                Tables\Columns\TextColumn::make('is_active')->formatStateUsing(fn($record)=>ActivateStatusBoolEnum::tryFrom($record->is_active)?->status())->color(fn($record)=>ActivateStatusBoolEnum::tryFrom($record->is_active)?->color())->label('الحالة'),
                ])->reorderable('sort')->defaultSort('sort')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button(),
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
            'index' => Pages\ListBanks::route('/'),
            'create' => Pages\CreateBank::route('/create'),
            'edit' => Pages\EditBank::route('/{record}/edit'),
        ];
    }
}
