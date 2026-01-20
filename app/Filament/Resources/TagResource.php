<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Etiquetas';

    protected static ?string $modelLabel = 'Etiqueta';

    protected static ?string $pluralModelLabel = 'Etiquetas';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Admin ve todas las tags, usuario normal solo globales + propias
        if (! auth()->user()->is_admin) {
            $query->forUser(auth()->id());
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
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
                Forms\Components\Toggle::make('is_global')
                    ->label('Tag Global')
                    ->helperText('Las tags globales están disponibles para todos los usuarios')
                    ->visible(fn () => auth()->user()->is_admin)
                    ->afterStateHydrated(function (Forms\Components\Toggle $component, $record) {
                        if ($record) {
                            $component->state($record->user_id === null);
                        }
                    })
                    ->dehydrated(false),
                Forms\Components\Hidden::make('user_id')
                    ->default(function ($record) {
                        // Si es admin y marca como global, user_id es null
                        if (auth()->user()->is_admin && request()->boolean('is_global')) {
                            return null;
                        }
                        // Si es admin editando y no cambia el estado, mantener el valor
                        if ($record && auth()->user()->is_admin) {
                            return $record->user_id;
                        }

                        // Usuario normal siempre crea tags personales
                        return auth()->id();
                    })
                    ->dehydrateStateUsing(function ($state) {
                        if (auth()->user()->is_admin && request()->boolean('is_global')) {
                            return null;
                        }
                        if (auth()->user()->is_admin && ! request()->boolean('is_global')) {
                            return $state ?? auth()->id();
                        }

                        return auth()->id();
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Tag $record): ?string => $record->isGlobal() ? 'Tag Global' : null),
                Tables\Columns\TextColumn::make('color')
                    ->label('Color')
                    ->badge()
                    ->color(fn (string $state): string => $state ?: 'gray'),
                Tables\Columns\IconColumn::make('is_global')
                    ->label('Global')
                    ->boolean()
                    ->getStateUsing(fn (Tag $record): bool => $record->isGlobal())
                    ->visible(fn () => auth()->user()->is_admin),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Creada por')
                    ->default('Sistema')
                    ->visible(fn () => auth()->user()->is_admin),
                Tables\Columns\TextColumn::make('transactions_count')
                    ->label('Transacciones')
                    ->counts('transactions')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_global')
                    ->label('Tipo de Tag')
                    ->placeholder('Todas')
                    ->trueLabel('Globales')
                    ->falseLabel('Personales')
                    ->visible(fn () => auth()->user()->is_admin)
                    ->queries(
                        true: fn (Builder $query) => $query->whereNull('user_id'),
                        false: fn (Builder $query) => $query->whereNotNull('user_id'),
                    ),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTags::route('/'),
        ];
    }
}
