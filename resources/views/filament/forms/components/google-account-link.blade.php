@php
    $hasGoogle = auth()->user()?->google_id !== null;
@endphp
<div class="space-y-2">
    @if ($hasGoogle)
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('Tu cuenta está vinculada con Google. Puedes iniciar sesión con email/contraseña o con Google.') }}
        </p>
        <x-filament::button
            color="danger"
            outlined
            size="sm"
            wire:click="unlinkGoogle"
        >
            {{ __('Desvincular cuenta de Google') }}
        </x-filament::button>
    @else
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('Vincula tu cuenta de Google para poder iniciar sesión de ambas formas.') }}
        </p>
        <x-filament::button
            :href="route('socialite.redirect', ['provider' => 'google', 'intent' => 'link'])"
            tag="a"
            color="gray"
            outlined
            size="sm"
        >
            {{ __('Vincular cuenta de Google') }}
        </x-filament::button>
    @endif
</div>
