<section class="relative pt-28 pb-16 sm:pt-32 sm:pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-3xl mx-auto gs-reveal">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/25 text-amber-400 text-xs font-semibold uppercase tracking-wider mb-6">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                {{ __('landing.hero.badge') }}
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight text-white mb-6 leading-[1.1]">
                {{ __('landing.hero.title') }}
                <span class="block bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 bg-clip-text text-transparent mt-1">
                    {{ __('landing.hero.title_highlight') }}
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg lg:text-xl text-zinc-300 mb-8 max-w-2xl mx-auto leading-relaxed">
                {{ __('landing.hero.subtitle') }}
            </p>
            
            <!-- CTA Group -->
            <div class="flex flex-col items-center justify-center gap-3">
                @auth
                    <a href="/app" class="w-full sm:w-auto px-8 py-3.5 text-base font-semibold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-xl transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_30px_rgba(245,158,11,0.5)] hover:-translate-y-0.5">
                        {{ __('landing.nav.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('socialite.redirect', 'google') }}" class="w-full sm:w-auto px-8 py-3.5 text-base font-semibold bg-amber-500 hover:bg-amber-400 text-amber-950 rounded-xl transition-all shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_30px_rgba(245,158,11,0.5)] hover:-translate-y-0.5 flex items-center justify-center gap-2.5">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        {{ __('landing.hero.cta') }}
                    </a>
                @endauth
                
                <p class="text-xs sm:text-sm text-zinc-400 mt-2.5 text-center max-w-lg mx-auto leading-relaxed">
                    <svg class="w-4 h-4 text-emerald-400 inline-block align-text-bottom mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg><span>{{ __('landing.hero.microcopy') }}</span>
                </p>
            </div>
        </div>

        <!-- Realistic Product Mockup (Filament Dashboard Look) -->
        <div class="mt-12 sm:mt-16 relative gs-reveal" id="mockup-container">
            <div class="absolute -inset-1 bg-gradient-to-r from-amber-500/15 via-amber-600/10 to-amber-500/15 rounded-3xl blur-xl opacity-40"></div>
            
            <div class="relative bg-zinc-900/95 backdrop-blur-xl rounded-2xl sm:rounded-3xl border border-zinc-800 shadow-2xl overflow-hidden transition-all duration-300 hover:border-zinc-700/80">
                <!-- Browser Header / Navigation Bar -->
                <div class="flex items-center justify-between px-4 sm:px-6 py-3.5 border-b border-zinc-800 bg-zinc-950/70">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500/70"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500/70"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500/70"></div>
                        <span class="ml-3 text-xs text-zinc-400 font-mono hidden sm:inline-block">finanzas-personales / app / dashboard</span>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 bg-zinc-900 px-3 py-1 rounded-lg border border-zinc-800 text-xs text-zinc-300">
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Año {{ date('Y') }}</span>
                        </div>
                        <div class="w-7 h-7 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center text-xs font-bold">
                            FP
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6 lg:p-8 space-y-6">
                    <!-- KPI Stats Row -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                        <!-- Patrimonio Total (Capital Total) -->
                        <div class="col-span-2 sm:col-span-1 bg-zinc-950/70 p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-zinc-800 hover:border-zinc-700 transition-colors">
                            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                <span class="text-xs sm:text-sm text-zinc-400 font-medium">{{ __('landing.hero.mockup.balance') }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-semibold">+12.4%</span>
                            </div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-white flex items-baseline gap-1">
                                $<span class="counter" id="hero-total-balance" data-target="14850">0</span>
                                <span class="text-xs sm:text-sm font-normal text-zinc-500">.00</span>
                            </div>
                        </div>

                        <!-- Ingresos del mes -->
                        <div class="col-span-1 bg-zinc-950/70 p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-zinc-800 hover:border-zinc-700 transition-colors">
                            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                <span class="text-xs sm:text-sm text-zinc-400 font-medium truncate">{{ __('landing.hero.mockup.income') }}</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
                            </div>
                            <div class="text-xl sm:text-3xl font-extrabold text-emerald-400 flex items-baseline gap-0.5 sm:gap-1">
                                +$<span class="counter" id="hero-monthly-income" data-target="4200">0</span>
                                <span class="text-xs sm:text-sm font-normal text-emerald-500/70">.00</span>
                            </div>
                        </div>

                        <!-- Gastos del mes -->
                        <div class="col-span-1 bg-zinc-950/70 p-3.5 sm:p-5 rounded-xl sm:rounded-2xl border border-zinc-800 hover:border-zinc-700 transition-colors">
                            <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                                <span class="text-xs sm:text-sm text-zinc-400 font-medium truncate">{{ __('landing.hero.mockup.expense') }}</span>
                                <span class="w-2 h-2 rounded-full bg-rose-400 shrink-0"></span>
                            </div>
                            <div class="text-xl sm:text-3xl font-extrabold text-rose-400 flex items-baseline gap-0.5 sm:gap-1">
                                -$<span class="counter" id="hero-monthly-expense" data-target="1680">0</span>
                                <span class="text-xs sm:text-sm font-normal text-rose-500/70">.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile View Switcher (< lg screens) -->
                    <div class="flex lg:hidden bg-zinc-950/80 p-1 rounded-xl border border-zinc-800 text-xs font-medium">
                        <button type="button" id="tab-btn-transactions" class="flex-1 py-2 rounded-lg bg-zinc-800/90 text-white font-semibold shadow-sm transition-all text-center">
                            {{ __('landing.hero.mockup.tab_transactions') }}
                        </button>
                        <button type="button" id="tab-btn-chart" class="flex-1 py-2 rounded-lg text-zinc-400 hover:text-white transition-all text-center">
                            {{ __('landing.hero.mockup.tab_chart') }}
                        </button>
                    </div>

                    <!-- Split Panel: Chart + Transactions Feed -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                        <!-- Chart Widget -->
                        <div id="mockup-panel-chart" class="hidden lg:flex lg:col-span-7 bg-zinc-950/50 p-4 sm:p-5 rounded-2xl border border-zinc-800 flex-col justify-between">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-white">{{ __('landing.hero.mockup.monthly_trend') }}</h4>
                                    <p class="text-xs text-zinc-400">Ingresos vs Gastos (Últimos 6 meses)</p>
                                </div>
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="flex items-center gap-1.5 text-zinc-300">
                                        <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span> Ingresos
                                    </span>
                                    <span class="flex items-center gap-1.5 text-zinc-300">
                                        <span class="w-2.5 h-2.5 rounded-sm bg-rose-500"></span> Gastos
                                    </span>
                                </div>
                            </div>

                            <!-- Bar Chart Visualizer -->
                            <div class="h-44 flex items-end justify-between gap-1.5 sm:gap-3 pt-4 border-b border-zinc-800/80 pb-2">
                                <!-- Mes 1 -->
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full flex items-end justify-center gap-1 h-32">
                                        <div class="chart-bar w-2 sm:w-3.5 bg-emerald-500/80 rounded-t-sm" style="height: 0%" data-height="65%"></div>
                                        <div class="chart-bar w-2 sm:w-3.5 bg-rose-500/70 rounded-t-sm" style="height: 0%" data-height="40%"></div>
                                    </div>
                                    <span class="text-[10px] text-zinc-400 font-medium">May</span>
                                </div>
                                <!-- Mes 2 -->
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full flex items-end justify-center gap-1 h-32">
                                        <div class="chart-bar w-2 sm:w-3.5 bg-emerald-500/80 rounded-t-sm" style="height: 0%" data-height="75%"></div>
                                        <div class="chart-bar w-2 sm:w-3.5 bg-rose-500/70 rounded-t-sm" style="height: 0%" data-height="48%"></div>
                                    </div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Jun</span>
                                </div>
                                <!-- Mes 3 -->
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full flex items-end justify-center gap-1 h-32">
                                        <div class="chart-bar w-2 sm:w-3.5 bg-emerald-500/80 rounded-t-sm" style="height: 0%" data-height="70%"></div>
                                        <div class="chart-bar w-2 sm:w-3.5 bg-rose-500/70 rounded-t-sm" style="height: 0%" data-height="52%"></div>
                                    </div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Jul</span>
                                </div>
                                <!-- Mes 4 -->
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full flex items-end justify-center gap-1 h-32">
                                        <div class="chart-bar w-2 sm:w-3.5 bg-emerald-500/80 rounded-t-sm" style="height: 0%" data-height="85%"></div>
                                        <div class="chart-bar w-2 sm:w-3.5 bg-rose-500/70 rounded-t-sm" style="height: 0%" data-height="45%"></div>
                                    </div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Ago</span>
                                </div>
                                <!-- Mes 5 -->
                                <div class="flex-1 flex flex-col items-center gap-1">
                                    <div class="w-full flex items-end justify-center gap-1 h-32">
                                        <div class="chart-bar w-2 sm:w-3.5 bg-emerald-500/80 rounded-t-sm" style="height: 0%" data-height="80%"></div>
                                        <div class="chart-bar w-2 sm:w-3.5 bg-rose-500/70 rounded-t-sm" style="height: 0%" data-height="38%"></div>
                                    </div>
                                    <span class="text-[10px] text-zinc-400 font-medium">Sep</span>
                                </div>
                                <!-- Mes 6 (Actual) -->
                                <div class="flex-1 flex flex-col items-center gap-1 bg-amber-500/5 rounded-lg py-1 border border-amber-500/20">
                                    <div class="w-full flex items-end justify-center gap-1 h-32">
                                        <div class="chart-bar w-2 sm:w-3.5 bg-emerald-400 rounded-t-sm shadow-sm" style="height: 0%" data-height="95%"></div>
                                        <div class="chart-bar w-2 sm:w-3.5 bg-rose-400 rounded-t-sm shadow-sm" style="height: 0%" data-height="42%"></div>
                                    </div>
                                    <span class="text-[10px] text-amber-400 font-bold">Oct</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Transactions Feed -->
                        <div id="mockup-panel-feed" class="flex lg:flex lg:col-span-5 bg-zinc-950/50 p-4 sm:p-5 rounded-2xl border border-zinc-800 flex-col justify-between">
                            <div class="flex items-center justify-between gap-2 flex-wrap mb-3">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-semibold text-white whitespace-nowrap">{{ __('landing.hero.mockup.recent_transactions') }}</h4>
                                    <span class="inline-flex items-center gap-1.5 text-[10px] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20 font-medium whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        En vivo
                                    </span>
                                </div>
                                <span class="text-[11px] text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/20 font-medium whitespace-nowrap">
                                    {{ __('landing.hero.mockup.time_badge') }}
                                </span>
                            </div>

                            <div class="space-y-2.5 overflow-hidden min-h-[250px] relative" id="transactions-feed">
                                <!-- Transaction 1 -->
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-zinc-900/70 border border-zinc-800/70 hover:border-zinc-700/80 transition-colors">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                            </svg>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-medium text-white truncate">Nómina Empresa</p>
                                            <p class="text-[10px] text-zinc-400">19/10/{{ date('Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold text-emerald-400">+$3,200.00</p>
                                        <span class="inline-block text-[9px] px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">Salario</span>
                                    </div>
                                </div>

                                <!-- Transaction 2 -->
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-zinc-900/70 border border-zinc-800/70 hover:border-zinc-700/80 transition-colors">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                            </svg>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-medium text-white truncate">Supermercado Mercadona</p>
                                            <p class="text-[10px] text-zinc-400">18/10/{{ date('Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold text-rose-400">-$142.50</p>
                                        <span class="inline-block text-[9px] px-1.5 py-0.5 rounded bg-rose-500/10 text-rose-300 border border-rose-500/20">Comida</span>
                                    </div>
                                </div>

                                <!-- Transaction 3 -->
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-zinc-900/70 border border-zinc-800/70 hover:border-zinc-700/80 transition-colors">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                            </svg>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-medium text-white truncate">Servicio Cloud & Hosting</p>
                                            <p class="text-[10px] text-zinc-400">15/10/{{ date('Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold text-rose-400">-$45.00</p>
                                        <span class="inline-block text-[9px] px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-300 border border-amber-500/20">Servicios</span>
                                    </div>
                                </div>

                                <!-- Transaction 4 -->
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-zinc-900/70 border border-zinc-800/70 hover:border-zinc-700/80 transition-colors">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-medium text-white truncate">Rendimiento Inversión</p>
                                            <p class="text-[10px] text-zinc-400">14/10/{{ date('Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-xs font-bold text-cyan-400">+$85.00</p>
                                        <span class="inline-block text-[9px] px-1.5 py-0.5 rounded bg-cyan-500/10 text-cyan-300 border border-cyan-500/20">Inversiones</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
