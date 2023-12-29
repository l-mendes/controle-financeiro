<?php

namespace App\Filament\Resources;

use App\Enums\Type;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $modelPluralLabel = 'Categorias';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->minLength(3)
                    ->maxLength(100)
                    ->columnSpanFull(),

                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\ColorPicker::make('color')
                            ->label('Cor')
                            ->required(),
                    ]),

                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options(Type::class)
                    ->default(Type::INBOUND->value)
                    ->native(false)
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome'),

                Tables\Columns\TextColumn::make('sub_categories_count')
                    ->counts('subCategories')
                    ->label('Sub-categorias'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo'),

                Tables\Columns\ColorColumn::make('color')
                    ->label('Cor'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, Category $record) {
                        if ($record->subCategories()->count()) {
                            Notification::make()
                                ->danger()
                                ->title('Falha ao excluir!')
                                ->body('A categoria possui sub-categorias.')
                                ->persistent()
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SubCategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->mainCategory();
    }
}
