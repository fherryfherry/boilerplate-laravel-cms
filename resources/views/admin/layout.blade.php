<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex"/>
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex"/>
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;family=Public+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#3471b7",
                        "accent": "#f97316",
                        "background-light": "#f6f7f8",
                        "background-dark": "#13191f",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "Public Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        * { scrollbar-width: thin; scrollbar-color: rgba(148, 163, 184, 0.28) transparent; }
        *::-webkit-scrollbar { width: 5px; height: 5px; }
        *::-webkit-scrollbar-track {
            background: transparent;
            border-radius: 9999px;
        }
        *::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.28);
            border-radius: 9999px;
            border: 1px solid transparent;
        }
        .dark *::-webkit-scrollbar-track {
            background: transparent;
        }
        .dark *::-webkit-scrollbar-thumb {
            background: rgba(100, 116, 139, 0.42);
            border-color: transparent;
        }
    </style>
    <script>
        // Check for saved dark mode preference
        if (localStorage.getItem('dark-mode') === 'true' || 
            (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
@php
    $appName = config('app.name');
    $navigationLayout = config('admin.navigation_layout', 'sidebar');
    $isTopbarLayout = $navigationLayout === 'topbar';
    $currentUser = auth()->user();
    $userRole = $currentUser->roles->pluck('name')->first() ?? admin_copy('admin.general.administrator');
    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($currentUser->name) . '&background=3471b7&color=fff';
    $navigationItems = collect([
        [
            'route' => 'admin.dashboard',
            'match' => 'admin.dashboard',
            'permission' => 'dashboard.view',
            'icon' => 'dashboard',
            'label' => admin_copy('admin.general.dashboard'),
            'font' => 'font-semibold',
        ],
        [
            'route' => 'admin.users.index',
            'match' => 'admin.users.*',
            'permission' => 'users.view',
            'icon' => 'group',
            'label' => admin_copy('admin.general.users'),
            'font' => 'font-medium',
        ],
        [
            'route' => 'admin.roles.index',
            'match' => 'admin.roles.*',
            'permission' => 'roles.view',
            'icon' => 'verified_user',
            'label' => admin_copy('admin.general.roles'),
            'font' => 'font-medium',
        ],
    ])->filter(fn ($item) => $currentUser->can($item['permission']))->values();
@endphp
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 antialiased" data-navigation-layout="{{ $navigationLayout }}">
    <div class="{{ $isTopbarLayout ? 'min-h-screen flex flex-col' : 'flex h-screen overflow-hidden' }}">
        @unless ($isTopbarLayout)
            <aside class="w-64 flex-shrink-0 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hidden md:flex flex-col" data-navigation-desktop="sidebar">
                <div class="p-6 flex items-center gap-3">
                    <div class="size-10 rounded-lg bg-primary flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-tight tracking-tight">{{ $appName }}</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Management System</p>
                    </div>
                </div>
                <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                    @foreach ($navigationItems as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            data-admin-nav-item
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs($item['match']) ? 'bg-primary/10 text-primary border border-primary/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors cursor-pointer"
                        >
                            <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                            <span class="text-sm {{ $item['font'] }}">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-100 dark:bg-slate-800 group relative">
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="size-10 rounded-full bg-slate-300 dark:bg-slate-700 overflow-hidden shrink-0">
                                <img alt="Admin Profile" class="w-full h-full object-cover" src="{{ $avatarUrl }}"/>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold truncate text-slate-900 dark:text-slate-100 group-hover:text-primary transition-colors">{{ $currentUser->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $userRole }}</p>
                            </div>
                        </a>
                        <form action="{{ route('admin.logout') }}" method="POST" class="shrink-0">
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors flex items-center" title="{{ admin_copy('admin.general.logout') }}">
                                <span class="material-symbols-outlined text-xl">logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        @endunless

        <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden z-50 flex flex-col">
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-primary flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-xl">admin_panel_settings</span>
                    </div>
                    <div>
                        <h1 class="text-sm font-bold leading-tight tracking-tight">{{ $appName }}</h1>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Menu</p>
                    </div>
                </div>
                <button id="mobile-menu-close" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <nav class="flex-1 px-3 space-y-1 overflow-y-auto">
                @foreach ($navigationItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        data-admin-nav-item
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs($item['match']) ? 'bg-primary/10 text-primary border border-primary/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }} transition-colors cursor-pointer"
                    >
                        <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                        <span class="text-sm {{ $item['font'] }}">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-100 dark:bg-slate-800">
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="size-10 rounded-full bg-slate-300 dark:bg-slate-700 overflow-hidden shrink-0">
                            <img alt="Admin Profile" class="w-full h-full object-cover" src="{{ $avatarUrl }}"/>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate text-slate-900 dark:text-slate-100">{{ $currentUser->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $userRole }}</p>
                        </div>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="shrink-0">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors flex items-center" title="{{ admin_copy('admin.general.logout') }}">
                            <span class="material-symbols-outlined text-xl">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        <div id="mobile-backdrop" class="fixed inset-0 bg-black/40 hidden md:hidden z-40"></div>

        <main class="flex-1 flex flex-col min-h-0 overflow-hidden bg-background-light dark:bg-background-dark">
            @if ($isTopbarLayout)
                <header class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 z-10" data-navigation-desktop="topbar">
                    <div class="px-4 md:px-8 py-4 flex flex-col gap-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <button id="mobile-menu-toggle" class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
                                    <span class="material-symbols-outlined">menu</span>
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0">
                                    <div class="size-10 rounded-lg bg-primary flex items-center justify-center text-white shrink-0">
                                        <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h1 class="text-lg font-bold leading-tight tracking-tight">{{ $appName }}</h1>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Management System</p>
                                    </div>
                                </a>
                            </div>
                            <div class="hidden lg:block flex-1 max-w-xl">
                                <div class="relative w-full">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                                    <input class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary text-sm transition-all" placeholder="{{ admin_copy('admin.general.search_placeholder') }}" type="text"/>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.profile.edit') }}" class="hidden sm:flex items-center gap-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/70 px-3 py-2 min-w-0">
                                    <div class="size-9 rounded-full bg-slate-300 dark:bg-slate-700 overflow-hidden shrink-0">
                                        <img alt="Admin Profile" class="w-full h-full object-cover" src="{{ $avatarUrl }}"/>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold truncate text-slate-900 dark:text-slate-100">{{ $currentUser->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $userRole }}</p>
                                    </div>
                                </a>
                                <button id="dark-mode-toggle" data-theme-toggle class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <span id="dark-mode-icon" class="material-symbols-outlined">light_mode</span>
                                </button>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-rose-500 dark:hover:bg-slate-800 transition-colors" title="{{ admin_copy('admin.general.logout') }}">
                                        <span class="material-symbols-outlined">logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <nav class="hidden md:flex items-center gap-2 overflow-x-auto">
                                @foreach ($navigationItems as $item)
                                    <a
                                        href="{{ route($item['route']) }}"
                                        data-admin-nav-item
                                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm {{ request()->routeIs($item['match']) ? 'bg-primary text-white shadow-sm shadow-primary/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700' }} transition-colors whitespace-nowrap"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">{{ $item['icon'] }}</span>
                                        <span class="{{ $item['font'] }}">{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </nav>
                            <div class="relative w-full md:w-80 lg:hidden">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                                <input class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary text-sm transition-all" placeholder="{{ admin_copy('admin.general.search_placeholder') }}" type="text"/>
                            </div>
                        </div>
                    </div>
                </header>
            @else
                <header class="h-16 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between px-4 md:px-8 z-10">
                    <div class="flex items-center gap-3 flex-1 max-w-md">
                        <button id="mobile-menu-toggle" class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                        <div class="relative w-full">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary text-sm transition-all" placeholder="{{ admin_copy('admin.general.search_placeholder') }}" type="text"/>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button id="dark-mode-toggle" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <span id="dark-mode-icon" class="material-symbols-outlined">light_mode</span>
                        </button>
                    </div>
                </header>
            @endif

            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-7xl mx-auto pb-24">
                    @yield('content')
                </div>
            </div>

            <div class="pointer-events-none sticky bottom-0 px-4 md:px-8 pt-3 pb-4">
                <div class="max-w-7xl mx-auto flex justify-end">
                    <div class="pointer-events-auto text-xs font-medium leading-none text-slate-500/90 dark:text-slate-400/90">
                        {{ admin_copy('admin.general.powered_by') }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const darkModeIcon = document.getElementById('dark-mode-icon');
        const htmlElement = document.documentElement;

        function updateIcon() {
            if (htmlElement.classList.contains('dark')) {
                darkModeIcon.textContent = 'dark_mode';
            } else {
                darkModeIcon.textContent = 'light_mode';
            }
        }

        // Initialize icon on load
        updateIcon();

        darkModeToggle.addEventListener('click', () => {
            htmlElement.classList.toggle('dark');
            const isDark = htmlElement.classList.contains('dark');
            localStorage.setItem('dark-mode', isDark);
            updateIcon();
        });

        // Flash Messages with SweetAlert2
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: "{{ admin_copy('admin.general.success') }}",
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#0f172a',
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: "{{ admin_copy('admin.general.error') }}",
                text: "{{ session('error') }}",
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#0f172a',
            });
        @endif
    </script>
    <script>
        (function () {
            // Mobile menu toggling
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileBackdrop = document.getElementById('mobile-backdrop');
            const toggleBtn = document.getElementById('mobile-menu-toggle');
            const closeBtn = document.getElementById('mobile-menu-close');
            const openMobileMenu = () => {
                mobileSidebar.classList.remove('-translate-x-full');
                mobileBackdrop.classList.remove('hidden');
            };
            const closeMobileMenu = () => {
                mobileSidebar.classList.add('-translate-x-full');
                mobileBackdrop.classList.add('hidden');
            };
            if (toggleBtn) toggleBtn.addEventListener('click', openMobileMenu);
            if (closeBtn) closeBtn.addEventListener('click', closeMobileMenu);
            if (mobileBackdrop) mobileBackdrop.addEventListener('click', closeMobileMenu);
            document.addEventListener('click', (e) => {
                const a = e.target.closest('a');
                if (a && mobileBackdrop && !mobileBackdrop.classList.contains('hidden')) {
                    closeMobileMenu();
                }
            });
        })();
    </script>

    @if (view()->exists('ai-agent-helper::metadata-script'))
        @include('ai-agent-helper::metadata-script')
    @else
    <script>
        (function () {
            const getSidebarMenu = () => {
                const menus = [];
                document.querySelectorAll('aside nav a').forEach(a => {
                    menus.push({
                        label: a.querySelector('span:not(.material-symbols-outlined)')?.textContent.trim(),
                        url: a.href,
                        active: a.classList.contains('bg-primary/10')
                    });
                });
                return menus;
            };

            const getFormInputs = () => {
                const forms = [];
                document.querySelectorAll('form').forEach(form => {
                    const inputs = [];
                    form.querySelectorAll('input, select, textarea').forEach(input => {
                        if (input.type === 'hidden') return;
                        inputs.push({
                            name: input.name,
                            type: input.type,
                            placeholder: input.placeholder,
                            value: input.value
                        });
                    });
                    forms.push({
                        action: form.action,
                        method: form.method,
                        inputs: inputs
                    });
                });
                return forms;
            };

            const describeElement = (node) => {
                const element = node instanceof Element
                    ? node
                    : node?.parentElement instanceof Element
                        ? node.parentElement
                        : null;

                if (!element) {
                    return null;
                }

                const classes = Array.from(element.classList).slice(0, 3);
                const selector = [
                    element.tagName.toLowerCase(),
                    element.id ? `#${element.id}` : '',
                    classes.length ? `.${classes.join('.')}` : '',
                ].join('');

                return {
                    tag: element.tagName.toLowerCase(),
                    id: element.id || null,
                    name: element.getAttribute('name'),
                    type: element.getAttribute('type'),
                    role: element.getAttribute('role'),
                    classes,
                    selector,
                };
            };

            const getSelectionMetadata = () => {
                const activeElement = document.activeElement;

                if (activeElement instanceof HTMLInputElement || activeElement instanceof HTMLTextAreaElement) {
                    const start = activeElement.selectionStart ?? 0;
                    const end = activeElement.selectionEnd ?? 0;
                    const text = activeElement.value.slice(start, end).trim();

                    if (text) {
                        return {
                            text,
                            element: describeElement(activeElement),
                        };
                    }
                }

                const selection = window.getSelection();

                if (!selection || selection.rangeCount === 0) {
                    return { text: '', element: null };
                }

                const text = selection.toString().trim();

                if (!text) {
                    return { text: '', element: null };
                }

                return {
                    text,
                    element: describeElement(selection.getRangeAt(0).commonAncestorContainer),
                };
            };

            const send = () => {
                try {
                    const selection = getSelectionMetadata();
                    const metadata = {
                        type: 'iframe-metadata',
                        url: window.location.href,
                        title: document.title,
                        controller_method: "{{ \Illuminate\Support\Facades\Route::currentRouteAction() }}",
                        route_name: "{{ \Illuminate\Support\Facades\Route::currentRouteName() }}",
                        view_blade: "{{ $active_view ?? 'unknown' }}",
                        sidebar_menus: getSidebarMenu(),
                        form_inputs: getFormInputs(),
                        project_name: "{{ config('app.name') }}",
                        user_name: "{{ auth()->user()->name ?? 'Guest' }}",
                        theme_mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                        selected_text: selection.text,
                        selected_element: selection.element,
                        flash_messages: {
                            success: "{{ session('success') }}",
                            error: "{{ session('error') }}"
                        }
                    };
                    window.parent.postMessage(metadata, '*');
                } catch (e) {
                    console.error('Error sending metadata:', e);
                }
            };
            const wrapHistory = () => {
                const pushState = history.pushState;
                history.pushState = function () {
                    pushState.apply(this, arguments);
                    send();
                };
                const replaceState = history.replaceState;
                history.replaceState = function () {
                    replaceState.apply(this, arguments);
                    send();
                };
            };
            window.addEventListener('load', send);
            window.addEventListener('popstate', send);
            let selectionUpdateTimeout;
            const queueSelectionUpdate = () => {
                clearTimeout(selectionUpdateTimeout);
                selectionUpdateTimeout = setTimeout(send, 120);
            };
            document.addEventListener('click', (event) => {
                const anchor = event.target.closest('a');
                if (anchor && anchor.href) {
                    setTimeout(send, 50); // Small delay to allow URL to change
                }
            });
            document.addEventListener('selectionchange', queueSelectionUpdate);
            document.addEventListener('select', queueSelectionUpdate, true);
            wrapHistory();
            send();

            // Listen for theme changes to update metadata
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        send();
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        })();
    </script>
    @endif
    @include('admin.partials.tour')
</body>
</html>
