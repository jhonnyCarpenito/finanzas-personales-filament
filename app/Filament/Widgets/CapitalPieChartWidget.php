<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\FundOrigin;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class CapitalPieChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Distribución por Origen (%)';

    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    protected function getData(): array
    {
        $origins = FundOrigin::query()
            ->where('user_id', Auth::id())
            ->orderBy('order')
            ->orderBy('name')
            ->get(['name', 'amount', 'color']);

        $total = $origins->sum('amount');
        $labels = $origins->pluck('name')->toArray();
        $data = $origins->map(fn ($o) => $total > 0 ? round((float) $o->amount / (float) $total * 100, 1) : 0)->toArray();

        $colors = [
            'rgba(34, 197, 94, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(234, 179, 8, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(236, 72, 153, 0.8)',
        ];

        $backgroundColors = [];
        foreach ($origins as $i => $origin) {
            $backgroundColors[] = $origin->color ? $this->colorToRgba($origin->color) : ($colors[$i % count($colors)] ?? $colors[0]);
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
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
