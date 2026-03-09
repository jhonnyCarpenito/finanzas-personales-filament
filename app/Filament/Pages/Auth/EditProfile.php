<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Validation\Rules\Password;

final class EditProfile extends BaseEditProfile
{
    public function mount(): void
    {
        parent::mount();

        if (session('success')) {
            Notification::make()
                ->success()
                ->title(session('success'))
                ->send();
            session()->forget('success');
        }
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->required(false)
            ->rule(Password::default())
            ->helperText(
                Auth::user()->google_id
                    ? __('Opcional. Si dejas en blanco, podrás seguir iniciando sesión con Google.')
                    : null
            );
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->required(fn (Get $get): bool => filled($get('password')));
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        Section::make(__('Cuenta de Google'))
                            ->schema([
                                View::make('filament.forms.components.google-account-link'),
                            ])
                            ->collapsible(),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(! static::isSimple()),
            ),
        ];
    }

    public function unlinkGoogle(): void
    {
        $user = Auth::user();
        $user->update(['google_id' => null, 'google_email' => null]);

        Notification::make()
            ->success()
            ->title(__('Cuenta de Google desvinculada'))
            ->send();

        $this->redirect(route('filament.admin.auth.profile'));
    }
}
