<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Usuario')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => ! empty($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->helperText('Dejar en blanco para mantener la contraseña actual'),
                        Forms\Components\Toggle::make('is_admin')
                            ->label('Administrador')
                            ->helperText('Los administradores tienen acceso completo al sistema'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('blocked_at')
                    ->label('Bloqueado')
                    ->boolean()
                    ->getStateUsing(fn (User $record): bool => $record->isBlocked())
                    ->sortable(),
                Tables\Columns\TextColumn::make('transactions_count')
                    ->label('Transacciones')
                    ->counts('transactions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('Tipo de Usuario')
                    ->placeholder('Todos')
                    ->trueLabel('Administradores')
                    ->falseLabel('Usuarios Normales'),
                Tables\Filters\TernaryFilter::make('blocked')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Bloqueados')
                    ->falseLabel('Activos')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('blocked_at'),
                        false: fn ($query) => $query->whereNull('blocked_at'),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('block')
                    ->label('Bloquear')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Bloquear Usuario')
                    ->modalDescription('¿Estás seguro de bloquear este usuario? No podrá acceder al sistema.')
                    ->action(function (User $record) {
                        $record->block();
                        Notification::make()
                            ->success()
                            ->title('Usuario bloqueado')
                            ->body('El usuario ha sido bloqueado exitosamente.')
                            ->send();
                    })
                    ->visible(fn (User $record): bool => ! $record->isBlocked() && ! $record->is_admin),
                Tables\Actions\Action::make('unblock')
                    ->label('Desbloquear')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Desbloquear Usuario')
                    ->modalDescription('¿Estás seguro de desbloquear este usuario?')
                    ->action(function (User $record) {
                        $record->unblock();
                        Notification::make()
                            ->success()
                            ->title('Usuario desbloqueado')
                            ->body('El usuario ha sido desbloqueado exitosamente.')
                            ->send();
                    })
                    ->visible(fn (User $record): bool => $record->isBlocked()),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record): bool => ! $record->is_admin),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
