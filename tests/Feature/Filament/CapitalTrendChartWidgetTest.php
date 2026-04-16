<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Widgets\CapitalTrendChartWidget;
use App\Models\CapitalSnapshot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

final class CapitalTrendChartWidgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_view_widget_and_regular_user_can_view_it(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);
        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin);
        $this->assertFalse(CapitalTrendChartWidget::canView());

        $this->actingAs($user);
        $this->assertTrue(CapitalTrendChartWidget::canView());
    }

    public function test_daily_range_returns_last_value_per_day_for_authenticated_user(): void
    {
        Carbon::setTestNow('2026-04-16 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        /** @var User $other */
        $other = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        CapitalSnapshot::query()->create([
            'user_id' => $user->id,
            'total_amount' => 100,
            'captured_at' => '2026-04-10 08:00:00',
        ]);
        CapitalSnapshot::query()->create([
            'user_id' => $user->id,
            'total_amount' => 120,
            'captured_at' => '2026-04-10 18:00:00',
        ]);
        CapitalSnapshot::query()->create([
            'user_id' => $user->id,
            'total_amount' => 90,
            'captured_at' => '2026-04-11 09:00:00',
        ]);
        CapitalSnapshot::query()->create([
            'user_id' => $other->id,
            'total_amount' => 999,
            'captured_at' => '2026-04-11 10:00:00',
        ]);

        Livewire::test(CapitalTrendChartWidget::class)
            ->call('getTrendPoints', 'daily')
            ->assertReturned([
                '10/04' => 120.0,
                '11/04' => 90.0,
            ]);

        Carbon::setTestNow();
    }

    public function test_yearly_range_groups_values_by_year(): void
    {
        Carbon::setTestNow('2026-12-01 00:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        CapitalSnapshot::query()->create([
            'user_id' => $user->id,
            'total_amount' => 40,
            'captured_at' => '2025-03-05 10:00:00',
        ]);
        CapitalSnapshot::query()->create([
            'user_id' => $user->id,
            'total_amount' => 85,
            'captured_at' => '2026-11-30 10:00:00',
        ]);

        Livewire::test(CapitalTrendChartWidget::class)
            ->call('getTrendPoints', 'yearly')
            ->assertReturned([
                '2025' => 40.0,
                '2026' => 85.0,
            ]);

        Carbon::setTestNow();
    }
}
