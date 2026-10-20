import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    // Check for reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Navbar Glass Effect on Scroll
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('glass-active');
            } else {
                navbar.classList.remove('glass-active');
            }
        }, { passive: true });
    }

    // Mobile Navigation Drawer Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const openIcon = document.querySelector('.menu-open-icon');
    const closeIcon = document.querySelector('.menu-close-icon');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('hidden');
            if (isOpen) {
                mobileMenu.classList.add('hidden');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                if (openIcon) openIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
            } else {
                mobileMenu.classList.remove('hidden');
                mobileMenuBtn.setAttribute('aria-expanded', 'true');
                if (openIcon) openIcon.classList.add('hidden');
                if (closeIcon) closeIcon.classList.remove('hidden');
            }
        });

        // Close mobile menu when clicking any link inside it
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                if (openIcon) openIcon.classList.remove('hidden');
                if (closeIcon) closeIcon.classList.add('hidden');
            });
        });
    }

    // FAQ Accordion Handler (Clean, accessible toggle)
    const faqButtons = document.querySelectorAll('.faq-button');
    faqButtons.forEach(button => {
        button.addEventListener('click', () => {
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            const content = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');

            // Close other open items for a neat accordion experience
            faqButtons.forEach(otherBtn => {
                if (otherBtn !== button && otherBtn.getAttribute('aria-expanded') === 'true') {
                    otherBtn.setAttribute('aria-expanded', 'false');
                    const otherContent = otherBtn.nextElementSibling;
                    const otherIcon = otherBtn.querySelector('.faq-icon');
                    if (otherContent) otherContent.classList.add('hidden');
                    if (otherIcon) otherIcon.classList.remove('rotate-180');
                }
            });

            if (isExpanded) {
                button.setAttribute('aria-expanded', 'false');
                if (content) content.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-180');
            } else {
                button.setAttribute('aria-expanded', 'true');
                if (content) content.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-180');
            }
        });
    });

    // Hero Mockup Mobile Tab Switcher (< lg screens)
    const tabBtnTransactions = document.getElementById('tab-btn-transactions');
    const tabBtnChart = document.getElementById('tab-btn-chart');
    const panelFeed = document.getElementById('mockup-panel-feed');
    const panelChart = document.getElementById('mockup-panel-chart');

    if (tabBtnTransactions && tabBtnChart && panelFeed && panelChart) {
        const activeClasses = ['bg-zinc-800/90', 'text-white', 'font-semibold', 'shadow-sm'];
        const inactiveClasses = ['text-zinc-400'];

        tabBtnTransactions.addEventListener('click', () => {
            tabBtnTransactions.classList.add(...activeClasses);
            tabBtnTransactions.classList.remove(...inactiveClasses);
            tabBtnChart.classList.remove(...activeClasses);
            tabBtnChart.classList.add(...inactiveClasses);

            panelFeed.classList.remove('hidden');
            panelFeed.classList.add('flex');
            panelChart.classList.add('hidden');
            panelChart.classList.remove('flex');
        });

        tabBtnChart.addEventListener('click', () => {
            tabBtnChart.classList.add(...activeClasses);
            tabBtnChart.classList.remove(...inactiveClasses);
            tabBtnTransactions.classList.remove(...activeClasses);
            tabBtnTransactions.classList.add(...inactiveClasses);

            panelChart.classList.remove('hidden');
            panelChart.classList.add('flex');
            panelFeed.classList.add('hidden');
            panelFeed.classList.remove('flex');
        });
    }

    if (prefersReducedMotion) {
        // Fallback for reduced motion: show elements without delay
        document.querySelectorAll('.gs-reveal').forEach(el => {
            el.style.opacity = '1';
            el.style.visibility = 'visible';
        });
        
        document.querySelectorAll('.chart-bar').forEach(bar => {
            bar.style.height = bar.dataset.height;
        });

        document.querySelectorAll('.counter').forEach(counter => {
            counter.textContent = counter.dataset.target;
        });
        return;
    }

    // Smooth Scroll Reveal Animations (Subtle & Clean)
    gsap.utils.toArray('.gs-reveal').forEach((elem) => {
        gsap.fromTo(elem, 
            { autoAlpha: 0, y: 20 },
            {
                duration: 0.7,
                autoAlpha: 1,
                y: 0,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: elem,
                    start: "top 90%",
                    toggleActions: "play none none reverse"
                }
            }
        );
    });

    // Mockup Chart Bars Animation (Clean rise into view)
    const mockupContainer = document.getElementById('mockup-container');
    if (mockupContainer) {
        gsap.to('.chart-bar', {
            height: (i, target) => target.dataset.height,
            duration: 1.0,
            stagger: 0.06,
            ease: "power2.out",
            scrollTrigger: {
                trigger: mockupContainer,
                start: "top 80%",
                once: true
            }
        });

        // KPI Counters Animation on initial view
        gsap.utils.toArray('.counter').forEach(counter => {
            const target = parseInt(counter.dataset.target, 10);
            gsap.to(counter, {
                innerText: target,
                duration: 1.5,
                snap: { innerText: 1 },
                ease: "power2.out",
                scrollTrigger: {
                    trigger: mockupContainer,
                    start: "top 80%",
                    once: true
                }
            });
        });
    }

    // Live UI Simulation: Simulated Incoming Transactions (Option A)
    const transactionsFeed = document.getElementById('transactions-feed');
    const heroBalanceEl = document.getElementById('hero-total-balance');
    const heroIncomeEl = document.getElementById('hero-monthly-income');
    const heroExpenseEl = document.getElementById('hero-monthly-expense');

    if (transactionsFeed && heroBalanceEl && !prefersReducedMotion) {
        let currentBalance = 14850;
        let currentIncome = 4200;
        let currentExpense = 1680;
        let poolIndex = 0;

        const simulatedPool = [
            {
                concept: "Proyecto Freelance UI",
                type: "income",
                amount: 650,
                amountFormatted: "+$650.00",
                tag: "Freelance",
                tagColor: "cyan",
            },
            {
                concept: "Gasolina / Transporte",
                type: "expense",
                amount: 38,
                amountFormatted: "-$38.00",
                tag: "Transporte",
                tagColor: "cyan",
            },
            {
                concept: "Cena Restaurante",
                type: "expense",
                amount: 54,
                amountFormatted: "-$54.00",
                tag: "Ocio",
                tagColor: "zinc",
            },
            {
                concept: "Rendimiento Ahorros",
                type: "income",
                amount: 95,
                amountFormatted: "+$95.00",
                tag: "Inversiones",
                tagColor: "cyan",
            },
            {
                concept: "Farmacia / Medicinas",
                type: "expense",
                amount: 28.5,
                amountFormatted: "-$28.50",
                tag: "Salud",
                tagColor: "rose",
            },
            {
                concept: "Bono por Desempeño",
                type: "income",
                amount: 420,
                amountFormatted: "+$420.00",
                tag: "Salario",
                tagColor: "emerald",
            },
            {
                concept: "Supermercado Frutas & Verduras",
                type: "expense",
                amount: 36.4,
                amountFormatted: "-$36.40",
                tag: "Comida",
                tagColor: "rose",
            },
        ];

        function getTagClasses(color) {
            switch (color) {
                case 'emerald':
                    return 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20';
                case 'rose':
                    return 'bg-rose-500/10 text-rose-300 border-rose-500/20';
                case 'amber':
                    return 'bg-amber-500/10 text-amber-300 border-amber-500/20';
                case 'cyan':
                    return 'bg-cyan-500/10 text-cyan-300 border-cyan-500/20';
                default:
                    return 'bg-zinc-800 text-zinc-300 border-zinc-700';
            }
        }

        function createTransactionElement(tx) {
            const el = document.createElement('div');
            el.className = 'flex items-center justify-between p-2.5 rounded-xl bg-zinc-900/70 border border-zinc-800/70 hover:border-zinc-700/80 transition-colors opacity-0 overflow-hidden';
            
            const isIncome = tx.type === 'income';
            const iconBg = isIncome ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400';
            const amountColor = isIncome ? 'text-emerald-400' : 'text-rose-400';
            const iconSvg = isIncome
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>';

            const tagCls = getTagClasses(tx.tagColor);

            el.innerHTML = `
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-lg ${iconBg} border flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            ${iconSvg}
                        </svg>
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-medium text-white truncate">${tx.concept}</p>
                        <p class="text-[10px] text-zinc-400">Hace unos segundos</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs font-bold ${amountColor}">${tx.amountFormatted}</p>
                    <span class="inline-block text-[9px] px-1.5 py-0.5 rounded border ${tagCls}">${tx.tag}</span>
                </div>
            `;
            return el;
        }

        function triggerLiveTransaction() {
            const tx = simulatedPool[poolIndex];
            poolIndex = (poolIndex + 1) % simulatedPool.length;

            // 1. Update balances smoothly with GSAP
            if (tx.type === 'income') {
                currentBalance += tx.amount;
                currentIncome += tx.amount;
                if (heroIncomeEl) {
                    gsap.to(heroIncomeEl, {
                        innerText: Math.round(currentIncome),
                        duration: 0.9,
                        snap: { innerText: 1 },
                        ease: "power2.out"
                    });
                }
            } else {
                currentBalance -= tx.amount;
                currentExpense += tx.amount;
                if (heroExpenseEl) {
                    gsap.to(heroExpenseEl, {
                        innerText: Math.round(currentExpense),
                        duration: 0.9,
                        snap: { innerText: 1 },
                        ease: "power2.out"
                    });
                }
            }

            if (heroBalanceEl) {
                gsap.to(heroBalanceEl, {
                    innerText: Math.round(currentBalance),
                    duration: 0.9,
                    snap: { innerText: 1 },
                    ease: "power2.out"
                });
            }

            // 2. Prepend new transaction card
            const newCard = createTransactionElement(tx);
            transactionsFeed.prepend(newCard);

            // Animate card entrance smoothly
            gsap.fromTo(newCard, 
                { height: 0, opacity: 0, y: -10, paddingTop: 0, paddingBottom: 0 },
                { 
                    height: "auto", 
                    opacity: 1, 
                    y: 0, 
                    paddingTop: 10, 
                    paddingBottom: 10, 
                    duration: 0.5, 
                    ease: "power2.out",
                    onComplete: () => {
                        // Subtle highlight pulse border
                        const pulseBorder = tx.type === 'income' ? 'border-emerald-500/50' : 'border-amber-500/50';
                        newCard.classList.add(pulseBorder);
                        setTimeout(() => {
                            newCard.classList.remove(pulseBorder);
                        }, 1200);
                    }
                }
            );

            // 3. Remove bottom-most cards keeping exactly 4 items
            const cards = transactionsFeed.querySelectorAll(':scope > div');
            if (cards.length > 4) {
                for (let i = 4; i < cards.length; i++) {
                    const cardToRemove = cards[i];
                    gsap.to(cardToRemove, {
                        opacity: 0,
                        height: 0,
                        paddingTop: 0,
                        paddingBottom: 0,
                        marginTop: 0,
                        marginBottom: 0,
                        duration: 0.4,
                        ease: "power2.in",
                        onComplete: () => cardToRemove.remove()
                    });
                }
            }
        }

        // Automatic interval with IntersectionObserver (pauses when user scrolls away)
        let liveInterval = null;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (!liveInterval) {
                        liveInterval = setInterval(triggerLiveTransaction, 4200);
                    }
                } else {
                    if (liveInterval) {
                        clearInterval(liveInterval);
                        liveInterval = null;
                    }
                }
            });
        }, { threshold: 0.2 });

        observer.observe(mockupContainer || transactionsFeed);
    }
});
