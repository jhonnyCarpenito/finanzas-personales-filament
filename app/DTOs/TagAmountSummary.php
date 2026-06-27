<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class TagAmountSummary
{
    public function __construct(
        public int|string $tagId,
        public string $tagName,
        public ?string $tagColor,
        public float $incomeTotal,
        public float $expenseTotal,
    ) {}

    public function totalForType(string $type): float
    {
        return $type === 'income' ? $this->incomeTotal : $this->expenseTotal;
    }

    public function combinedTotal(): float
    {
        return $this->incomeTotal + $this->expenseTotal;
    }
}
