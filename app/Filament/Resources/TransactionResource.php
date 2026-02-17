<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Tag;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Transacciones';

    protected static ?string $modelLabel = 'Transacción';

    protected static ?string $pluralModelLabel = 'Transacciones';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalles de la Transacción')
                    ->schema([
                        Forms\Components\TextInput::make('concept')
                            ->label('Concepto')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->prefix('$')
                            ->step(0.01),
                        Forms\Components\DatePicker::make('date')
                            ->label('Fecha')
                            ->required()
                            ->default(now())
                            ->maxDate(now()),
                        Forms\Components\Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'income' => 'Ingreso',
                                'expense' => 'Egreso',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('tags')
                            ->label('Etiquetas')
                            ->relationship(
                                'tags',
                                'name',
                                fn (Builder $query) => $query->forUser(auth()->id())
                            )
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Tag::class, 'name'),
                                Forms\Components\Select::make('color')
                                    ->label('Color')
                                    ->options([
                                        'success' => 'Verde',
                                        'danger' => 'Rojo',
                                        'warning' => 'Amarillo',
                                        'info' => 'Azul',
                                        'gray' => 'Gris',
                                    ])
                                    ->native(false),
                            ])
                            ->createOptionUsing(function (array $data) {
                                // Usuario normal crea tags personales, admin puede crear globales
                                return Tag::create([
                                    'name' => $data['name'],
                                    'color' => $data['color'] ?? null,
                                    'user_id' => auth()->id(), // Tags creadas desde transacciones son personales
                                ])->id;
                            }),
                        Forms\Components\Hidden::make('user_id')
                            ->default(auth()->id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('concept')
                    ->label('Concepto')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Etiquetas')
                    ->badge()
                    ->separator(','),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'income' => 'Ingreso',
                        'expense' => 'Egreso',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'income' => 'heroicon-m-arrow-trending-up',
                        'expense' => 'heroicon-m-arrow-trending-down',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('USD')
                    ->sortable()
                    ->alignEnd(),
            ])
            ->filters([
                Filter::make('month')
                    ->label('Mes')
                    ->form([
                        Forms\Components\Select::make('month')
                            ->label('Mes')
                            ->options(static::getMonthFilterOptions())
                            ->searchable()
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $month = $data['month'] ?? null;

                        if (! $month) {
                            return $query;
                        }

                        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                        $end = (clone $start)->endOfMonth();

                        return $query->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
                    }),
                SelectFilter::make('tags')
                    ->label('Etiquetas')
                    ->relationship(
                        'tags',
                        'name',
                        fn (Builder $query) => $query->forUser((int) auth()->id())
                    )
                    ->multiple()
                    ->preload()
                    ->searchable(),
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'income' => 'Ingreso',
                        'expense' => 'Egreso',
                    ]),
                Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('date_to')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    /**
     * @return array<string, string>
     */
    protected static function getMonthFilterOptions(int $monthsBack = 24): array
    {
        $options = [];

        $cursor = now()->startOfMonth();

        for ($i = 0; $i < $monthsBack; $i++) {
            $value = $cursor->format('Y-m');
            $label = $cursor->translatedFormat('F Y'); // e.g. "febrero 2026"

            $options[$value] = ucfirst($label);

            $cursor->subMonth();
        }

        return $options;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
