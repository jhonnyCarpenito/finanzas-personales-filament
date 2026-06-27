<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\DTOs\TagAmountSummary;
use App\Enums\TransactionType;
use App\Repositories\TransactionTagAmountRepository;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

final class TransactionTagAmountsChartWidget extends ChartWidget
{
    protected static bool $isDiscovered = false;

    public TransactionType $transactionType = TransactionType::Expense;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $tableFilters = null;

    protected static ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    public function getHeading(): ?string
    {
        return match ($this->transactionType) {
            TransactionType::Income => 'Ingresos por etiqueta',
            TransactionType::Expense => 'Egresos por etiqueta',
        };
    }

    protected function getData(): array
    {
        $userId = (int) Auth::id();
        $typeValue = $this->transactionType->value;

        $summaries = app(TransactionTagAmountRepository::class)
            ->amountsByTagForUser($userId, $this->tableFilters)
            ->filter(fn (TagAmountSummary $summary): bool => $summary->totalForType($typeValue) > 0)
            ->values();

        $labels = $summaries->pluck('tagName')->all();
        $data = $summaries
            ->map(fn (TagAmountSummary $summary): float => $summary->totalForType($typeValue))
            ->all();

        $backgroundColors = $summaries
            ->map(fn (TagAmountSummary $summary, int $index): string => $this->resolveBarColor($summary->tagColor, $index))
            ->all();

        $borderColors = collect($backgroundColors)
            ->map(fn (string $color): string => str_replace('0.8', '1', $color))
            ->all();

        return [
            'datasets' => [
                [
                    'label' => match ($this->transactionType) {
                        TransactionType::Income => 'Ingresos',
                        TransactionType::Expense => 'Egresos',
                    },
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    private function resolveBarColor(?string $color, int $index): string
    {
        if ($color !== null && $color !== '') {
            return $this->colorToRgba($color);
        }

        $fallbackColors = [
            'rgba(34, 197, 94, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(234, 179, 8, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(236, 72, 153, 0.8)',
        ];

        return $fallbackColors[$index % count($fallbackColors)] ?? $fallbackColors[0];
    }

    private function colorToRgba(string $color): string
    {
        $map = [
            'success' => 'rgba(34, 197, 94, 0.8)',
            'danger' => 'rgba(239, 68, 68, 0.8)',
            'warning' => 'rgba(234, 179, 8, 0.8)',
            'info' => 'rgba(59, 130, 246, 0.8)',
            'gray' => 'rgba(107, 114, 128, 0.8)',
            'primary' => 'rgba(245, 158, 11, 0.8)',
            'purple' => 'rgba(168, 85, 247, 0.8)',
            'violet' => 'rgba(139, 92, 246, 0.8)',
            'teal' => 'rgba(20, 184, 166, 0.8)',
            'cyan' => 'rgba(6, 182, 212, 0.8)',
            'orange' => 'rgba(249, 115, 22, 0.8)',
            'rose' => 'rgba(244, 63, 94, 0.8)',
            'fuchsia' => 'rgba(217, 70, 239, 0.8)',
            'pink' => 'rgba(236, 72, 153, 0.8)',
        ];

        return $map[$color] ?? 'rgba(107, 114, 128, 0.8)';
    }
}
