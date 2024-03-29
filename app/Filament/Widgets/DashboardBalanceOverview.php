<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Brick\Money\Money;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class DashboardBalanceOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $inboundAmount = $this->getInboundAmount();

        $outboundAmount = $this->getOutboundAmount();

        $balance = $inboundAmount - $outboundAmount;

        return [
            Stat::make('Carteira', Money::ofMinor(($this->getWalletAmount()), 'BRL')->formatTo(config('app.locale')))
                ->icon('heroicon-s-wallet'),

            Stat::make('Saldo', Money::ofMinor(($balance), 'BRL')->formatTo(config('app.locale')))
                ->icon('heroicon-s-scale'),

            Stat::make('Entradas', Money::ofMinor(($inboundAmount), 'BRL')->formatTo(config('app.locale')))
                ->icon('heroicon-s-arrow-up-circle'),

            Stat::make('Saídas', Money::ofMinor(($outboundAmount), 'BRL')->formatTo(config('app.locale')))
                ->icon('heroicon-s-arrow-down-circle'),
        ];
    }

    private function getWalletAmount(): int
    {
        return Transaction::done()
            ->select(
                DB::raw(
                    "SUM(
                        case
                            when type = 'I' then amount
                            when type = 'O' then - amount
                            else 0
                        end
                    ) as balance"
                )
            )
            ->first()
            ?->balance ?? 0;
    }

    private function getInboundAmount(): int
    {
        $userTimezone = auth()->user()->timezone;

        if (!data_get($this->filters, 'startDate') || !data_get($this->filters, 'endDate')) {
            return 0;
        }

        return Transaction::query()
            ->fromDate($this->filters['startDate'], $userTimezone)
            ->untilDate($this->filters['endDate'], $userTimezone)
            ->when(data_get($this->filters, 'category_id'), function ($query) {
                return $query->whereIn('category_id', $this->filters['category_id']);
            })
            ->when(data_get($this->filters, 'main_category_id'), function ($query) {
                return $query->whereHas('subCategory', function ($query) {
                    return $query->where('category_id', $this->filters['main_category_id']);
                });
            })
            ->inbound()
            ->sum('amount');
    }

    private function getOutboundAmount(): int
    {
        $userTimezone = auth()->user()->timezone;

        if (!data_get($this->filters, 'startDate') || !data_get($this->filters, 'endDate')) {
            return 0;
        }

        return Transaction::query()
            ->fromDate($this->filters['startDate'], $userTimezone)
            ->untilDate($this->filters['endDate'], $userTimezone)
            ->when(data_get($this->filters, 'category_id'), function ($query) {
                return $query->whereIn('category_id', $this->filters['category_id']);
            })
            ->when(data_get($this->filters, 'main_category_id'), function ($query) {
                return $query->whereHas('subCategory', function ($query) {
                    return $query->where('category_id', $this->filters['main_category_id']);
                });
            })
            ->outbound()
            ->sum('amount');
    }
}
