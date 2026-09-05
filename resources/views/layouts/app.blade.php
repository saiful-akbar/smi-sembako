<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    
    <title>@yield('title', config('app.name'))</title>
    
    {{-- Material icons --}}
    <link href="{{ asset('fonts/material-icons/material-icons.css') }}" rel="stylesheet">
    
    {{-- ALpine JS --}}
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>

    {{-- CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Touch optimization untuk mobile */
        @media (max-width: 768px) {
            * {
                -webkit-tap-highlight-color: transparent;
            }

            button,
            a {
                touch-action: manipulation;
            }
        }

        /* Dynamic theme helpers - Improved dark mode support */
        .dark {
            color-scheme: dark;
        }

        .dark .bg-white\/70 {
            background-color: rgba(15, 23, 42, 0.7) !important;
        }

        .dark .bg-white\/95 {
            background-color: rgba(15, 23, 42, 0.95) !important;
        }

        .dark .bg-white\/80 {
            background-color: rgba(15, 23, 42, 0.8) !important;
        }

        .dark .bg-white {
            background-color: #0f172a !important;
        }

        .dark .text-slate-900 {
            color: #e2e8f0 !important;
        }

        .dark .text-slate-700 {
            color: #cbd5e1 !important;
        }

        .dark .text-slate-600 {
            color: #94a3b8 !important;
        }

        .dark .text-slate-500 {
            color: #94a3b8 !important;
        }

        .dark .border-slate-200\/60 {
            border-color: rgba(51, 65, 85, 0.6) !important;
        }

        .dark .border-slate-200 {
            border-color: #334155 !important;
        }

        .dark .hover\:bg-slate-100:hover {
            background-color: rgba(51, 65, 85, 0.5) !important;
        }

        .dark .hover\:bg-slate-50:hover {
            background-color: rgba(51, 65, 85, 0.3) !important;
        }

        .dark .bg-slate-50 {
            background-color: #1e293b !important;
        }

        .dark .bg-slate-100 {
            background-color: #334155 !important;
        }

        .dark .shadow-slate-500\/20 {
            --tw-shadow-color: rgba(51, 65, 85, 0.2);
            --tw-shadow: var(--tw-shadow-colored);
        }

        .dark .hover\:bg-gradient-to-r:hover {
            filter: brightness(1.1);
        }

        /* Performance: reduce motion/filters when user prefers or when .reduce-motion is present */
        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }

        .reduce-motion *,
        .reduce-motion *::before,
        .reduce-motion *::after {
            animation: none !important;
            transition: none !important;
        }

        .reduce-motion .animate-gradient,
        .reduce-motion .animate-fade-in,
        .reduce-motion .animate-fade-in-up,
        .reduce-motion .animate-bounce-slow,
        .reduce-motion .animate-pulse-slow,
        .reduce-motion .animate-wiggle,
        .reduce-motion .animate-scale-in {
            animation: none !important;
        }

        .reduce-motion .decorative {
            display: none !important;
        }

        .reduce-motion .blur-xl,
        .reduce-motion .backdrop-blur,
        .reduce-motion .backdrop-blur-xl,
        .reduce-motion .backdrop-blur-sm {
            filter: none !important;
            -webkit-backdrop-filter: none !important;
            backdrop-filter: none !important;
        }

        /* Browser compatibility fallbacks for backdrop-filter */
        @supports not (backdrop-filter: blur(10px)) {
            .backdrop-blur-xl {
                background-color: rgba(255, 255, 255, 0.9) !important;
            }

            .backdrop-blur-sm {
                background-color: rgba(255, 255, 255, 0.8) !important;
            }

            .dark .backdrop-blur-xl {
                background-color: rgba(15, 23, 42, 0.9) !important;
            }

            .dark .backdrop-blur-sm {
                background-color: rgba(15, 23, 42, 0.8) !important;
            }
        }

        /* Additional performance optimizations */
        @media (max-width: 768px) {
            .animate-gradient {
                animation-duration: 40s !important;
                /* Reduce animation speed on mobile */
            }

            .backdrop-blur-xl,
            .backdrop-blur-sm {
                -webkit-backdrop-filter: blur(4px) !important;
                backdrop-filter: blur(4px) !important;
            }

            .transition-all {
                transition-duration: 0.2s !important;
            }
        }

        /* GPU acceleration for better performance */
        .animate-fade-in,
        .animate-scale-in,
        .animate-slide-in {
            will-change: transform, opacity;
        }

        .backdrop-blur-xl,
        .backdrop-blur-sm {
            will-change: backdrop-filter;
        }

        /* Reduce motion for low-performance devices */
        @media (max-resolution: 1dppx) and (max-width: 1024px) {
            .animate-gradient {
                animation: none !important;
            }

            .backdrop-blur-xl,
            .backdrop-blur-sm {
                background-color: rgba(255, 255, 255, 0.9) !important;
                -webkit-backdrop-filter: none !important;
                backdrop-filter: none !important;
            }
        }
    </style>

    <script>
        function themeShell() {
            return {
                open: false,
                userMenu: false,
                motion: {
                    reduce: JSON.parse(
                        localStorage.getItem('motion.reduce') ?? (
                            (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) ?
                            'true' : 'false'
                        )
                    )
                },
                theme: {
                    dark: JSON.parse(localStorage.getItem('theme.dark') || 'false'),
                    primary: localStorage.getItem('theme.primary') || '#4F46E5',
                    accent: localStorage.getItem('theme.accent') || '#8B5CF6',
                    bgFrom: localStorage.getItem('theme.bgFrom') || '#f8fafc',
                    bgTo: localStorage.getItem('theme.bgTo') || '#dbeafe'
                },
                presets: [{
                        name: 'Indigo',
                        primary: '#4F46E5',
                        accent: '#8B5CF6',
                        bgFrom: '#f8fafc',
                        bgTo: '#dbeafe'
                    },
                    {
                        name: 'Emerald',
                        primary: '#059669',
                        accent: '#10B981',
                        bgFrom: '#f0fdf4',
                        bgTo: '#d1fae5'
                    },
                    {
                        name: 'Rose',
                        primary: '#E11D48',
                        accent: '#F43F5E',
                        bgFrom: '#fff1f2',
                        bgTo: '#ffe4e6'
                    },
                    {
                        name: 'Slate',
                        primary: '#334155',
                        accent: '#475569',
                        bgFrom: '#f1f5f9',
                        bgTo: '#e2e8f0'
                    }
                ],
                initTheme() {
                    this.applyVars();
                    // Apply theme classes immediately
                    this.updateThemeClasses();
                },
                applyVars() {
                    const r = document.documentElement.style;
                    r.setProperty('--brand-primary', this.theme.primary);
                    r.setProperty('--brand-accent', this.theme.accent);
                    r.setProperty('--bg-from', this.theme.bgFrom);
                    r.setProperty('--bg-to', this.theme.bgTo);
                },
                updateThemeClasses() {
                    const body = document.body;
                    if (this.theme.dark) {
                        body.classList.add('dark');
                    } else {
                        body.classList.remove('dark');
                    }
                    if (this.motion.reduce) {
                        body.classList.add('reduce-motion');
                    } else {
                        body.classList.remove('reduce-motion');
                    }
                },
                toggleDark() {
                    this.theme.dark = !this.theme.dark;
                    localStorage.setItem('theme.dark', JSON.stringify(this.theme.dark));
                    this.updateThemeClasses();
                },
                toggleMotionReduce() {
                    this.motion.reduce = !this.motion.reduce;
                    localStorage.setItem('motion.reduce', JSON.stringify(this.motion.reduce));
                    this.updateThemeClasses();
                },
                applyPreset(p) {
                    this.theme.primary = p.primary;
                    this.theme.accent = p.accent;
                    this.theme.bgFrom = p.bgFrom;
                    this.theme.bgTo = p.bgTo;
                    ['primary', 'accent', 'bgFrom', 'bgTo'].forEach(k => localStorage.setItem('theme.' + k, this.theme[k]));
                    this.applyVars();
                }
            }
        }
    </script>

    @if (app()->environment('production'))
        <link rel="manifest" href="/manifest.webmanifest">
        <meta name="theme-color" content="#4F46E5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
    @endif
</head>

<body class="m-0 min-h-screen text-slate-900 animate-gradient bg-linear-to-br from-slate-50 to-indigo-50"
    x-data="themeShell()" x-init="initTheme()"
    :class="(theme.dark ? 'dark ' : '') + (motion.reduce ? 'reduce-motion' : '')"
    :style="{
        backgroundImage: `linear-gradient(135deg, ${theme.bgFrom}, ${theme.bgTo})`,
        backgroundSize: '400% 400%',
        backgroundAttachment: 'fixed'
    }">
    @auth
        <!-- Topbar -->
        <header class="sticky top-0 z-40 backdrop-blur-xl animate-fade-in"
            :class="theme.dark ? 'bg-slate-900/80 border-b border-slate-700 shadow-slate-900/20' :
                'bg-white/80 border-b border-slate-200/60 shadow-lg shadow-indigo-500/5'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <button id="sidebarToggle"
                        class="relative z-50 p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 shadow-sm hover:shadow-md active:scale-95 transition-all duration-300 touch-manipulation md:hidden"
                        :class="theme.dark ?
                            'border-slate-600 bg-slate-800 hover:bg-slate-700 active:bg-slate-600 text-slate-200' : ''"
                        @click.prevent="open = !open" aria-label="Toggle Sidebar">
                        <span class="material-icons text-2xl transition-colors">menu</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group mr-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-8 mr-2" loading="lazy"
                            decoding="async">
                        <span
                            class="text-2xl font-bold tracking-tight bg-clip-text text-transparent bg-size-[200%_auto] animate-gradient"
                            :style="{ backgroundImage: `linear-gradient(90deg, ${theme.primary}, ${theme.accent}, ${theme.primary})` }">{{ config('app.name') }}</span>
                    </a>
                </div>
                <div class="flex items-center gap-4 justify-end">
                    <!-- Mobile User Info -->
                    <div class="md:hidden flex items-center text-sm text-slate-600">
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                    </div>

                    <div class="hidden md:flex items-center text-sm"
                        :class="theme.dark ? 'text-slate-300' : 'text-slate-600'">
                        <button @click="toggleDark()"
                            class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 shadow-sm hover:shadow-md active:scale-95 transition-all duration-300 mr-3"
                            :class="theme.dark ?
                                'border-slate-600 bg-slate-800 hover:bg-slate-700 active:bg-slate-600 text-slate-200' :
                                ''">
                            <span class="material-icons align-middle mr-2 text-lg"
                                x-text="theme.dark ? 'dark_mode' : 'light_mode'"></span>
                            <span x-text="theme.dark ? 'Dark' : 'Light'"></span>
                        </button>
                        <div class="flex items-center gap-2">
                            <template x-for="(col, idx) in presets" :key="idx">
                                <button
                                    class="w-8 h-8 rounded-xl border-2 border-white shadow-sm hover:shadow-md active:scale-95 transition-all duration-300"
                                    :style="{ background: col.primary, borderColor: theme.dark ? '#334155' : '#e2e8f0' }"
                                    :class="theme.dark ? 'hover:shadow-slate-900/20' : 'hover:shadow-indigo-500/20'"
                                    @click="applyPreset(col)" :title="col.name"></button>
                            </template>
                        </div>
                    </div>
                    <div
                        class="hidden md:flex items-center text-sm text-slate-600 bg-linear-to-r from-slate-50 to-indigo-50 px-4 py-2 rounded-full border border-slate-200/60 shadow-sm hover:shadow-md hover:scale-105 transition-all duration-300">
                        <span class="material-icons mr-2 text-indigo-600 animate-pulse-slow">account_circle</span>
                        <span class="font-semibold">{{ Auth::user()->name }}</span>
                        <span class="mx-2 text-indigo-300">•</span>
                        <span class="capitalize text-indigo-600 font-medium">{{ Auth::user()->role }}</span>
                    </div>
                    <div class="relative" @keydown.escape.window="userMenu=false">
                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 shadow-sm hover:shadow-md active:scale-95 transition-all duration-300"
                            :class="theme.dark ?
                                'border-slate-600 bg-slate-800 hover:bg-slate-700 active:bg-slate-600 text-slate-200' :
                                ''"
                            @click="userMenu=!userMenu">
                            <span class="material-icons transition-transform duration-300 text-lg"
                                :class="userMenu ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-cloak x-show="userMenu" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white/95 backdrop-blur-xl border border-slate-200/60 rounded-2xl shadow-2xl shadow-slate-500/20 overflow-hidden">
                            <div
                                class="px-4 py-3 text-xs text-slate-500 bg-linear-to-r from-slate-50 to-indigo-50 border-b border-slate-100">
                                {{ Auth::user()->email }}</div>
                            <div class="p-2">
                                <button @click="toggleDark()"
                                    class="w-full text-left px-4 py-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 shadow-sm hover:shadow-md active:scale-95 transition-all duration-300 flex items-center gap-3">
                                    <span class="material-icons text-lg"
                                        x-text="theme.dark ? 'dark_mode' : 'light_mode'"></span>
                                    <span x-text="theme.dark ? 'Mode Terang' : 'Mode Gelap'"></span>
                                </button>
                                <div class="mt-2">
                                    <div class="text-xs text-slate-500 mb-2 px-3">Tema Warna:</div>
                                    <div class="flex gap-2 px-4">
                                        <template x-for="(col, idx) in presets" :key="idx">
                                            <button
                                                class="w-8 h-8 rounded-xl border-2 border-white shadow-sm hover:shadow-md active:scale-95 transition-all duration-300 flex-1"
                                                :style="{
                                                    background: col.primary,
                                                    borderColor: theme.dark ? '#334155' : '#e2e8f0'
                                                }"
                                                :class="theme.dark ? 'hover:shadow-slate-900/20' : 'hover:shadow-indigo-500/20'"
                                                @click="applyPreset(col)" :title="col.name"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-slate-100">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-3 rounded-xl border border-red-200 bg-white hover:bg-red-50 active:bg-red-100 text-red-600 shadow-sm hover:shadow-md active:scale-95 transition-all duration-300 flex items-center gap-3 font-medium">
                                        <span class="material-icons text-lg">logout</span>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside id="sidebar" class="fixed top-0 left-0 w-72 h-screen z-50 border-r md:relative md:w-64 md:h-auto"
                :class="theme.dark ? 'bg-slate-900 border-slate-700' : 'bg-white border-slate-200'"
                x-show="open || window.innerWidth >= 768" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-full" @click.away="open = false">
                <nav class="h-full flex flex-col py-8">
                    <div class="px-6 mb-8">
                        <div class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Menu</div>
                    </div>
                    <div class="flex-1 px-4 space-y-2 overflow-y-auto">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                            <span class="material-icons">dashboard</span>
                            <span>Dashboard</span>
                        </a>

                        @if (Auth::user()->hasAnyRole('owner', 'manager'))
                            <a href="{{ route('products.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('products.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">inventory_2</span>
                                <span>Produk</span>
                            </a>
                            <a href="{{ route('categories.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('categories.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">category</span>
                                <span>Kategori</span>
                            </a>
                        @endif

                        @if (Auth::user()->hasAnyRole('cashier', 'owner', 'manager'))
                            <a href="{{ route('pos.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('pos.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">point_of_sale</span>
                                <span>POS / Kasir</span>
                            </a>
                        @endif

                        @if (Auth::user()->hasAnyRole('owner'))
                            <a href="{{ route('users.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('users.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">people</span>
                                <span>Pengguna</span>
                            </a>
                        @endif

                        @if (Auth::user()->hasAnyRole('owner', 'manager', 'cashier'))
                            <a href="{{ route('stores.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('stores.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">store</span>
                                <span>Toko</span>
                            </a>
                            <a href="{{ route('customers.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('customers.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">group</span>
                                <span>Pelanggan</span>
                            </a>
                            <a href="{{ route('suppliers.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('suppliers.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">local_shipping</span>
                                <span>Supplier</span>
                            </a>
                            <a href="{{ route('purchases.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('purchases.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">receipt_long</span>
                                <span>Pembelian</span>
                            </a>
                            <a href="{{ route('promotions.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('promotions.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">sell</span>
                                <span>Promosi</span>
                            </a>
                            <a href="{{ route('analytics.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('analytics.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">insights</span>
                                <span>Analytics</span>
                            </a>
                            <a href="{{ route('reports.sales') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200 {{ request()->routeIs('reports.*') ? 'bg-indigo-500 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                                <span class="material-icons">bar_chart</span>
                                <span>Laporan</span>
                            </a>
                        @endif
                    </div>
                    <div class="px-6 mt-auto pt-6 text-xs text-slate-500">
                        <div class="border-t border-slate-200 pt-3 text-center font-medium">© {{ date('Y') }} {{ config('app.name') }}
                        </div>
                    </div>
                </nav>
            </aside>

            <!-- Mobile overlay when sidebar open -->
            <div x-cloak x-show="open" @click="open = false" @touchstart.prevent="open = false"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/50 md:hidden z-40 backdrop-blur-sm"></div>

            <!-- Main Content -->
            <main
                class="flex-1 min-h-screen p-4 sm:p-6 lg:p-8 xl:p-10 bg-white/70 backdrop-blur-sm rounded-none md:rounded-2xl xl:rounded-3xl shadow-none md:shadow-xl xl:shadow-2xl shadow-indigo-500/10 mx-0 sm:mx-2 md:mx-4 my-2 sm:my-4 md:my-6 animate-fade-in hover:shadow-indigo-500/20 transition-all duration-300 lg:max-w-6xl xl:max-w-7xl lg:mx-auto w-full">
                @include('components.toast')
                @yield('content')
            </main>
        </div>
    @endauth

    @guest
        @yield('content')
    @endguest

    @if (app()->environment('production'))
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js').catch(console.error);
                });
            }
        </script>
    @endif

    <!-- Debug script untuk memastikan Alpine.js loaded -->
    <script>
        document.addEventListener('alpine:init', () => {
            console.log('✅ Alpine.js berhasil dimuat!');
        });

        // Fallback jika Alpine tidak load
        setTimeout(() => {
            if (typeof Alpine === 'undefined') {
                console.error('❌ Alpine.js tidak berhasil dimuat! Reload halaman.');
                alert('Ada masalah dengan loading halaman. Silakan refresh.');
            }
        }, 2000);
    </script>
</body>

</html>
