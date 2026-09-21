@if ($errors->any())
    <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-600">
        {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
    @csrf
    <div class="space-y-2">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ admin_copy('admin.login.email_label') }}</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
            <input name="email" class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none" placeholder="{{ admin_copy('admin.login.email_placeholder') }}" type="email" value="{{ old('email', app()->environment(['local','development']) ? config('admin.default_email', 'admin@example.com') : '') }}" required/>
        </div>
    </div>
    <div class="space-y-2">
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ admin_copy('admin.login.password_label') }}</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
            <input id="password" name="password" class="w-full pl-10 pr-12 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none" placeholder="{{ admin_copy('admin.login.password_placeholder') }}" type="password" value="{{ app()->environment(['local','development']) ? config('admin.default_password', '') : '' }}" required/>
            <button id="password-toggle" type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors" aria-label="Toggle password visibility" aria-pressed="false">
                <span id="password-toggle-icon" class="material-symbols-outlined text-xl">visibility</span>
            </button>
        </div>
    </div>
    <button class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3.5 rounded-lg shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group" type="submit">
        {{ admin_copy('admin.login.signin_button') }}
        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
    </button>
</form>
