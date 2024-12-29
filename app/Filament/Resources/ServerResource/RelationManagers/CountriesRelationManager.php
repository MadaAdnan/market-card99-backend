<?php

namespace App\Filament\Resources\ServerResource\RelationManagers;

use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountriesRelationManager extends RelationManager
{
    protected static string $relationship = 'countries';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الدول')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->label('الصورة'),
                    Forms\Components\TextInput::make('name')->label('اسم الدولة')->required(),
                    Forms\Components\TextInput::make('code')->label('كود الدولة')->required(),

                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->collection('image')->conversion('webp')->label('الصورة')->circular(),
                Tables\Columns\TextColumn::make('name')->label('الدولة'),
                Tables\Columns\TextColumn::make('code')->label('الرمز'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function($livewire,$data){
                    $data['server_id']=$livewire->ownerRecord->id;
                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                 Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
            ]);
    }
}
