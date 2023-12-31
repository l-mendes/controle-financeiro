<?php

namespace App\Filament\Resources;

use App\Enums\Type;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrows-right-left';

    protected static ?string $modelLabel = 'Transação';

    protected static ?string $pluralModelLabel = 'Transações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subCategory.category.name')
                    ->label('Categoria')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subCategory.name')
                    ->label('Sub-categoria')
                    ->searchable(),

                Tables\Columns\TextColumn::make('performed_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money(currency: 'BRL', divideBy: 100)
                    ->prefix(fn (Transaction $record) => $record->type->isInbound() ? '+' : '-')
                    ->color(fn (Transaction $record) => $record->type->getColor())
            ])
            ->filters([
                Tables\Filters\Filter::make('performed_at')
                    ->form([
                        DatePicker::make('performed_from')
                            ->label('De')
                            ->default(now()->startOfMonth()),

                        DatePicker::make('performed_until')
                            ->label('Até')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['performed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('performed_at', '>=', $date),
                            )
                            ->when(
                                $data['performed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('performed_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['performed_from'] ?? null) {
                            $indicators[] = Indicator::make('De ' . Carbon::parse($data['performed_from'])->format('d/m/Y'))
                                ->removeField('performed_from');
                        }

                        if ($data['performed_until'] ?? null) {
                            $indicators[] = Indicator::make('Até ' . Carbon::parse($data['performed_until'])->format('d/m/Y'))
                                ->removeField('performed_until');
                        }

                        return $indicators;
                    }),

                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoria')
                    ->form([
                        Select::make('type')
                            ->label('Tipo')
                            ->options(function () {
                                $types = [];
                                foreach (Type::cases() as $type) {
                                    $types[$type->value] = $type->getLabelText();
                                }

                                return $types;
                            })
                            ->afterStateUpdated(function (Set $set) {
                                $set('category_id', null);
                            })
                            ->live(),

                        Select::make('category_id')
                            ->label('Categoria')
                            ->relationship('subCategory.category', 'name', function (Builder $query, Get $get) {
                                $type = $get('type');
                                return $query->mainCategory()
                                    ->when($type, function ($query) use ($type) {
                                        $query->where('type', $type);
                                    });
                            })
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->live(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $type = $data['type'];
                        $categoryId   = (int) $data['category_id'];

                        if ($type) {
                            $query->where('type', $type);
                        }

                        if ($categoryId) {
                            $query->whereHas('subCategory', function ($q) use ($categoryId) {
                                $q->where('category_id', $categoryId);
                            });
                        }
                    }),

                Tables\Filters\Filter::make('done')
                    ->label('Somente transações concluídas')
                    ->query(fn (Builder $query): Builder => $query->where('done', true))

            ], layout: FiltersLayout::Modal)
            ->filtersTriggerAction(fn (Tables\Actions\Action $action) => $action->slideOver())
            ->recordAction(null)
            ->actions([
                Tables\Actions\Action::make('validateTransaction')
                    ->label(fn (Transaction $record) => $record->done ? 'Marcar como não concluída' : 'Marcar como concluída')
                    ->iconButton()
                    ->icon(fn (Transaction $record) => $record->done ? 'heroicon-s-arrow-uturn-left' : 'heroicon-s-check')
                    ->color(fn (Transaction $record) => $record->done ? 'warning' : 'success')
                    ->action(fn (Transaction $record) => $record->update(['done' => !$record->done])),

                Tables\Actions\EditAction::make()
                    ->iconButton(),

                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTransactions::route('/'),
        ];
    }
}
