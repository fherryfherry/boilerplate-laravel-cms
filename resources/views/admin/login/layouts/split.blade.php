<div class="grid w-full max-w-6xl overflow-hidden rounded-[32px] border border-slate-200/70 bg-white shadow-2xl dark:border-slate-800/80 dark:bg-slate-900 lg:grid-cols-[1.2fr_0.9fr]" data-login-layout="split">
    <section class="relative hidden overflow-hidden lg:block">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(52,113,183,0.35),_transparent_45%),linear-gradient(145deg,_#091120,_#132238_45%,_#0f172a_100%)]"></div>
        <div class="absolute inset-y-0 right-0 w-px bg-white/10"></div>
        <div class="relative flex h-full flex-col justify-between p-10 text-white">
            <div class="space-y-6">
                <div class="inline-flex size-14 items-center justify-center rounded-2xl bg-white/10 backdrop-blur">
                    <span class="material-symbols-outlined text-3xl">admin_panel_settings</span>
                </div>
                <div class="max-w-md space-y-4">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sky-100/80">{{ $appName }}</p>
                    <h1 class="text-5xl font-bold leading-tight">Admin access built for focused work.</h1>
                    <p class="text-base leading-7 text-slate-200/90">{{ admin_copy('admin.login.subtitle') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Security</p>
                    <p class="mt-2 text-2xl font-bold">24/7</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Modules</p>
                    <p class="mt-2 text-2xl font-bold">RBAC</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-300">Theme</p>
                    <p class="mt-2 text-2xl font-bold">Live</p>
                </div>
            </div>
        </div>
    </section>

    <section class="flex items-center justify-center p-6 sm:p-10 lg:p-12">
        <div class="w-full max-w-md space-y-8">
            <div class="space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary">{{ $appName }}</p>
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">{{ admin_copy('admin.login.welcome') }}</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ admin_copy('admin.login.subtitle') }}</p>
                </div>
            </div>

            @include('admin.login.partials.form')

            <div class="flex items-center justify-between gap-4 border-t border-slate-200 pt-5 dark:border-slate-800">
                <p class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <span class="material-symbols-outlined text-sm">shield</span>
                    {{ admin_copy('admin.login.secure_access') }}
                </p>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">&copy; {{ now()->year }} {{ $appName }}</p>
            </div>
        </div>
    </section>
</div>
