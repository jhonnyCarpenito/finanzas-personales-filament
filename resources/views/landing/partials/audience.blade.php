<section id="audience" class="py-24 relative z-10 bg-zinc-900/30 border-y border-zinc-900/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20 gs-reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold uppercase tracking-wider mb-4">
                {{ __('landing.audience.badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4">
                {{ __('landing.audience.title') }}
            </h2>
            <p class="text-base sm:text-lg text-zinc-400">
                {{ __('landing.audience.subtitle') }}
            </p>
        </div>

        <!-- 2 Columns Comparison Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 max-w-5xl mx-auto items-stretch">
            <!-- Columna 1: Ideal para ti -->
            <div class="bg-zinc-900/60 backdrop-blur-sm rounded-2xl sm:rounded-3xl px-5 py-6 sm:p-8 border border-emerald-500/30 hover:border-emerald-500/50 transition-all gs-reveal flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        {{ __('landing.audience.for_you.tag') }}
                    </div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-white mb-6">
                        {{ __('landing.audience.for_you.title') }}
                    </h3>
                    <ul class="space-y-4 pr-4 sm:pr-0">
                        @foreach(__('landing.audience.for_you.points') as $point)
                            <li class="flex items-start gap-2.5 sm:gap-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="text-sm text-zinc-300 leading-relaxed">{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Columna 2: Otras alternativas / No es para ti -->
            <div class="bg-zinc-900/60 backdrop-blur-sm rounded-2xl sm:rounded-3xl px-5 py-6 sm:p-8 border border-zinc-800 hover:border-zinc-700 transition-all gs-reveal flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-zinc-800 text-zinc-400 border border-zinc-700 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                        {{ __('landing.audience.not_for_you.tag') }}
                    </div>
                    <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-zinc-200 mb-6">
                        {{ __('landing.audience.not_for_you.title') }}
                    </h3>
                    <ul class="space-y-4 pr-4 sm:pr-0">
                        @foreach(__('landing.audience.not_for_you.points') as $point)
                            <li class="flex items-start gap-2.5 sm:gap-3">
                                <div class="w-5 h-5 rounded-full bg-rose-500/10 text-rose-400/80 border border-rose-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <span class="text-sm text-zinc-400 leading-relaxed">{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
