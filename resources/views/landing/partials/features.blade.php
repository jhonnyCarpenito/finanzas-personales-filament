<section id="features" class="py-24 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 gs-reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold uppercase tracking-wider mb-4">
                {{ __('landing.features.badge') }}
            </div>
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white mb-6">
                {{ __('landing.features.title') }}
            </h2>
            <p class="text-base sm:text-lg text-zinc-400 leading-relaxed">
                {{ __('landing.features.subtitle') }}
            </p>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            
            <!-- Bento Item 1 (Span 2): Multi-origen de fondos -->
            <div class="lg:col-span-2 bg-zinc-900/40 rounded-2xl sm:rounded-3xl p-5 sm:p-8 border border-zinc-800/80 hover:border-zinc-700/80 transition-all gs-reveal flex flex-col justify-between group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-white mb-3">
                        {{ __('landing.features.bento.funds.title') }}
                    </h3>
                    <p class="text-sm sm:text-base text-zinc-400 leading-relaxed mb-6">
                        {{ __('landing.features.bento.funds.description') }}
                    </p>
                </div>

                <!-- Visual Element: Interactive Accounts Pills -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-zinc-800/60">
                    <div class="bg-zinc-950/70 p-3 rounded-2xl border border-zinc-800">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2 h-2 rounded-full bg-blue-400 shrink-0"></span>
                            <span class="text-[11px] text-zinc-400 truncate">Banco Principal</span>
                        </div>
                        <p class="text-sm font-bold text-white">$6,450</p>
                    </div>

                    <div class="bg-zinc-950/70 p-3 rounded-2xl border border-zinc-800">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
                            <span class="text-[11px] text-zinc-400 truncate">Efectivo Físico</span>
                        </div>
                        <p class="text-sm font-bold text-white">$420</p>
                    </div>

                    <div class="bg-zinc-950/70 p-3 rounded-2xl border border-zinc-800">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                            <span class="text-[11px] text-zinc-400 truncate">MetaMask / Crypto</span>
                        </div>
                        <p class="text-sm font-bold text-white">$3,180</p>
                    </div>

                    <div class="bg-zinc-950/70 p-3 rounded-2xl border border-zinc-800">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span>
                            <span class="text-[11px] text-zinc-400 truncate">Ahorros USD</span>
                        </div>
                        <p class="text-sm font-bold text-white">$4,800</p>
                    </div>
                </div>
            </div>

            <!-- Bento Item 2 (Span 1): Etiquetas con significado -->
            <div class="bg-zinc-900/40 rounded-2xl sm:rounded-3xl p-5 sm:p-8 border border-zinc-800/80 hover:border-zinc-700/80 transition-all gs-reveal flex flex-col justify-between group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">
                        {{ __('landing.features.bento.tags.title') }}
                    </h3>
                    <p class="text-sm text-zinc-400 leading-relaxed mb-6">
                        {{ __('landing.features.bento.tags.description') }}
                    </p>
                </div>

                <!-- Visual Element: Tags Cloud -->
                <div class="flex flex-wrap gap-2 pt-4 border-t border-zinc-800/60">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-300 border border-emerald-500/25">#Salario</span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-rose-500/10 text-rose-300 border border-rose-500/25">#Comida</span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-500/10 text-amber-300 border border-amber-500/25">#Vivienda</span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-cyan-500/10 text-cyan-300 border border-cyan-500/25">#Inversiones</span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-violet-500/10 text-violet-300 border border-violet-500/25">#Servicios</span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-300 border border-zinc-700">#Ocio</span>
                </div>
            </div>

            <!-- Bento Item 3 (Span 1): Filtros quirúrgicos -->
            <div class="bg-zinc-900/40 rounded-2xl sm:rounded-3xl p-5 sm:p-8 border border-zinc-800/80 hover:border-zinc-700/80 transition-all gs-reveal flex flex-col justify-between group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">
                        {{ __('landing.features.bento.filters.title') }}
                    </h3>
                    <p class="text-sm text-zinc-400 leading-relaxed mb-6">
                        {{ __('landing.features.bento.filters.description') }}
                    </p>
                </div>

                <!-- Visual Element: Filter controls snippet -->
                <div class="bg-zinc-950/80 p-3 rounded-2xl border border-zinc-800 space-y-2">
                    <div class="flex items-center justify-between text-xs text-zinc-400 border-b border-zinc-800/80 pb-1.5">
                        <span class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                            Mes: <strong>Octubre 2026</strong>
                        </span>
                        <span class="text-amber-400 font-mono text-[10px]">34 filas</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-zinc-400">
                        <span>Tipo: <strong>Solo Gastos</strong></span>
                        <span class="px-1.5 py-0.5 rounded bg-zinc-800 text-zinc-300 text-[10px]">Activo</span>
                    </div>
                </div>
            </div>

            <!-- Bento Item 4 (Span 2): Historial mensual y evolución -->
            <div class="lg:col-span-2 bg-zinc-900/40 rounded-2xl sm:rounded-3xl p-5 sm:p-8 border border-zinc-800/80 hover:border-zinc-700/80 transition-all gs-reveal flex flex-col justify-between group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-white mb-3">
                        {{ __('landing.features.bento.history.title') }}
                    </h3>
                    <p class="text-sm sm:text-base text-zinc-400 leading-relaxed mb-6">
                        {{ __('landing.features.bento.history.description') }}
                    </p>
                </div>

                <!-- Visual Element: Monthly progression table -->
                <div class="bg-zinc-950/70 rounded-xl sm:rounded-2xl border border-zinc-800 overflow-x-auto">
                    <div class="grid grid-cols-4 px-3 sm:px-4 py-2 text-[10px] sm:text-[11px] font-semibold text-zinc-500 border-b border-zinc-800 bg-zinc-900/50 min-w-[280px]">
                        <span>Periodo</span>
                        <span>Ingresos</span>
                        <span>Gastos</span>
                        <span class="text-right">Ahorro Neto</span>
                    </div>
                    <div class="divide-y divide-zinc-800/60 text-xs min-w-[280px]">
                        <div class="grid grid-cols-4 px-3 sm:px-4 py-2 sm:py-2.5 items-center text-[11px] sm:text-xs text-zinc-300">
                            <span class="font-medium text-white">Octubre</span>
                            <span class="text-emerald-400 font-mono">+$4,200</span>
                            <span class="text-rose-400 font-mono">-$1,680</span>
                            <span class="text-right text-emerald-400 font-bold font-mono">+$2,520</span>
                        </div>
                        <div class="grid grid-cols-4 px-3 sm:px-4 py-2 sm:py-2.5 items-center text-[11px] sm:text-xs text-zinc-400">
                            <span class="font-medium">Septiembre</span>
                            <span class="text-emerald-400/80 font-mono">+$3,850</span>
                            <span class="text-rose-400/80 font-mono">-$1,940</span>
                            <span class="text-right text-emerald-400/80 font-bold font-mono">+$1,910</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
