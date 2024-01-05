<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTransactions extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Últimas transações';

    protected static ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(TransactionResource::getEloquentQuery())
            ->paginated([5, 10, 25, 50])
            ->deferLoading()
            ->striped()
            ->defaultPaginationPageOption(5)
            ->defaultSort('performed_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

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
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money(currency: 'BRL', divideBy: 100)
                    ->prefix(fn (Transaction $record) => $record->type->isInbound() ? '+' : '-')
                    ->color(fn (Transaction $record) => $record->type->getColor())
                    ->sortable()
            ]);
    }
}
