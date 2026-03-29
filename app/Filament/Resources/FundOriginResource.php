<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\TagColor;
use App\Filament\Resources\FundOriginResource\Pages;
use App\Models\FundOrigin;
use App\Support\CapitalAmountDisplay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FundOriginResource extends Resource
{
    protected static ?string $model = FundOrigin::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Capital Total';

    protected static ?string $modelLabel = 'Origen de fondos';

    protected static ?string $pluralModelLabel = 'Orígenes de fondos';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && ! Auth::user()->is_admin;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! Auth::check() || Auth::user()->is_admin) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: Banesco Panamá, MetaMask, Efectivo'),
                Forms\Components\TextInput::make('amount')
                    ->label('Monto')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->minValue(0)
                    ->prefix('$')
                    ->step(0.01),
                Forms\Components\Select::make('color')
                    ->label('Color')
                    ->options(TagColor::options())
                    ->native(false),
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id())
                    ->dehydrateStateUsing(fn () => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Origen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->formatStateUsing(fn ($state): string => CapitalAmountDisplay::formatUsingSession((float) $state))
                    ->sortable()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('color')
                    ->label('Color')
                    ->badge()
                    ->color(fn (?string $state): string => $state ?: 'gray')
                    ->formatStateUsing(fn (?string $state) => $state ? (TagColor::tryFrom($state)?->getLabel() ?? $state) : '-'),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFundOrigins::route('/'),
        ];
    }
}
