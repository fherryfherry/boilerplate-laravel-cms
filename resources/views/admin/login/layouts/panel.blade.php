<div class="w-full max-w-md" data-login-layout="panel">
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl backdrop-blur-sm dark:border-slate-800 dark:bg-slate-900/50">
        <div class="flex flex-col items-center pt-10 pb-6">
            <div class="mb-4 flex size-16 items-center justify-center rounded-xl bg-primary/10">
                <span class="material-symbols-outlined text-4xl text-primary">admin_panel_settings</span>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">{{ admin_copy('admin.login.welcome') }}</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ admin_copy('admin.login.subtitle') }}</p>
        </div>

        <div class="px-8 pb-10">
            @include('admin.login.partials.form')
        </div>

        <div class="border-t border-slate-200 bg-slate-50/50 px-8 py-4 text-center dark:border-slate-800 dark:bg-slate-800/30">
            <p class="flex items-center justify-center gap-1 text-xs text-slate-500 dark:text-slate-500">
                <span class="material-symbols-outlined text-sm">shield</span>
                {{ admin_copy('admin.login.secure_access') }}
            </p>
        </div>
    </div>
    <div class="mt-8 text-center">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">&copy; {{ now()->year }} {{ $appName }}</p>
    </div>
</div>
