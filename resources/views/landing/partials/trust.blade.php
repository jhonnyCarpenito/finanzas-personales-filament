<section class="py-20 relative z-10 bg-zinc-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-b from-zinc-900/90 to-zinc-900/40 rounded-2xl sm:rounded-3xl p-6 sm:p-10 lg:p-12 border border-amber-500/20 shadow-xl gs-reveal relative overflow-hidden">
            <!-- Subtle accent glow -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <!-- Transparency Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold uppercase tracking-wider mb-5">
                    {{ __('landing.trust.badge') }}
                </div>

                <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white tracking-tight mb-5 sm:mb-6">
                    {{ __('landing.trust.title') }}
                </h3>

                <!-- Quote Block -->
                <blockquote class="text-sm sm:text-base lg:text-lg text-zinc-300 font-normal leading-relaxed italic border-l-2 border-amber-500/60 pl-4 sm:pl-5 mb-6 sm:mb-8">
                    {{ __('landing.trust.quote') }}
                </blockquote>

                <!-- Author Signature & Technical Badges -->
                <div class="pt-6 border-t border-zinc-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold text-sm shrink-0">
                            FP
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ __('landing.trust.author') }}</p>
                            <p class="text-xs text-zinc-400">Software Engineer & Creator</p>
                        </div>
                    </div>

                    <!-- Tech & Privacy Pills -->
                    <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-zinc-950/80 border border-zinc-800 text-xs text-zinc-300">
                            <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            {{ __('landing.trust.pills.laravel') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-zinc-950/80 border border-zinc-800 text-xs text-zinc-300">
                            <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            {{ __('landing.trust.pills.no_trackers') }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-zinc-950/80 border border-zinc-800 text-xs text-zinc-300">
                            <svg class="w-3.5 h-3.5 text-cyan-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            {{ __('landing.trust.pills.isolated') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
