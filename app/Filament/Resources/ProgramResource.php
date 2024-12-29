<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Filament\Resources\ProgramResource\RelationManagers;
use App\Models\Program;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive';
    protected static ?string $label = 'التطبيقات';
    protected static ?string $navigationLabel = 'التطبيقات';
    protected static ?string $pluralLabel = 'التطبيقات';
    protected static ?string $navigationGroup = 'أونلاين';
    protected static ?int $navigationSort = 5;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الدول')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->label('الصورة'),
                    Forms\Components\TextInput::make('name')->label('اسم التطبيق')->required(),
                    Forms\Components\Select::make('server_id')->label('السيرفر')->relationship('server','name')->preload()->required(),
//                    Forms\Components\Select::make('country_id')->label('الدولة')->relationship('country','name')->preload()->required(),
                    Forms\Components\Select::make('department_id')->label('القسم')->relationship('department','name')->preload()->required(),
                    Forms\Components\TextInput::make('code')->label('كود التطبيق')->required(),
                    Forms\Components\TextInput::make('price')->label('سعر التطبيق')->required()->numeric(),
                    Forms\Components\TextInput::make('cost')->label('رأسمال التطبيق')->required()->numeric(),
                    Forms\Components\Toggle::make('is_active')->label('حالة التطبيق'),
                    Forms\Components\Repeater::make('countries')->schema([
                        Forms\Components\TextInput::make('code')->label('كود الدولة')->required(),
                    ])->label('أكواد الدول')->minItems(1)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sortable')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->circular()->collection('image')->conversion('webp')->label('الصورة'),
                Tables\Columns\TextColumn::make('name')->label('اسم التطبيق')->searchable(),
                /*Tables\Columns\TextColumn::make('country.name')->label('اسم الدولة'),*/
                Tables\Columns\TextColumn::make('server.name')->label('اسم السيرفر')->sortable(),
                Tables\Columns\TextColumn::make('department.name')->label('اسم القسم')->sortable(),
                Tables\Columns\TextColumn::make('price')->label('السعر')->sortable(),
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
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
