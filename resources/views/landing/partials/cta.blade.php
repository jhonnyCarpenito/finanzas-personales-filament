<section class="py-24 relative z-10 overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-gradient-to-b from-zinc-900 to-zinc-950 rounded-2xl sm:rounded-3xl border border-zinc-800 p-6 sm:p-12 lg:p-14 text-center gs-reveal overflow-hidden shadow-2xl">
            <!-- Glow effect in background -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-amber-500/10 rounded-full blur-[100px] pointer-events-none"></div>

            <h2 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4 sm:mb-5 relative z-10">
                {{ __('landing.cta.title') }}
            </h2>
            <p class="text-base sm:text-lg text-zinc-400 max-w-xl mx-auto mb-8 relative z-10 leading-relaxed">
                {{ __('landing.cta.subtitle') }}
            </p>
            
            <div class="flex flex-col items-center justify-center gap-3 relative z-10">
                @auth
                    <a href="/app" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 sm:py-4 text-base font-bold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-xl sm:rounded-2xl transition-all shadow-[0_0_25px_rgba(245,158,11,0.3)] hover:shadow-[0_0_35px_rgba(245,158,11,0.5)] hover:-translate-y-0.5">
                        {{ __('landing.nav.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('socialite.redirect', 'google') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-3.5 sm:py-4 text-base font-bold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-xl sm:rounded-2xl transition-all shadow-[0_0_25px_rgba(245,158,11,0.3)] hover:shadow-[0_0_35px_rgba(245,158,11,0.5)] hover:-translate-y-0.5">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        {{ __('landing.cta.button') }}
                    </a>
                @endauth

                <span class="text-xs text-zinc-500 font-medium">
                    {{ __('landing.cta.badge') }}
                </span>
            </div>
        </div>
    </div>
</section>
