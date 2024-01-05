<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Filament\Resources\TransactionResource\Pages\ManageTransactions;
use App\Models\Transaction;
use Brick\Money\Money;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BalanceOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ManageTransactions::class;
    }

    protected function getStats(): array
    {
        $inboundAmount = $this->getInboundAmount();

        $outboundAmount = $this->getOutboundAmount();

        $balance = $inboundAmount - $outboundAmount;

        return [
            Stat::make('Saldo', Money::ofMinor(($balance), 'BRL')->formatTo(config('app.locale')))
                ->icon('heroicon-s-scale'),

            Stat::make('Entradas', Money::ofMinor(($inboundAmount), 'BRL')->formatTo(config('app.locale')))
                ->icon('heroicon-s-arrow-up-circle'),

            Stat::make('Saídas', Money::ofMinor(($outboundAmount), 'BRL')->formatTo(config('app.locale')))
                ->icon('heroicon-s-arrow-down-circle'),
        ];
    }

    private function getInboundAmount(): int
    {
        $userTimezone = auth()->user()->timezone;

        return Transaction::query()
            ->when(data_get($this->tableFilters, 'performed_at.from'), function ($query) use ($userTimezone) {
                return $query->fromDate($this->tableFilters['performed_at']['from'], $userTimezone);
            })
            ->when(data_get($this->tableFilters, 'performed_at.until'), function ($query) use ($userTimezone) {
                return $query->untilDate($this->tableFilters['performed_at']['until'], $userTimezone);
            })
            ->when($this->tableFilters['category']['category_id'], function ($query) {
                return $query->where('category_id', $this->tableFilters['category']['category_id']);
            })
            ->when($this->tableFilters['done']['isActive'], function ($query) {
                return $query->done();
            })
            ->inbound()
            ->sum('amount');
    }

    private function getOutboundAmount(): int
    {
        $userTimezone = auth()->user()->timezone;

        return Transaction::query()
            ->when(data_get($this->tableFilters, 'performed_at.from'), function ($query) use ($userTimezone) {
                return $query->fromDate($this->tableFilters['performed_at']['from'], $userTimezone);
            })
            ->when(data_get($this->tableFilters, 'performed_at.until'), function ($query) use ($userTimezone) {
                return $query->untilDate($this->tableFilters['performed_at']['until'], $userTimezone);
            })
            ->when($this->tableFilters['category']['category_id'], function ($query) {
                return $query->where('category_id', $this->tableFilters['category']['category_id']);
            })
            ->when($this->tableFilters['done']['isActive'], function ($query) {
                return $query->done();
            })
            ->outbound()
            ->sum('amount');
    }
}
