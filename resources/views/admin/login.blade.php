<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex"/>
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        * { scrollbar-width: thin; scrollbar-color: rgba(148, 163, 184, 0.28) transparent; }
        *::-webkit-scrollbar { width: 5px; height: 5px; }
        *::-webkit-scrollbar-track { background: transparent; border-radius: 9999px; }
        *::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.28); border-radius: 9999px; border: 1px solid transparent; }
        .dark *::-webkit-scrollbar-track { background: transparent; }
        .dark *::-webkit-scrollbar-thumb { background: rgba(100, 116, 139, 0.42); border-color: transparent; }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#3471b7",
                        "background-light": "#f6f7f8",
                        "background-dark": "#13191f",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <script>
        if (localStorage.getItem('dark-mode') === 'true' ||
            (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <title>{{ admin_copy('admin.login.welcome') }} | {{ config('app.name') }}</title>
</head>
@php
    $appName = config('app.name');
    $loginLayout = config('admin.login_layout', 'panel');
@endphp
<body class="min-h-screen bg-background-light font-display text-slate-900 dark:bg-background-dark dark:text-slate-100" data-login-layout="{{ $loginLayout }}">
    <div class="fixed top-4 right-4 z-20">
        <button id="dark-mode-toggle" class="rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 dark:hover:bg-slate-800">
            <span id="dark-mode-icon" class="material-symbols-outlined">light_mode</span>
        </button>
    </div>

    <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10 sm:px-6 lg:px-8">
        @include('admin.login.layouts.' . $loginLayout, ['appName' => $appName])
    </main>

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

        updateIcon();

        darkModeToggle.addEventListener('click', () => {
            htmlElement.classList.toggle('dark');
            const isDark = htmlElement.classList.contains('dark');
            localStorage.setItem('dark-mode', isDark);
            updateIcon();
        });

        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        const passwordToggleIcon = document.getElementById('password-toggle-icon');

        if (passwordInput && passwordToggle && passwordToggleIcon) {
            passwordToggle.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';
                passwordToggle.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                passwordToggleIcon.textContent = isHidden ? 'visibility_off' : 'visibility';
            });
        }
    </script>
    @if (view()->exists('ai-agent-helper::metadata-script'))
        @include('ai-agent-helper::metadata-script')
    @else
        <script>
            (function () {
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

                        window.parent.postMessage({
                            type: 'iframe-metadata',
                            url: window.location.href,
                            title: document.title,
                            project_name: "{{ config('app.name') }}",
                            selected_text: selection.text,
                            selected_element: selection.element,
                        }, '*');
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
                let selectionUpdateTimeout;
                const queueSelectionUpdate = () => {
                    clearTimeout(selectionUpdateTimeout);
                    selectionUpdateTimeout = setTimeout(send, 120);
                };
                document.addEventListener('click', (event) => {
                    const anchor = event.target.closest('a');
                    if (anchor && anchor.href) {
                        setTimeout(send, 50);
                    }
                });
                document.addEventListener('selectionchange', queueSelectionUpdate);
                document.addEventListener('select', queueSelectionUpdate, true);
                wrapHistory();
                send();
            })();
        </script>
    @endif
</body>
</html>
