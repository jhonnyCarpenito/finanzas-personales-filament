<div class="space-y-6">
    @livewire(\App\Filament\Widgets\TransactionTagAmountsChartWidget::class, [
        'transactionType' => \App\Enums\TransactionType::Income,
        'tableFilters' => $tableFilters,
    ], key('transaction-tag-income-chart-' . md5(json_encode($tableFilters ?? []))))

    @livewire(\App\Filament\Widgets\TransactionTagAmountsChartWidget::class, [
        'transactionType' => \App\Enums\TransactionType::Expense,
        'tableFilters' => $tableFilters,
    ], key('transaction-tag-expense-chart-' . md5(json_encode($tableFilters ?? []))))
</div>
