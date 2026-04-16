<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\CapitalSnapshot;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

final class CapitalTrendChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Evolución del Capital Total';

    protected static ?int $sort = 12;

    protected int|string|array $columnSpan = 1;

    public ?string $filter = 'monthly';

    public static function canView(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    #[On(CapitalTotalWidget::FUND_ORIGINS_DATA_CHANGED_EVENT)]
    public function refreshAfterFundOriginsDataChanged(): void
    {
        // Livewire event listener to refresh chart data.
    }

    /**
     * @return array<string, string>
     */
    protected function getFilters(): array
    {
        return [
            'daily' => 'Diaria',
            'monthly' => 'Mensual',
            'yearly' => 'Anual',
        ];
    }

    protected function getData(): array
    {
        $points = $this->getTrendPoints($this->filter ?? 'monthly');

        return [
            'datasets' => [
                [
                    'label' => 'Capital Total',
                    'data' => array_values($points),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => array_keys($points),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * @return array<string, float>
     */
    public function getTrendPoints(string $range): array
    {
        $selectedRange = in_array($range, ['daily', 'monthly', 'yearly'], true) ? $range : 'monthly';
        $startDate = $this->resolveStartDate($selectedRange);

        $snapshots = CapitalSnapshot::query()
            ->where('user_id', Auth::id())
            ->where('captured_at', '>=', $startDate)
            ->orderBy('captured_at')
            ->get(['captured_at', 'total_amount']);

        return $snapshots
            ->map(function (CapitalSnapshot $snapshot) use ($selectedRange): array {
                $capturedAt = $snapshot->captured_at instanceof Carbon
                    ? $snapshot->captured_at
                    : Carbon::parse((string) $snapshot->captured_at);

                return [
                    'bucket' => $this->formatBucketLabel($capturedAt, $selectedRange),
                    'total' => (float) $snapshot->total_amount,
                ];
            })
            ->reduce(function (array $carry, array $item): array {
                $carry[$item['bucket']] = $item['total'];

                return $carry;
            }, []);
    }

    private function resolveStartDate(string $range): Carbon
    {
        $now = now();

        return match ($range) {
            'daily' => $now->copy()->subDays(30)->startOfDay(),
            'yearly' => $now->copy()->subYears(5)->startOfYear(),
            default => $now->copy()->subMonths(12)->startOfMonth(),
        };
    }

    private function formatBucketLabel(Carbon $date, string $range): string
    {
        return match ($range) {
            'daily' => $date->format('d/m'),
            'yearly' => $date->format('Y'),
            default => ucfirst($date->locale('es')->translatedFormat('M Y')),
        };
    }
}
