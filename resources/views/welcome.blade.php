<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="dark">
        <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">

        <title>Welcome Page</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

        <!-- Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: '#3b82f6',
                        },
                        fontFamily: {
                            sans: ['Figtree', 'sans-serif'],
                        },
                    }
                }
            }
        </script>

        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                display: inline-block;
                vertical-align: middle;
                line-height: 1;
            }
            .bg-grid {
                background-image: radial-gradient(circle, rgba(51, 65, 85, 0.8) 1px, transparent 1px);
                background-size: 30px 30px;
            }
            .glass {
                background: rgba(15, 23, 42, 0.7);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(71, 85, 105, 0.35);
            }
            * { scrollbar-width: thin; scrollbar-color: rgba(148, 163, 184, 0.28) transparent; }
            *::-webkit-scrollbar { width: 5px; height: 5px; }
            *::-webkit-scrollbar-track { background: transparent; border-radius: 9999px; }
            *::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.28); border-radius: 9999px; border: 1px solid transparent; }
        </style>
    </head>
    <body class="antialiased bg-slate-950 text-slate-100 font-sans selection:bg-primary selection:text-white">
        <div class="relative min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-grid">
            
            <!-- Navbar-like Top Right -->
            <div class="absolute top-0 right-0 p-6 flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="text-sm font-semibold text-slate-300 hover:text-primary transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-primary transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-primary text-white text-sm font-bold rounded-lg shadow-sm hover:bg-primary/90 transition-all">Get Started</a>
                        @endif
                    @endauth
                @endif                                               
            </div>

            <div class="max-w-4xl w-full space-y-12 text-center">
                <!-- Hero Section -->
                <div class="space-y-6">
                    <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight text-slate-100">
                        Your starter workspace is <span class="text-primary">ready</span>
                    </h1>
                    
                    <p class="text-xl text-slate-400 max-w-2xl mx-auto leading-relaxed">
                        This is the default page for your new project. Describe what you want to build in TanpaNgoding AI, and your app will be generated step by step.
                    </p>
                </div>

                <!-- Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="glass p-8 rounded-2xl shadow-sm hover:shadow-md transition-all text-left group">
                        <div class="w-12 h-12 bg-blue-500/20 text-blue-300 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-100 mb-2">Starter Notes</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">Begin with a clear prompt: module name, fields, relationships, and expected pages.</p>
                    </div>

                    <div class="glass p-8 rounded-2xl shadow-sm hover:shadow-md transition-all text-left group border-primary/30 bg-primary/10">
                        <div class="w-12 h-12 bg-primary text-white rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-primary/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.198-1.816" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-100 mb-2">Admin Panel</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">A baseline admin area is ready for you while the rest of the app is generated.</p>
                        <a href="{{ url('/admin/login') }}" class="mt-4 inline-flex items-center text-primary font-bold text-sm hover:gap-2 transition-all">
                            Go to Admin <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span>
                        </a>
                    </div>

                    <div class="glass p-8 rounded-2xl shadow-sm hover:shadow-md transition-all text-left group">
                        <div class="w-12 h-12 bg-purple-500/20 text-purple-300 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-100 mb-2">Next Step</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">Tell TanpaNgoding AI to build something to start development.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="pt-12 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-slate-500 text-sm font-medium">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-slate-500 hover:text-slate-200 transition-colors">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                        <a href="#" class="text-slate-500 hover:text-slate-200 transition-colors">
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if (view()->exists('ai-agent-helper::metadata-script'))
            @include('ai-agent-helper::metadata-script')
        @else
            <script>
                (function () {
                    const send = () => {
                        try {
                            window.parent.postMessage({ type: 'iframe-metadata', url: window.location.href, title: document.title }, '*');
                        } catch (e) {}
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
                    document.addEventListener('click', (event) => {
                        const anchor = event.target.closest('a');
                        if (anchor && anchor.href) {
                            setTimeout(send, 50);
                        }
                    });
                    wrapHistory();
                    send();
                })();
            </script>
        @endif
    </body>
</html>
