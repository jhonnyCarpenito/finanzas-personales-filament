<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\TransactionType;
use PHPUnit\Framework\TestCase;

class TransactionTypeTest extends TestCase
{
    public function test_income_has_correct_label(): void
    {
        $this->assertSame('Ingreso', TransactionType::Income->getLabel());
    }

    public function test_expense_has_correct_label(): void
    {
        $this->assertSame('Egreso', TransactionType::Expense->getLabel());
    }

    public function test_income_has_success_color(): void
    {
        $this->assertSame('success', TransactionType::Income->getColor());
    }

    public function test_expense_has_danger_color(): void
    {
        $this->assertSame('danger', TransactionType::Expense->getColor());
    }

    public function test_income_has_trending_up_icon(): void
    {
        $this->assertSame('heroicon-m-arrow-trending-up', TransactionType::Income->getIcon());
    }

    public function test_expense_has_trending_down_icon(): void
    {
        $this->assertSame('heroicon-m-arrow-trending-down', TransactionType::Expense->getIcon());
    }

    public function test_options_returns_all_cases(): void
    {
        $options = TransactionType::options();

        $this->assertSame([
            'income' => 'Ingreso',
            'expense' => 'Egreso',
        ], $options);
    }

    public function test_can_create_from_value(): void
    {
        $this->assertSame(TransactionType::Income, TransactionType::from('income'));
        $this->assertSame(TransactionType::Expense, TransactionType::from('expense'));
    }

    public function test_try_from_invalid_value_returns_null(): void
    {
        $this->assertNull(TransactionType::tryFrom('invalid'));
    }
}
