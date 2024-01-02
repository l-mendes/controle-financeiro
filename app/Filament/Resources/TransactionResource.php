<?php

namespace App\Filament\Resources;

use App\Enums\Type;
use App\Filament\Resources\TransactionResource\Pages;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
                Forms\Components\TextInput::make('name')
                    ->label('Descrição')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255)
                    ->autofocus(),

                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options(Type::class)
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('main_category_id', null);
                        $set('category_id', null);
                    })
                    ->required(),

                Forms\Components\Select::make('main_category_id')
                    ->label('Categoria')
                    ->options(fn (Get $get) => match ($get('type')) {
                        Type::INBOUND->value => Category::query()
                            ->mainCategory()
                            ->inbound()
                            ->pluck('name', 'id'),

                        Type::OUTBOUND->value => Category::query()
                            ->mainCategory()
                            ->outbound()
                            ->pluck('name', 'id'),
                        default => [],
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => Category::find($value)?->name)
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('category_id', null))
                    ->required(),

                Forms\Components\Select::make('category_id')
                    ->label('Categoria')
                    ->options(function (Get $get) {
                        return Category::query()
                            ->subCategory()
                            ->where('category_id', $get('main_category_id'))
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => Category::find($value)?->name)
                    ->native(false)
                    ->required()
                    ->live(),

                Forms\Components\DateTimePicker::make('performed_at')
                    ->label('Data da transação')
                    ->default(now()->setTimezone(auth()->user()->timezone)->toDateTimeString())
                    ->seconds(false)
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label('Valor')
                    ->extraAlpineAttributes([
                        'x-on:keypress' => 'function() {
                                var charCode = event.keyCode || event.which;
                                if (charCode < 48 || charCode > 57) {
                                    event.preventDefault();
                                    return false;
                                }
                                return true;
                            }',

                        'x-on:keyup' => 'function() {
                                var money = $el.value.replace(/\D/g, "");
                                money = (money / 100).toFixed(2) + "";
                                money = money.replace(".", ",");
                                money = money.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
                                money = money.replace(/(\d)(\d{3}),/g, "$1.$2,");
                                
                                $el.value = money;
                            }',
                    ])
                    ->dehydrateStateUsing(
                        fn ($state): ?int => $state ?
                            Str::of($state)
                            ->replace('.', '')
                            ->replace(',', '')
                            ->toInteger()
                            : null
                    )
                    ->default(0.00)
                    ->formatStateUsing(fn ($state) => $state ? number_format(($state / 100), 2, ',', '.') : 0.00)
                    ->prefix('R$')
                    ->required(),

                Forms\Components\Checkbox::make('done')
                    ->label('Transação concluída?')
                    ->default(true)
                    ->inline()

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subCategory.category.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subCategory.name')
                    ->label('Sub-categoria')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('performed_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i', auth()->user()->timezone)
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money(currency: 'BRL', divideBy: 100)
                    ->prefix(fn (Transaction $record) => $record->type->isInbound() ? '+' : '-')
                    ->color(fn (Transaction $record) => $record->type->getColor())
                    ->sortable()
            ])
            ->paginated([10, 25, 50, 100])
            ->deferLoading()
            ->striped()
            ->filters([
                Tables\Filters\Filter::make('performed_at')
                    ->form([
                        DatePicker::make('from')
                            ->label('De')
                            ->default(now()->setTimezone(auth()->user()->timezone)->startOfMonth()->toDateTimeString()),

                        DatePicker::make('until')
                            ->label('Até')
                            ->default(now()->setTimezone(auth()->user()->timezone)->toDateTimeString()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('performed_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('performed_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make('De ' . Carbon::parse($data['from'])->format('d/m/Y'))
                                ->removeField('from');
                        }

                        if ($data['until'] ?? null) {
                            $indicators[] = Indicator::make('Até ' . Carbon::parse($data['until'])->format('d/m/Y'))
                                ->removeField('until');
                        }

                        return $indicators;
                    }),

                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoria')
                    ->form([
                        Select::make('type')
                            ->label('Tipo')
                            ->options(Type::class)
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
                    ->iconButton()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['main_category_id'] = Category::query()
                            ->mainCategory()
                            ->whereHas('subCategories', function ($q) use ($data) {
                                $q->where('id', $data['category_id']);
                            })
                            ->get()
                            ->first()
                            ?->id;

                        return $data;
                    })
                    ->modalWidth('lg'),

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
