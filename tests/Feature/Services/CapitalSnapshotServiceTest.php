<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\CapitalSnapshot;
use App\Models\FundOrigin;
use App\Models\User;
use App\Services\CapitalSnapshotBackfillService;
use App\Services\CapitalSnapshotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CapitalSnapshotServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_capture_for_user_creates_snapshot_with_current_total(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $other = User::factory()->create(['is_admin' => false]);

        FundOrigin::factory()->create(['user_id' => $user->id, 'amount' => 100.25]);
        FundOrigin::factory()->create(['user_id' => $user->id, 'amount' => 49.75]);
        FundOrigin::factory()->create(['user_id' => $other->id, 'amount' => 999.00]);

        (new CapitalSnapshotService())->captureForUser($user->id);

        $this->assertDatabaseCount('capital_snapshots', 1);

        $snapshot = CapitalSnapshot::query()->first();
        $this->assertNotNull($snapshot);
        $this->assertSame($user->id, $snapshot->user_id);
        $this->assertSame('150.00', $snapshot->total_amount);
    }

    public function test_backfill_creates_initial_snapshot_for_regular_users_and_is_idempotent(): void
    {
        $regular = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->create(['is_admin' => true]);

        FundOrigin::factory()->create(['user_id' => $regular->id, 'amount' => 300]);
        FundOrigin::factory()->create(['user_id' => $regular->id, 'amount' => 25]);
        FundOrigin::factory()->create(['user_id' => $admin->id, 'amount' => 400]);

        $backfillService = new CapitalSnapshotBackfillService();
        $backfillService->backfill();
        $backfillService->backfill();

        $this->assertDatabaseCount('capital_snapshots', 1);
        $this->assertDatabaseHas('capital_snapshots', [
            'user_id' => $regular->id,
            'total_amount' => '325.00',
        ]);
        $this->assertDatabaseMissing('capital_snapshots', [
            'user_id' => $admin->id,
        ]);
    }
}
