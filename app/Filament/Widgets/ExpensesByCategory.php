<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;

class ExpensesByCategory extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Despesas por Categoria';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        if (!data_get($this->filters, 'startDate') || !data_get($this->filters, 'endDate')) {
            return [];
        }

        $userTimezone = auth()->user()->timezone;

        $transactions = Transaction::done()
            ->fromDate($this->filters['startDate'], $userTimezone)
            ->untilDate($this->filters['endDate'], $userTimezone)
            ->outbound()
            ->select(
                'categories.name',
                'categories.color',
                DB::raw('SUM(amount) as value')
            )
            ->join('categories as sub_categories', 'transactions.category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->groupBy(['categories.name', 'categories.color'])
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total',
                    'data' => $transactions->map(fn ($transaction) => $transaction->value),
                    'backgroundColor' => $transactions->map(fn ($transaction) => $transaction->color),
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $transactions->map(fn ($transaction) => $transaction->name),
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            display: false
                        }
                    }
                },
                cutout: '65%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            generateLabels: function(chart) {
                                var data = chart.data;

                                if (data.labels.length && data.datasets.length) {
                                    var total = data.datasets[0].data.reduce(function(acc, value) {
                                        if (value) {
                                            return acc + Number(value);
                                        }

                                        return acc;
                                    }, 0);

                                    return data.labels.map(function(label, i) {
                                        var dataset = data.datasets[0];
                                        var percent = ((dataset.data[i] / total) * 100).toFixed(2) + '%';
                                        
                                        return {
                                            text: label + ' (' + percent + ')',
                                            fillStyle: dataset.backgroundColor[i],
                                            hidden: isNaN(dataset.data[i]) || dataset.data[i] === 0,
                                            index: i
                                        };
                                    });
                                }

                                return [];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return tooltipItem.label + ' ' + new Intl.NumberFormat('pt-BR', {
                                    style: 'currency', 
                                    currency: 'BRL', 
                                    minimumFractionDigits: 2, 
                                    maximumFractionDigits: 2
                                }).format(tooltipItem.raw / 100);
                            }
                        }
                    }
                }
            }
        JS);
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
