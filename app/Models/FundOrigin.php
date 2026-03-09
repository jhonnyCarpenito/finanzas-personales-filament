<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundOrigin extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'color',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'order' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
