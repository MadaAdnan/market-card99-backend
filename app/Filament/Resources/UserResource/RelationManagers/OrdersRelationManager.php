<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\OrderStatusEnum;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $recordTitleAttribute = 'id';
protected static ?string $title='مشتريات الأرقام';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('رقم الهاتف')->searchable(),

                Tables\Columns\TextColumn::make('code')->label('الكود')->searchable(),
                Tables\Columns\TextColumn::make('status')->formatStateUsing(fn($record)=>OrderStatusEnum::tryFrom($record->status->value)?->status())->color(fn($record)=>OrderStatusEnum::tryFrom($record->status->value)?->color())->label('الكود'),
                Tables\Columns\TextColumn::make('server.name')->label('السيرفر'),
                Tables\Columns\TextColumn::make('country.name')->label('الدولة'),
                Tables\Columns\TextColumn::make('program.name')->label('التطبيق'),
                Tables\Columns\TextColumn::make('price')->formatStateUsing(fn($record)=>number_format($record->price,2))->label('السعر'),
                Tables\Columns\TextColumn::make('created_at')->since()->label('منذ'),

            ])->defaultSort('created_at','desc')
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
