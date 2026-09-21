<div class="relative w-full max-w-5xl" data-login-layout="spotlight">
    <div class="absolute -top-16 left-8 h-48 w-48 rounded-full bg-primary/20 blur-3xl"></div>
    <div class="absolute -bottom-12 right-10 h-56 w-56 rounded-full bg-amber-400/15 blur-3xl"></div>

    <div class="relative grid gap-8 lg:grid-cols-[1fr_420px] lg:items-center">
        <section class="hidden lg:block">
            <div class="max-w-xl space-y-6">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-primary">{{ $appName }}</p>
                <h1 class="text-6xl font-bold leading-[1.02] tracking-tight text-slate-900 dark:text-white">One secure entrance for every admin workflow.</h1>
                <p class="max-w-lg text-lg leading-8 text-slate-600 dark:text-slate-300">{{ admin_copy('admin.login.subtitle') }}</p>
                <div class="flex flex-wrap gap-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/70 px-4 py-2 text-sm text-slate-600 backdrop-blur dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300">
                        <span class="material-symbols-outlined text-base text-primary">verified_user</span>
                        Role-aware access
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/70 px-4 py-2 text-sm text-slate-600 backdrop-blur dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300">
                        <span class="material-symbols-outlined text-base text-primary">dark_mode</span>
                        Theme ready
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white/70 px-4 py-2 text-sm text-slate-600 backdrop-blur dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300">
                        <span class="material-symbols-outlined text-base text-primary">bolt</span>
                        Fast admin flow
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-white/60 bg-white/85 p-6 shadow-2xl backdrop-blur-xl dark:border-slate-800/80 dark:bg-slate-900/85 sm:p-8">
            <div class="mb-8 flex items-start justify-between gap-4">
                <div class="space-y-3">
                    <div class="inline-flex size-14 items-center justify-center rounded-2xl bg-primary/10">
                        <span class="material-symbols-outlined text-3xl text-primary">lock_person</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary">{{ $appName }}</p>
                        <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">{{ admin_copy('admin.login.welcome') }}</h2>
                    </div>
                </div>
                <div class="hidden rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-right dark:border-slate-700 dark:bg-slate-800 sm:block">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Mode</p>
                    <p class="mt-1 text-sm font-semibold text-slate-600 dark:text-slate-200">Spotlight</p>
                </div>
            </div>

            <div class="space-y-6">
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
</div>
