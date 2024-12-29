<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $label = 'المدن';
    protected static ?string $navigationLabel = 'المدن';
    protected static ?string $pluralLabel = 'المدن';
    protected static ?string $navigationGroup = 'أونلاين';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الدول')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->label('الصورة'),
                    Forms\Components\TextInput::make('name')->label('اسم الدولة')->required(),
                    Forms\Components\Select::make('server_id')->label('السيرفر')->relationship('server', 'name')->preload()->required(),
                    Forms\Components\TextInput::make('code')->label('كود الدولة')->required(),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->circular()->collection('image')->conversion('webp')->label('الصورة'),
                Tables\Columns\TextColumn::make('name')->label('اسم الدولة')->sortable(),
                Tables\Columns\TextColumn::make('server.name')->label('اسم السيرفر')->sortable(),
                Tables\Columns\TextColumn::make('code')->label('كود الدولة'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('server_id')->relationship('server','name')->label('السيرفر')
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
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
