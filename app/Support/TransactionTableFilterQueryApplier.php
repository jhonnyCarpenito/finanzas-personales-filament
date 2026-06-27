<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

final class TransactionTableFilterQueryApplier
{
    /**
     * @param  Builder<\App\Models\Transaction>  $query
     * @param  array<string, mixed>|null  $tableFilters
     */
    public function apply(Builder $query, ?array $tableFilters): void
    {
        if ($tableFilters === null) {
            return;
        }

        $this->applyMonthFilter($query, $tableFilters);
        $this->applyTagsFilter($query, $tableFilters);
        $this->applyTypeFilter($query, $tableFilters);
        $this->applyDateFilter($query, $tableFilters);
    }

    /**
     * @param  Builder<\App\Models\Transaction>  $query
     * @param  array<string, mixed>  $tableFilters
     */
    private function applyMonthFilter(Builder $query, array $tableFilters): void
    {
        $month = $tableFilters['month']['month'] ?? null;

        if (! is_string($month) || $month === '') {
            return;
        }

        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        TransactionDateRangeFilter::apply($query, $start, $end);
    }

    /**
     * @param  Builder<\App\Models\Transaction>  $query
     * @param  array<string, mixed>  $tableFilters
     */
    private function applyTagsFilter(Builder $query, array $tableFilters): void
    {
        $values = $tableFilters['tags']['values'] ?? null;

        if (! is_array($values) || $values === []) {
            return;
        }

        $query->whereHas('tags', fn (Builder $tagQuery): Builder => $tagQuery->whereKey($values));
    }

    /**
     * @param  Builder<\App\Models\Transaction>  $query
     * @param  array<string, mixed>  $tableFilters
     */
    private function applyTypeFilter(Builder $query, array $tableFilters): void
    {
        $type = $tableFilters['type']['value'] ?? null;

        if (! is_string($type) || $type === '') {
            return;
        }

        $query->where('transactions.type', $type);
    }

    /**
     * @param  Builder<\App\Models\Transaction>  $query
     * @param  array<string, mixed>  $tableFilters
     */
    private function applyDateFilter(Builder $query, array $tableFilters): void
    {
        $dateFrom = $tableFilters['date']['date_from'] ?? null;
        $dateTo = $tableFilters['date']['date_to'] ?? null;

        if (is_string($dateFrom) && $dateFrom !== '') {
            TransactionDateRangeFilter::applyFromStrings($query, $dateFrom);
        }

        if (is_string($dateTo) && $dateTo !== '') {
            TransactionDateRangeFilter::applyUpperBound($query, $dateTo);
        }
    }
}
