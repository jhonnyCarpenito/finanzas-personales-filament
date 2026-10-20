<section id="comparison" class="py-24 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 gs-reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold uppercase tracking-wider mb-4">
                {{ __('landing.comparison.badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-6">
                {{ __('landing.comparison.title') }}
            </h2>
            <p class="text-base sm:text-lg text-zinc-300 leading-relaxed">
                {{ __('landing.comparison.subtitle') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch max-w-6xl mx-auto">
            <!-- 1. Excel / Hojas de cálculo -->
            <div class="bg-zinc-900/50 rounded-2xl sm:rounded-3xl px-5 py-6 sm:p-8 border border-zinc-800/80 flex flex-col justify-between gs-reveal">
                <div>
                    <div class="inline-block px-3 py-1 rounded-lg bg-zinc-800 text-zinc-400 text-xs font-medium mb-4">
                        {{ __('landing.comparison.cards.spreadsheet.tag') }}
                    </div>
                    <h3 class="text-xl font-bold text-white mb-6">{{ __('landing.comparison.cards.spreadsheet.title') }}</h3>
                    
                    <ul class="space-y-4 pr-3 sm:pr-0">
                        @foreach(__('landing.comparison.cards.spreadsheet.points') as $point)
                            <li class="flex items-start gap-2.5 sm:gap-3 text-sm text-zinc-300">
                                <span class="w-5 h-5 rounded-full bg-rose-500/10 text-rose-400 flex items-center justify-center shrink-0 mt-0.5 border border-rose-500/20 text-xs">✕</span>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- 2. Apps bancarias tradicionales -->
            <div class="bg-zinc-900/50 rounded-2xl sm:rounded-3xl px-5 py-6 sm:p-8 border border-zinc-800/80 flex flex-col justify-between gs-reveal">
                <div>
                    <div class="inline-block px-3 py-1 rounded-lg bg-zinc-800 text-zinc-400 text-xs font-medium mb-4">
                        {{ __('landing.comparison.cards.banking_apps.tag') }}
                    </div>
                    <h3 class="text-xl font-bold text-white mb-6">{{ __('landing.comparison.cards.banking_apps.title') }}</h3>
                    
                    <ul class="space-y-4 pr-3 sm:pr-0">
                        @foreach(__('landing.comparison.cards.banking_apps.points') as $point)
                            <li class="flex items-start gap-2.5 sm:gap-3 text-sm text-zinc-300">
                                <span class="w-5 h-5 rounded-full bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0 mt-0.5 border border-amber-500/20 text-xs">✕</span>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- 3. Finanzas Personales (Highlighted) -->
            <div class="relative bg-gradient-to-b from-zinc-900 via-zinc-900/95 to-zinc-950 rounded-2xl sm:rounded-3xl px-5 py-6 sm:p-8 border-2 border-amber-500/40 shadow-xl shadow-amber-500/5 flex flex-col justify-between gs-reveal">
                <!-- Highlight badge -->
                <div class="absolute -top-3.5 right-6 px-3 py-0.5 rounded-full bg-amber-500 text-amber-950 text-xs font-bold uppercase tracking-wider shadow">
                    {{ app()->getLocale() === 'en' ? 'Recommended' : 'Recomendado' }}
                </div>

                <div>
                    <div class="inline-block px-3 py-1 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 text-xs font-semibold mb-4">
                        {{ __('landing.comparison.cards.app.tag') }}
                    </div>
                    <h3 class="text-xl font-bold text-white mb-6">{{ __('landing.comparison.cards.app.title') }}</h3>
                    
                    <ul class="space-y-4 pr-3 sm:pr-0">
                        @foreach(__('landing.comparison.cards.app.points') as $point)
                            <li class="flex items-start gap-2.5 sm:gap-3 text-sm text-zinc-200">
                                <span class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 mt-0.5 border border-emerald-500/40 font-bold text-xs">✓</span>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-zinc-800">
                    <a href="{{ route('socialite.redirect', 'google') }}" class="block text-center w-full py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-amber-950 font-semibold text-sm transition-all shadow-md">
                        {{ __('landing.hero.cta') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
