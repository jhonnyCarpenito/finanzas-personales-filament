<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\TagAmountSummary;
use App\Models\Transaction;
use App\Support\TransactionTableFilterQueryApplier;
use Illuminate\Support\Collection;

final class TransactionTagAmountRepository
{
    public function __construct(
        private readonly TransactionTableFilterQueryApplier $filterQueryApplier,
    ) {}

    /**
     * @param  array<string, mixed>|null  $tableFilters
     * @return Collection<int, TagAmountSummary>
     */
    public function amountsByTagForUser(int $userId, ?array $tableFilters = null): Collection
    {
        $baseQuery = Transaction::query()->where('transactions.user_id', $userId);
        $this->filterQueryApplier->apply($baseQuery, $tableFilters);

        $taggedRows = (clone $baseQuery)
            ->join('tag_transaction', 'transactions.id', '=', 'tag_transaction.transaction_id')
            ->join('tags', 'tags.id', '=', 'tag_transaction.tag_id')
            ->selectRaw('tags.id as tag_id')
            ->selectRaw('tags.name as tag_name')
            ->selectRaw('tags.color as tag_color')
            ->selectRaw('transactions.type as transaction_type')
            ->selectRaw('SUM(transactions.amount) as total')
            ->groupBy('tags.id', 'tags.name', 'tags.color', 'transactions.type')
            ->get();

        $untaggedRows = (clone $baseQuery)
            ->whereDoesntHave('tags')
            ->selectRaw('transactions.type as transaction_type')
            ->selectRaw('SUM(transactions.amount) as total')
            ->groupBy('transactions.type')
            ->get();

        /** @var array<int|string, array{tag_name: string, tag_color: ?string, income: float, expense: float}> $summaries */
        $summaries = [];

        foreach ($taggedRows as $row) {
            $this->accumulateRow($summaries, (int) $row->tag_id, (string) $row->tag_name, $row->tag_color, (string) $row->transaction_type, (float) $row->total);
        }

        foreach ($untaggedRows as $row) {
            $this->accumulateRow($summaries, 'untagged', 'Sin etiqueta', null, (string) $row->transaction_type, (float) $row->total);
        }

        return collect($summaries)
            ->map(fn (array $summary, int|string $tagId): TagAmountSummary => new TagAmountSummary(
                tagId: $tagId,
                tagName: $summary['tag_name'],
                tagColor: $summary['tag_color'],
                incomeTotal: $summary['income'],
                expenseTotal: $summary['expense'],
            ))
            ->sortByDesc(fn (TagAmountSummary $summary): float => $summary->combinedTotal())
            ->values();
    }

    /**
     * @param  array<int|string, array{tag_name: string, tag_color: ?string, income: float, expense: float}>  $summaries
     */
    private function accumulateRow(
        array &$summaries,
        int|string $tagId,
        string $tagName,
        ?string $tagColor,
        string $transactionType,
        float $total,
    ): void {
        if (! isset($summaries[$tagId])) {
            $summaries[$tagId] = [
                'tag_name' => $tagName,
                'tag_color' => $tagColor,
                'income' => 0.0,
                'expense' => 0.0,
            ];
        }

        if ($transactionType === 'income') {
            $summaries[$tagId]['income'] += $total;

            return;
        }

        $summaries[$tagId]['expense'] += $total;
    }
}
