<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\TransactionResource\Widgets\BalanceOverview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ManageRecords;

class ManageTransactions extends ManageRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TransactionResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            BalanceOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('lg'),
        ];
    }
}
