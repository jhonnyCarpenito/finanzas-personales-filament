<section id="faq" class="py-24 relative z-10 bg-zinc-900/20 border-t border-zinc-900/60">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 gs-reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold uppercase tracking-wider mb-4">
                {{ __('landing.faq.badge') }}
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-4">
                {{ __('landing.faq.title') }}
            </h2>
            <p class="text-base sm:text-lg text-zinc-400">
                {{ __('landing.faq.subtitle') }}
            </p>
        </div>

        <div class="space-y-4 max-w-3xl mx-auto">
            @foreach(__('landing.faq.items') as $index => $item)
                <div class="bg-zinc-900/60 rounded-2xl border border-zinc-800/80 overflow-hidden transition-all duration-200 hover:border-zinc-700/80 faq-item gs-reveal">
                    <button type="button" class="w-full px-4 sm:px-6 py-4 sm:py-5 text-left flex items-center justify-between gap-3 sm:gap-4 focus:outline-none faq-button" aria-expanded="false">
                        <span class="text-sm sm:text-base lg:text-lg font-semibold text-white">{{ $item['question'] }}</span>
                        <div class="w-8 h-8 rounded-xl bg-zinc-800/80 flex items-center justify-center shrink-0 text-zinc-400 faq-icon transition-transform duration-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    
                    <div class="faq-content hidden px-4 sm:px-6 pb-4 sm:pb-6 text-xs sm:text-sm lg:text-base text-zinc-400 leading-relaxed border-t border-zinc-800/40 pt-3.5 sm:pt-4">
                        {{ $item['answer'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
