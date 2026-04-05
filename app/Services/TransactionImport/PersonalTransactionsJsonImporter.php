<?php

declare(strict_types=1);

namespace App\Services\TransactionImport;

use App\Enums\TransactionType;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\File;
use JsonException;
use RuntimeException;

final class PersonalTransactionsJsonImporter
{
    private const DEFAULT_TAG_COLOR = 'gray';

    public function import(User $user, string $absolutePathToJson): void
    {
        if (! File::isFile($absolutePathToJson)) {
            throw new RuntimeException('Transaction import file not found: '.$absolutePathToJson);
        }

        $raw = File::get($absolutePathToJson);

        try {
            /** @var mixed $decoded */
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Invalid transaction import JSON: '.$e->getMessage(), 0, $e);
        }

        if (! is_array($decoded) || ! isset($decoded['transactions']) || ! is_array($decoded['transactions'])) {
            throw new RuntimeException('Transaction import JSON must contain a "transactions" array.');
        }

        foreach ($decoded['transactions'] as $index => $row) {
            if (! is_array($row)) {
                throw new RuntimeException('Transaction import row '.$index.' must be an object.');
            }

            $this->importRow($user, $row, $index);
        }
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function importRow(User $user, array $row, int $index): void
    {
        foreach (['date', 'amount', 'concept', 'type'] as $key) {
            if (! array_key_exists($key, $row)) {
                throw new RuntimeException('Transaction import row '.$index.' is missing "'.$key.'".');
            }
        }

        $type = TransactionType::tryFrom((string) $row['type']);

        if ($type === null) {
            throw new RuntimeException('Transaction import row '.$index.' has invalid type.');
        }

        $tags = $row['tags'] ?? [];

        if (! is_array($tags)) {
            throw new RuntimeException('Transaction import row '.$index.' "tags" must be an array.');
        }

        $tagNames = [];

        foreach ($tags as $tag) {
            if (! is_string($tag) || $tag === '') {
                throw new RuntimeException('Transaction import row '.$index.' contains an invalid tag name.');
            }

            $tagNames[] = $tag;
        }

        $amount = $this->normalizeAmount($row['amount']);
        $date = (string) $row['date'];
        $concept = (string) $row['concept'];

        $transaction = $this->findOrCreateTransaction($user, $date, $concept, $amount, $type);

        $tagIds = [];

        foreach ($tagNames as $name) {
            $tagIds[] = $this->resolveOrCreateTag($user, $name)->id;
        }

        $transaction->tags()->sync(array_values(array_unique($tagIds)));
    }

    private function findOrCreateTransaction(User $user, string $date, string $concept, string $amount, TransactionType $type): Transaction
    {
        $candidates = Transaction::query()
            ->where('user_id', $user->id)
            ->whereDate('date', $date)
            ->where('concept', $concept)
            ->where('type', $type)
            ->get();

        foreach ($candidates as $candidate) {
            if ($this->normalizeAmount($candidate->amount) === $amount) {
                return $candidate;
            }
        }

        return Transaction::create([
            'user_id' => $user->id,
            'date' => $date,
            'concept' => $concept,
            'amount' => $amount,
            'type' => $type,
        ]);
    }

    private function normalizeAmount(mixed $amount): string
    {
        if (is_int($amount) || is_float($amount)) {
            return number_format((float) $amount, 2, '.', '');
        }

        if (is_string($amount) && is_numeric($amount)) {
            return number_format((float) $amount, 2, '.', '');
        }

        throw new RuntimeException('Transaction amount must be numeric.');
    }

    private function resolveOrCreateTag(User $user, string $name): Tag
    {
        $existing = Tag::forUser($user->id)->where('name', $name)->first();

        if ($existing !== null) {
            return $existing;
        }

        return Tag::create([
            'name' => $name,
            'user_id' => $user->id,
            'color' => self::DEFAULT_TAG_COLOR,
        ]);
    }
}
