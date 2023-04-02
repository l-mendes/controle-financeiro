<?php

namespace App\Http\Livewire;

use App\Enums\Type;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = $startDate->clone()->endOfMonth();
        $timezone = auth()->user()->timezone;
        $inboundType = Type::INBOUND->value;
        $outboundType = Type::OUTBOUND->value;

        $inboundAmount = Transaction::done()
            ->betweenDates($startDate, $endDate, $timezone)
            ->inbound()
            ->sum('amount');

        $outboundAmount = Transaction::done()
            ->betweenDates($startDate, $endDate, $timezone)
            ->outbound()
            ->sum('amount');

        $wallet = Transaction::done()
            ->select(
                DB::raw(
                    "SUM(
                        case
                            when type = '$inboundType' then amount
                            when type = '$outboundType' then - amount
                            else 0
                        end
                    ) as balance"
                )
            )
            ->first()
            ->balance;

        $balance = $inboundAmount - $outboundAmount;

        return view('livewire.dashboard', compact('inboundAmount', 'outboundAmount', 'balance', 'wallet'));
    }
}
