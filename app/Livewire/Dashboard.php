<?php

namespace App\Livewire;

use App\Enums\Type;
use App\Models\Transaction;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    private Carbon $startDate;

    private Carbon $endDate;

    private string $timezone;

    private string $inboundType;

    private string $outboundType;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->startOfMonth();
        $this->endDate = $this->startDate->clone()->endOfMonth();

        $this->timezone = auth()->user()->timezone;

        $this->inboundType = Type::INBOUND->value;
        $this->outboundType = Type::OUTBOUND->value;
    }

    public function render()
    {
        $inboundAmount = Transaction::done()
            ->betweenDates($this->startDate, $this->endDate, $this->timezone)
            ->inbound()
            ->sum('amount');

        $outboundAmount = Transaction::done()
            ->betweenDates($this->startDate, $this->endDate, $this->timezone)
            ->outbound()
            ->sum('amount');

        $wallet = Transaction::done()
            ->select(
                DB::raw(
                    "SUM(
                        case
                            when type = '$this->inboundType' then amount
                            when type = '$this->outboundType' then - amount
                            else 0
                        end
                    ) as balance"
                )
            )
            ->first()
            ?->balance ?? 0;

        $balance = $inboundAmount - $outboundAmount;

        $expensesByCategoryPieChart = $this->getExpensesByCategoryPieChart();

        $expensesBySubCategoryPieChart = $this->getExpensesBySubCategoryPieChart();

        $transactions = $this->getLatestTransactions();

        return view('livewire.dashboard', compact(
            'inboundAmount',
            'outboundAmount',
            'balance',
            'wallet',
            'expensesByCategoryPieChart',
            'expensesBySubCategoryPieChart',
            'transactions'
        ));
    }

    private function getExpensesByCategoryPieChart(): PieChartModel
    {
        $data = Transaction::done()
            ->betweenDates($this->startDate, $this->endDate, $this->timezone)
            ->outbound()
            ->select(
                'categories.name as title',
                'categories.color',
                DB::raw('SUM(amount) as value')
            )
            ->join('categories as sub_categories', 'transactions.category_id', '=', 'sub_categories.id')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->groupBy(['categories.name', 'categories.color'])
            ->get();

        $pieChart = (new PieChartModel())
            ->setOpacity(0.9)
            ->setAnimated(true)
            ->setType('donut')
            ->withDataLabels();

        foreach ($data as $item) {
            $pieChart->addSlice($item->title, $item->value / 100, $item->color, ['tooltip' => currencyFormat($item->value)]);
        }

        return $pieChart;
    }

    private function getExpensesBySubCategoryPieChart(): PieChartModel
    {
        $data = Transaction::done()
            ->betweenDates($this->startDate, $this->endDate, $this->timezone)
            ->outbound()
            ->select(
                'categories.name as title',
                'categories.color',
                DB::raw('SUM(amount) as value')
            )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->groupBy(['categories.name', 'categories.color'])
            ->get();

        $pieChart = (new PieChartModel())
            ->setOpacity(0.9)
            ->setAnimated(true)
            ->setType('donut')
            ->withDataLabels();

        foreach ($data as $item) {
            $pieChart->addSlice($item->title, $item->value / 100, $item->color, ['tooltip' => currencyFormat($item->value)]);
        }

        return $pieChart;
    }

    protected function getLatestTransactions(): Collection
    {
        return Transaction::done()
            ->with(['subCategory.category'])
            ->latest('performed_at')
            ->limit(10)
            ->get();
    }
}
