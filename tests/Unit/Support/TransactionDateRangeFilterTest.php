<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Models\Transaction;
use App\Models\User;
use App\Support\TransactionDateRangeFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class TransactionDateRangeFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_apply_includes_last_day_when_date_column_has_time_suffix(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-31',
        ]);

        DB::table('transactions')->where('id', $transaction->id)->update([
            'date' => '2026-01-31 00:00:00',
        ]);

        $start = Carbon::create(2026, 1, 1)->startOfMonth();
        $end = Carbon::create(2026, 1, 31)->endOfMonth();

        $count = Transaction::query()
            ->where('user_id', $user->id)
            ->tap(fn ($query) => TransactionDateRangeFilter::apply($query, $start, $end))
            ->count();

        $this->assertSame(1, $count);
    }

    public function test_apply_upper_bound_includes_last_day_when_date_column_has_time_suffix(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $transaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-01-31',
        ]);

        DB::table('transactions')->where('id', $transaction->id)->update([
            'date' => '2026-01-31 00:00:00',
        ]);

        $count = Transaction::query()
            ->where('user_id', $user->id)
            ->tap(fn ($query) => TransactionDateRangeFilter::applyUpperBound($query, '2026-01-31'))
            ->count();

        $this->assertSame(1, $count);
    }
}
