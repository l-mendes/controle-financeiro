<?php

namespace App\Http\Livewire\Charts;

use Asantibanez\LivewireCharts\Charts\LivewirePieChart;

class LivewireCurrencyDonutChart extends LivewirePieChart
{
    public function render()
    {
        return view('livewire.charts.livewire-currency-donut-chart');
    }
}
