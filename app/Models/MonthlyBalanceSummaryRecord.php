<?php

declare(strict_types=1);

namespace App\Models;

use App\DTOs\MonthlyBalanceSummary;
use Illuminate\Database\Eloquent\Model;

final class MonthlyBalanceSummaryRecord extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'year_month';

    protected $guarded = [];

    public $timestamps = false;

    public static function fromSummary(MonthlyBalanceSummary $summary): self
    {
        $record = new self([
            'year_month' => $summary->yearMonth,
            'month_label' => $summary->monthLabel,
            'total_income' => $summary->totalIncome,
            'total_expense' => $summary->totalExpense,
            'net_balance' => $summary->netBalance(),
        ]);

        $record->exists = true;

        return $record;
    }
}
