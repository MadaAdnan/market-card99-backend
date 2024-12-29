<?php

namespace App\Filament\Resources;

use App\Enums\ActivateStatusBoolEnum;
use App\Enums\ActivateStatusStrEnum;
use App\Filament\Resources\AskResource\Pages;
use App\Filament\Resources\AskResource\RelationManagers;
use App\Models\Ask;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AskResource extends Resource
{
    protected static ?string $model = Ask::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $label = 'الأسئلة';
    protected static ?string $navigationLabel = 'الأسئلة';
    protected static ?string $pluralLabel = 'الأسئلة';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الأسئلة')->schema([
                    Forms\Components\TextInput::make('ask')->label('السؤال')->required(),
                    Forms\Components\Radio::make('is_active')->options([
                        'active'=>'مفعل',
                        'inactive'=>'غير مفعل'
                    ])->label('حالة السؤال')->default('active')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ask')->label('السؤال'),
                Tables\Columns\TextColumn::make('is_active')->formatStateUsing(fn($record)=>ActivateStatusStrEnum::tryFrom($record->is_active)?->status())->color(fn($record)=>ActivateStatusStrEnum::tryFrom($record->is_active)?->color())->label('الحالة'),
            ])
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
            'index' => Pages\ListAsks::route('/'),
            'create' => Pages\CreateAsk::route('/create'),
            'edit' => Pages\EditAsk::route('/{record}/edit'),
        ];
    }
}
