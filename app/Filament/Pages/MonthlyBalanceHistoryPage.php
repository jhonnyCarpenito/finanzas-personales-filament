<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\MonthlyBalanceSummaryRecord;
use App\Models\Transaction;
use App\Services\MonthlyBalanceHistoryService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

final class MonthlyBalanceHistoryPage extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Histórico de balance';

    protected static ?string $title = 'Histórico de balance';

    protected static ?string $navigationParentItem = 'Transacciones';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.monthly-balance-history-page';

    #[Url(as: 'year')]
    public ?int $year = null;

    /**
     * @var array{year: int|null}
     */
    public array $filtersData = [];

    public static function canAccess(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    public function mount(): void
    {
        $this->year ??= now()->year;

        $this->filtersForm->fill([
            'year' => $this->year,
        ]);
    }

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('year')
                    ->label('Año')
                    ->options(fn (): array => app(MonthlyBalanceHistoryService::class)
                        ->getAvailableYearsForUser((int) Auth::id()))
                    ->native(false)
                    ->live(),
            ])
            ->statePath('filtersData');
    }

    public function updatedFiltersData(): void
    {
        $this->year = (int) ($this->filtersData['year'] ?? now()->year);
        $this->flushCachedTableRecords();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Transaction::query()->whereRaw('1 = 0'))
            ->paginated(false)
            ->columns([
                TextColumn::make('month_label')
                    ->label('Mes'),
                TextColumn::make('total_income')
                    ->label('Ingresos')
                    ->money('USD')
                    ->color('success')
                    ->alignEnd(),
                TextColumn::make('total_expense')
                    ->label('Egresos')
                    ->money('USD')
                    ->color('danger')
                    ->alignEnd(),
                TextColumn::make('net_balance')
                    ->label('Balance')
                    ->money('USD')
                    ->color(fn (MonthlyBalanceSummaryRecord $record): string => $record->net_balance >= 0 ? 'success' : 'danger')
                    ->alignEnd(),
            ])
            ->actions([
                Action::make('viewTransactions')
                    ->label('Ver transacciones')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (MonthlyBalanceSummaryRecord $record): string => TransactionResource::getUrl('index', [
                        'tableFilters' => [
                            'month' => [
                                'month' => $record->year_month,
                            ],
                        ],
                    ])),
            ])
            ->defaultSort('year_month', 'desc')
            ->emptyStateHeading('Sin movimientos')
            ->emptyStateDescription('No hay transacciones registradas para el año seleccionado.');
    }

    /**
     * @return EloquentCollection<int, MonthlyBalanceSummaryRecord>|Paginator|CursorPaginator
     */
    public function getTableRecords(): EloquentCollection|Paginator|CursorPaginator
    {
        if ($this->cachedTableRecords !== null) {
            return $this->cachedTableRecords;
        }

        $summaries = app(MonthlyBalanceHistoryService::class)
            ->getSummariesForUser((int) Auth::id(), (int) ($this->year ?? now()->year));

        return $this->cachedTableRecords = new EloquentCollection(
            $summaries
                ->map(fn ($summary) => MonthlyBalanceSummaryRecord::fromSummary($summary))
                ->all(),
        );
    }

    protected function resolveTableRecord(?string $key): ?Model
    {
        if ($key === null) {
            return null;
        }

        /** @var MonthlyBalanceSummaryRecord|null $record */
        $record = $this->getTableRecords()->firstWhere('year_month', $key);

        return $record;
    }

    public function getAllTableRecordsCount(): int
    {
        return $this->getTableRecords()->count();
    }

    protected function getForms(): array
    {
        return [
            'filtersForm',
        ];
    }
}
