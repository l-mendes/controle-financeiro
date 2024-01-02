<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Filament\Resources\TransactionResource\Pages\ManageTransactions;
use App\Models\Transaction;
use Brick\Money\Money;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

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
        $appTimezone = config('app.timezone');
        $userTimezone = auth()->user()->timezone;

        return Transaction::query()
            ->when($this->tableFilters['performed_at']['from'], function ($query) use ($appTimezone, $userTimezone) {
                return $query->where(
                    DB::raw("CONVERT_TZ(performed_at, '$appTimezone', '$userTimezone')"),
                    '>=',
                    $this->tableFilters['performed_at']['from']
                );
            })
            ->when($this->tableFilters['performed_at']['until'], function ($query) use ($appTimezone, $userTimezone) {
                return $query->where(
                    DB::raw("CONVERT_TZ(performed_at, '$appTimezone', '$userTimezone')"),
                    '<=',
                    $this->tableFilters['performed_at']['until']
                );
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
        $appTimezone = config('app.timezone');
        $userTimezone = auth()->user()->timezone;

        return Transaction::query()
            ->when($this->tableFilters['performed_at']['from'], function ($query) use ($appTimezone, $userTimezone) {
                return $query->where(
                    DB::raw("CONVERT_TZ(performed_at, '$appTimezone', '$userTimezone')"),
                    '>=',
                    $this->tableFilters['performed_at']['from']
                );
            })
            ->when($this->tableFilters['performed_at']['until'], function ($query) use ($appTimezone, $userTimezone) {
                return $query->where(
                    DB::raw("CONVERT_TZ(performed_at, '$appTimezone', '$userTimezone')"),
                    '<=',
                    $this->tableFilters['performed_at']['until']
                );
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
