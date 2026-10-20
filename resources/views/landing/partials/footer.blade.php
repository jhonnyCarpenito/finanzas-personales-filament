<footer class="border-t border-zinc-900 bg-zinc-950 py-10 sm:py-12 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-5 sm:gap-6 text-center md:text-left">
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2.5">
                @include('landing.partials.logo', ['class' => 'w-5 h-5 text-amber-500 opacity-80 shrink-0'])
                <span class="text-zinc-400 text-sm font-semibold">Finanzas Personales</span>
                <span class="text-zinc-700 hidden sm:inline">·</span>
                <span class="text-zinc-500 text-xs hidden sm:inline">{{ __('landing.footer.tagline') }}</span>
            </div>
            
            <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-xs text-zinc-400">
                <a href="#security" class="hover:text-white transition-colors">
                    {{ __('landing.footer.links.privacy') }}
                </a>
                <span class="text-zinc-800">·</span>
                <a href="mailto:{{ __('landing.footer.contact_email') }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-zinc-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ __('landing.footer.links.support') }}
                </a>
            </div>

            <p class="text-zinc-600 text-xs text-center md:text-right">
                &copy; {{ date('Y') }} Finanzas Personales. {{ __('landing.footer.rights') }}
            </p>
        </div>
    </div>
</footer>
