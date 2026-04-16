<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class CapitalSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'captured_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'captured_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
