<?php

declare(strict_types=1);

use App\Services\CapitalSnapshotBackfillService;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capital_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamp('captured_at');
            $table->timestamps();

            $table->index(['user_id', 'captured_at']);
        });

        (new CapitalSnapshotBackfillService())->backfill();
    }

    public function down(): void
    {
        Schema::dropIfExists('capital_snapshots');
    }
};
