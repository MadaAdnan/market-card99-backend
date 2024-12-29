<?php

namespace App\Filament\Resources;

use App\Enums\ActivateStatusStrEnum;
use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;
use App\Models\Server;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'heroicon-o-desktop-computer';
    protected static ?string $label = 'السيرفرات';
    protected static ?string $navigationLabel = 'السيرفرات';
    protected static ?string $pluralLabel = 'السيرفرات';
    protected static ?string $navigationGroup = 'أونلاين';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('السيرفرات')->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')->collection('image')->conversion('webp')->required()->label('صورة السيرفر')->image(),
                    Forms\Components\TextInput::make('name')->label('اسم السيرفر')->required(),
                    Forms\Components\Select::make('code')->options(
                        function(){
                        $path = app_path('Helpers/');
                        $fqcns = array();

                        $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
                        $phpFiles = new RegexIterator($allFiles, '/\.php$/');
                        foreach ($phpFiles as $phpFile) {
                            $content = file_get_contents($phpFile->getRealPath());
                            $tokens = token_get_all($content);
                            $namespace = 'App\Helpers\\';
                            for ($index = 0; isset($tokens[$index]); $index++) {
                                if (!isset($tokens[$index][0])) {
                                    continue;
                                }
                                if (
                                    T_NAMESPACE === $tokens[$index][0]
                                    && T_WHITESPACE === $tokens[$index + 1][0]
                                    && T_STRING === $tokens[$index + 2][0]
                                ) {
                                    //$namespace = $tokens[$index + 2][1];
                                    // Skip "namespace" keyword, whitespaces, and actual namespace
                                    $index += 2;
                                }
                                if (
                                    T_CLASS === $tokens[$index][0]
                                    && T_WHITESPACE === $tokens[$index + 1][0]
                                    && T_STRING === $tokens[$index + 2][0]
                                ) {
                                    $fqcns[$namespace . '' . $tokens[$index + 2][1]] = $namespace . '' . $tokens[$index + 2][1];
                                    // Skip "class" keyword, whitespaces, and actual classname
                                    $index += 2;

                                    # break if you have one class per file (psr-4 compliant)
                                    # otherwise you'll need to handle class constants (Foo::class)
                                    break;
                                }
                            }
                        }

                        return $fqcns;
                    })->label('المكتبة'),
                    Forms\Components\TextInput::make('api')->label('API الربط'),
                    Forms\Components\TextInput::make('username')->label('اسم المستخدم'),
                    Forms\Components\TextInput::make('password')->label('كلمة المرور'),
                    Forms\Components\Radio::make('is_active')->options([
                        'active'=>'مفعل',
                        'inactive'=>'غير مفعل'
                    ])->label('الحالة'),
                    Forms\Components\TextInput::make('network')->label('الشبكة'),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->circular()->collection('image')->conversion('webp')->label('الصورة'),
                Tables\Columns\TextColumn::make('name')->label('اسم السيرفر'),
                Tables\Columns\TextColumn::make('code')->label('المكتبة'),
                Tables\Columns\TextColumn::make('is_active')
                    ->formatStateUsing(fn($record)=>ActivateStatusStrEnum::tryFrom($record->is_active)?->status())
                    ->color(fn($record)=>ActivateStatusStrEnum::tryFrom($record->is_active)?->color())->label('الحالة'),

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
            RelationManagers\ProgramsRelationManager::class,
//            RelationManagers\CountriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
