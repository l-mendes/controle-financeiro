<?php

namespace App\Filament\Pages;

use App\Enums\Type;
use App\Models\Category;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Illuminate\Support\Arr;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersAction;

    public function mount(): void
    {
        if (!data_get($this->filters, 'startDate')) {
            $this->filters['startDate'] = now()->setTimezone(auth()->user()->timezone)->startOfMonth()->toDateString();
        }

        if (!data_get($this->filters, 'endDate')) {
            $this->filters['endDate'] = now()->setTimezone(auth()->user()->timezone)->toDateString();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->label('Filtrar')
                ->modalHeading('Filtrar')
                ->form([
                    DatePicker::make('startDate')
                        ->label('Data inicial')
                        ->default(now()->startOfMonth()->toDateString())
                        ->required(),

                    DatePicker::make('endDate')
                        ->label('Data final')
                        ->default(now()->setTimezone(auth()->user()->timezone)->toDateString())
                        ->required(),

                    Select::make('type')
                        ->label('Tipo')
                        ->options(Type::class)
                        ->afterStateUpdated(function (Set $set) {
                            $set('main_category_id', null);
                            $set('category_id', null);
                        })
                        ->live(),

                    Select::make('main_category_id')
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
                        ->afterStateUpdated(function (Set $set) {
                            $set('category_id', null);
                        })
                        ->searchable()
                        ->preload()
                        ->live(),

                    Select::make('category_id')
                        ->label('Sub-categoria')
                        ->options(
                            fn (Get $get) => Category::query()
                                ->subCategory()
                                ->whereIn('category_id', Arr::wrap($get('main_category_id')))
                                ->pluck('name', 'id')
                        )
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->live(),
                ])
                ->iconButton()
                ->slideOver()
                ->modalSubmitActionLabel('Aplicar')
                ->modalCancelActionLabel('Cancelar'),
        ];
    }
}
