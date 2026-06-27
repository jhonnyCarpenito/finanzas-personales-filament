<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Enums\TransactionType;
use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use App\Filament\Widgets\TransactionTagAmountsChartWidget;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

final class TransactionTagAmountsChartWidgetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setFilamentPanel('app');
    }

    public function test_admin_cannot_view_widget_and_regular_user_can_view_it(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['is_admin' => true]);
        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin);
        $this->assertFalse(TransactionTagAmountsChartWidget::canView());

        $this->actingAs($user);
        $this->assertTrue(TransactionTagAmountsChartWidget::canView());
    }

    public function test_expense_chart_returns_amounts_grouped_by_tag(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        $foodTag = Tag::query()->create([
            'name' => 'Comida',
            'color' => 'danger',
            'user_id' => null,
        ]);
        $transportTag = Tag::query()->create([
            'name' => 'Transporte',
            'color' => 'info',
            'user_id' => $user->id,
        ]);

        $foodTransaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 25,
            'date' => '2026-06-10',
        ]);
        $foodTransaction->tags()->sync([$foodTag->id]);

        $transportTransaction = Transaction::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 15,
            'date' => '2026-06-11',
        ]);
        $transportTransaction->tags()->sync([$transportTag->id]);

        Livewire::test(TransactionTagAmountsChartWidget::class, [
            'transactionType' => TransactionType::Expense,
            'tableFilters' => [
                'month' => [
                    'month' => '2026-06',
                ],
            ],
        ])
            ->assertSuccessful()
            ->assertSet('transactionType', TransactionType::Expense);

        Carbon::setTestNow();
    }

    public function test_transactions_page_exposes_tag_amount_chart_header_action(): void
    {
        Carbon::setTestNow('2026-06-15 12:00:00');

        /** @var User $user */
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        Livewire::test(ListTransactions::class)
            ->assertActionVisible('tagAmountChart')
            ->assertActionHasLabel('tagAmountChart', 'Gráfico por etiquetas')
            ->mountAction('tagAmountChart')
            ->assertActionMounted('tagAmountChart');

        Carbon::setTestNow();
    }
}
