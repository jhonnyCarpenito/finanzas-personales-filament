<nav class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-transparent" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Brand Logo & Name -->
            <div class="flex items-center min-w-0">
                <a href="{{ route(app()->getLocale() === 'en' ? 'landing.en' : 'landing') }}" class="flex items-center gap-2 group min-w-0">
                    <div class="bg-amber-500/10 p-1.5 sm:p-2 rounded-xl border border-amber-500/20 group-hover:border-amber-500/40 transition-colors shrink-0">
                        @include('landing.partials.logo', ['class' => 'w-5 h-5 text-amber-500'])
                    </div>
                    <span class="font-bold text-sm sm:text-base md:text-lg tracking-tight text-white truncate max-w-[145px] sm:max-w-none">Finanzas Personales</span>
                </a>
            </div>
            
            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center space-x-5 xl:space-x-7 shrink-0">
                <a href="#comparison" class="text-sm text-zinc-300 hover:text-white transition-colors whitespace-nowrap">{{ __('landing.nav.comparison') }}</a>
                <a href="#features" class="text-sm text-zinc-300 hover:text-white transition-colors whitespace-nowrap">{{ __('landing.nav.features') }}</a>
                <a href="#audience" class="text-sm text-zinc-300 hover:text-white transition-colors whitespace-nowrap">{{ __('landing.nav.audience') }}</a>
                <a href="#security" class="text-sm text-zinc-300 hover:text-white transition-colors whitespace-nowrap">{{ __('landing.nav.security') }}</a>
                <a href="#faq" class="text-sm text-zinc-300 hover:text-white transition-colors whitespace-nowrap">{{ __('landing.nav.faq') }}</a>
                
                <!-- Language Switcher -->
                <div class="flex items-center space-x-2 border-l border-zinc-800 pl-5 shrink-0">
                    @if(app()->getLocale() === 'es')
                        <span class="text-xs font-semibold text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded border border-amber-500/20">ES</span>
                        <a href="{{ route('landing.en') }}" class="text-xs font-medium text-zinc-400 hover:text-white transition-colors px-1.5 py-0.5">EN</a>
                    @else
                        <a href="{{ route('landing') }}" class="text-xs font-medium text-zinc-400 hover:text-white transition-colors px-1.5 py-0.5">ES</a>
                        <span class="text-xs font-semibold text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded border border-amber-500/20">EN</span>
                    @endif
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3 shrink-0">
                    @auth
                        <a href="/app" class="px-4 py-2 text-sm font-semibold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-xl transition-all shadow-[0_0_15px_rgba(245,158,11,0.25)] hover:shadow-[0_0_20px_rgba(245,158,11,0.4)] whitespace-nowrap">
                            {{ __('landing.nav.dashboard') }}
                        </a>
                    @else
                        <a href="/app/login" class="px-3 py-2 text-sm font-medium text-zinc-300 hover:text-white hover:bg-zinc-800/60 rounded-xl transition-all whitespace-nowrap">
                            {{ __('landing.nav.login') }}
                        </a>
                        <a href="{{ route('socialite.redirect', 'google') }}" class="px-4 py-2 text-sm font-semibold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-xl transition-all shadow-[0_0_15px_rgba(245,158,11,0.25)] hover:shadow-[0_0_20px_rgba(245,158,11,0.4)] whitespace-nowrap">
                            {{ __('landing.nav.get_started') }}
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Mobile Controls (Language + CTA + Hamburger) -->
            <div class="flex lg:hidden items-center gap-2 shrink-0">
                <!-- Language Switcher in header on sm+ screens (tablets/landscape) -->
                <div class="hidden sm:flex items-center space-x-1 bg-zinc-900 px-2 py-1 rounded-lg border border-zinc-800 shrink-0">
                    @if(app()->getLocale() === 'es')
                        <span class="text-xs font-bold text-amber-500">ES</span>
                        <span class="text-zinc-600 text-xs">/</span>
                        <a href="{{ route('landing.en') }}" class="text-xs text-zinc-400 hover:text-white transition-colors">EN</a>
                    @else
                        <a href="{{ route('landing') }}" class="text-xs text-zinc-400 hover:text-white transition-colors">ES</a>
                        <span class="text-zinc-600 text-xs">/</span>
                        <span class="text-xs font-bold text-amber-500">EN</span>
                    @endif
                </div>

                @auth
                    <a href="/app" class="px-2.5 sm:px-3 py-1.5 text-xs font-semibold bg-amber-500 text-amber-950 rounded-lg whitespace-nowrap shrink-0">
                        {{ __('landing.nav.dashboard') }}
                    </a>
                @else
                    <a href="/app/login" class="px-2.5 sm:px-3 py-1.5 text-xs font-semibold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-lg whitespace-nowrap transition-colors shrink-0">
                        {{ __('landing.nav.login') }}
                    </a>
                @endauth

                <!-- Hamburger Button -->
                <button type="button" id="mobile-menu-btn" class="p-1.5 sm:p-2 text-zinc-400 hover:text-white bg-zinc-900/80 border border-zinc-800 rounded-xl focus:outline-none shrink-0 transition-colors" aria-label="Abrir menú" aria-expanded="false">
                    <svg class="w-5 h-5 menu-open-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="w-5 h-5 menu-close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-menu" class="hidden lg:hidden bg-zinc-950/95 backdrop-blur-xl border-b border-zinc-800 px-5 pt-3 pb-6 space-y-2">
        <!-- Language Switcher in Drawer -->
        <div class="flex items-center justify-between pb-3 mb-2 border-b border-zinc-900">
            <span class="text-xs text-zinc-400 font-medium">{{ app()->getLocale() === 'es' ? 'Idioma' : 'Language' }}</span>
            <div class="flex items-center space-x-1.5 bg-zinc-900 px-2.5 py-1 rounded-lg border border-zinc-800">
                @if(app()->getLocale() === 'es')
                    <span class="text-xs font-bold text-amber-500">ES</span>
                    <span class="text-zinc-600 text-xs">/</span>
                    <a href="{{ route('landing.en') }}" class="text-xs text-zinc-400 hover:text-white transition-colors">EN</a>
                @else
                    <a href="{{ route('landing') }}" class="text-xs text-zinc-400 hover:text-white transition-colors">ES</a>
                    <span class="text-zinc-600 text-xs">/</span>
                    <span class="text-xs font-bold text-amber-500">EN</span>
                @endif
            </div>
        </div>

        <a href="#comparison" class="block text-sm font-medium text-zinc-300 hover:text-white py-2.5 mobile-nav-link border-b border-zinc-900">{{ __('landing.nav.comparison') }}</a>
        <a href="#features" class="block text-sm font-medium text-zinc-300 hover:text-white py-2.5 mobile-nav-link border-b border-zinc-900">{{ __('landing.nav.features') }}</a>
        <a href="#audience" class="block text-sm font-medium text-zinc-300 hover:text-white py-2.5 mobile-nav-link border-b border-zinc-900">{{ __('landing.nav.audience') }}</a>
        <a href="#security" class="block text-sm font-medium text-zinc-300 hover:text-white py-2.5 mobile-nav-link border-b border-zinc-900">{{ __('landing.nav.security') }}</a>
        <a href="#faq" class="block text-sm font-medium text-zinc-300 hover:text-white py-2.5 mobile-nav-link border-b border-zinc-900">{{ __('landing.nav.faq') }}</a>

        <div class="pt-4 mt-2 flex flex-col gap-2.5">
            @auth
                <a href="/app" class="w-full text-center py-2.5 px-4 text-sm font-semibold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-xl transition-all shadow-md">
                    {{ __('landing.nav.dashboard') }}
                </a>
            @else
                <a href="{{ route('socialite.redirect', 'google') }}" class="w-full text-center py-2.5 px-4 text-sm font-semibold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    {{ __('landing.nav.get_started') }}
                </a>
                <a href="/app/login" class="w-full text-center py-2 px-4 text-xs font-medium text-zinc-400 hover:text-white">
                    {{ __('landing.nav.login') }}
                </a>
            @endauth
        </div>
    </div>
</nav>
