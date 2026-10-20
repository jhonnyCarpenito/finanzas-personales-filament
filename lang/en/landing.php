<?php

return [
    'meta' => [
        'title' => 'Personal Finance - All your money in one clean screen',
        'description' => 'Log your daily income and expenses, organize your accounts, and track your net worth in a private and clear dashboard.',
    ],
    'nav' => [
        'comparison' => 'Comparison',
        'features' => 'Features',
        'audience' => 'Who is this for?',
        'security' => 'Privacy',
        'faq' => 'FAQ',
        'login' => 'Log in',
        'get_started' => 'Get started free',
        'dashboard' => 'Go to panel',
    ],
    'hero' => [
        'badge' => 'Expense tracking & net worth',
        'title' => 'All your money in one clean screen.',
        'title_highlight' => 'No broken spreadsheets.',
        'subtitle' => 'Organize bank accounts, cash, and digital wallets in a fast and 100% private dashboard. Understand your spending in seconds from your computer or phone.',
        'cta' => 'Get started free with Google',
        'microcopy' => 'No credit card required · 5-second setup · Zero passwords to remember',
        'mockup' => [
            'balance' => 'Total Net Worth',
            'income' => 'Monthly Income',
            'expense' => 'Monthly Expenses',
            'monthly_trend' => 'Monthly Trend',
            'recent_transactions' => 'Recent Transactions',
            'time_badge' => 'Logged in seconds',
            'tab_transactions' => 'Live Transactions',
            'tab_chart' => 'Monthly Trend',
        ]
    ],
    'comparison' => [
        'badge' => 'Comparison',
        'title' => 'Why leave spreadsheets and traditional banking apps behind?',
        'subtitle' => 'Managing your daily finances should be fast, clear, and without compromising your banking credentials.',
        'cards' => [
            'spreadsheet' => [
                'tag' => 'The traditional way',
                'title' => 'Excel / Spreadsheets',
                'points' => [
                    'Awkward to open and fill out on a mobile phone.',
                    'Cell formulas and references that easily break.',
                    'Building charts and summaries requires time and manual setup.',
                    'Requires constant discipline to avoid giving up.',
                ],
            ],
            'banking_apps' => [
                'tag' => 'Bank sync apps',
                'title' => 'Connected banking apps',
                'points' => [
                    'Cannot track physical cash or external digital wallets.',
                    'Automatic bank aggregator connections frequently drop.',
                    'Requires sharing your online banking login credentials.',
                    'Frequently pushes ads for loans and credit cards.',
                ],
            ],
            'app' => [
                'tag' => 'This application',
                'title' => 'Personal Finance',
                'points' => [
                    'Quick logging of income and expenses on mobile or desktop.',
                    'Track bank balances, cash, and wallets in one place.',
                    '100% private: we never ask for your bank credentials or PINs.',
                    'Charts, summaries, and monthly balances calculated instantly.',
                ],
            ],
        ],
    ],
    'features' => [
        'badge' => 'Features',
        'title' => 'Designed for how you organize money day-to-day',
        'subtitle' => 'The exact tools you need to maintain clarity over your finances, without bloated features.',
        'bento' => [
            'funds' => [
                'title' => 'Fund origins & total capital',
                'description' => 'Your money is not just in one place. Create origins for bank accounts, physical cash, or wallets and monitor your consolidated capital distribution.',
            ],
            'tags' => [
                'title' => 'Custom color tags',
                'description' => 'Categorize each transaction with custom color badges. See at a glance how much you allocate to food, utilities, housing, or leisure.',
            ],
            'filters' => [
                'title' => 'Quick filters by date and type',
                'description' => 'Find any transaction by filtering by month, year, tag, or movement type (income or expense) without messy spreadsheets.',
            ],
            'history' => [
                'title' => 'Monthly balance history',
                'description' => 'Review your net balance month-over-month. Verify your consolidated income and expenses to check your true savings progress.',
            ],
        ],
    ],
    'audience' => [
        'badge' => 'Clarity & Focus',
        'title' => 'Who is this for (and who is it NOT for)?',
        'subtitle' => 'We are upfront about the purpose of this tool. We do not attempt to be a bloated app for everyone.',
        'for_you' => [
            'tag' => 'Perfect match',
            'title' => 'This app is for you if...',
            'points' => [
                'You have money spread across several places (local banks, cash, crypto wallets, savings) and want to see your consolidated total.',
                'You prefer logging an expense in 5 seconds from your phone over fighting a slow spreadsheet.',
                'You value privacy and refuse to give your bank login credentials to marketing intermediaries.',
                'You want to know exactly how much you saved month-over-month without complex formulas.',
            ],
        ],
        'not_for_you' => [
            'tag' => 'Other alternatives',
            'title' => 'This app is NOT for you if...',
            'points' => [
                'You want an app that automatically syncs 15 bank accounts and sends notifications for every penny.',
                'You want an algorithm to decide your budget or push loan and credit card recommendations.',
                'You need corporate accounting with invoicing, VAT breakdown, and enterprise balance sheets.',
            ],
        ],
    ],
    'trust' => [
        'badge' => 'Transparency',
        'title' => 'Built out of real necessity, not to sell data',
        'quote' => '“I built this tool because no app on the market let me track cash and bank accounts together without asking for my online banking passwords or bombarding me with loan ads. I wanted a fast, clean, and private panel that worked just as smoothly on my phone as on my desktop.”',
        'author' => 'Developer of Personal Finance',
        'pills' => [
            'laravel' => 'Built on Laravel & Filament',
            'no_trackers' => '0 commercial trackers',
            'isolated' => 'Strict database data isolation',
        ],
    ],
    'security' => [
        'badge' => 'Privacy & Control',
        'title' => 'Your data is private and under your control',
        'subtitle' => 'An app designed for peace of mind: no bank access credentials, no data selling.',
        'items' => [
            [
                'title' => 'Zero access to your bank accounts',
                'description' => 'We do not connect to your bank accounts or ask for passwords or PINs. You log your records independently and safely.',
                'icon' => 'shield-check',
            ],
            [
                'title' => 'Secure access with Google',
                'description' => 'Log in via official Google OAuth 2.0 with native support for two-factor authentication (2FA).',
                'icon' => 'key',
            ],
            [
                'title' => 'Your data is strictly yours',
                'description' => 'Strict data isolation per user. We do not sell or share financial profiles with advertisers or third parties.',
                'icon' => 'lock-closed',
            ],
            [
                'title' => 'History always available',
                'description' => 'Browse and inspect your complete history of income, expenses, and monthly balances anytime without paywalls.',
                'icon' => 'arrow-down-tray',
            ],
        ],
    ],
    'faq' => [
        'badge' => 'Common Questions',
        'title' => 'Frequently Asked Questions',
        'subtitle' => 'Clear answers about how the application works.',
        'items' => [
            [
                'question' => 'Do I have to connect my real bank accounts or credit cards?',
                'answer' => 'No, absolutely not. We will never ask for bank passwords or credit card numbers. Personal Finance works by logging your transactions directly, guaranteeing nobody can touch your real money.',
            ],
            [
                'question' => 'Is there any cost or hidden subscription?',
                'answer' => 'You can use the application for free by signing in with your Google account, with no credit card required and no hidden catches.',
            ],
            [
                'question' => 'Can I track physical cash or digital wallets?',
                'answer' => 'Yes. You can create fund origins for your different bank accounts, cash in hand, or wallets, and record your daily income and expenses categorized with custom tags.',
            ],
            [
                'question' => 'Can I use the app from my mobile phone?',
                'answer' => 'Yes. The panel is 100% responsive, so you can open the web app from your phone browser and record an expense or income in seconds.',
            ],
            [
                'question' => 'How does Google authentication work?',
                'answer' => 'We use Google OAuth 2.0 protocol. We never see or store your Google password; authentication is handled and secured directly by Google.',
            ],
        ],
    ],
    'cta' => [
        'title' => 'Start organizing your finances today.',
        'subtitle' => 'Sign in in seconds with your Google account and get clarity on your income and expenses.',
        'button' => 'Get started free with Google',
        'badge' => 'Ready in 5 seconds · No credit card required',
    ],
    'footer' => [
        'tagline' => 'Clean, private, and effortless personal finance management.',
        'rights' => 'All rights reserved.',
        'links' => [
            'privacy' => 'Privacy',
            'support' => 'Support & Contact',
        ],
        'contact_email' => 'support@finanzaspersonales.app',
    ],
];
