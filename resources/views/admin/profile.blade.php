@extends('admin.layout')

@section('title', admin_copy('admin.profile.title'))

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold tracking-tight">{{ admin_copy('admin.profile.title') }}</h2>
    <p class="text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.profile.subtitle') }}</p>
</div>

@if ($errors->any())
    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.profile.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="space-y-8">
        <!-- Basic Information -->
        <section class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-lg font-bold mb-6 text-slate-900 dark:text-slate-100">{{ admin_copy('admin.profile.basic_info') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.general.name') }}</label>
                    <input name="name" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="text" value="{{ old('name', $user->name) }}" required/>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.login.email_label') }}</label>
                    <input name="email" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="email" value="{{ old('email', $user->email) }}" required/>
                </div>
            </div>
        </section>

        <!-- Change Password -->
        <section class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-lg font-bold mb-6 text-slate-900 dark:text-slate-100">{{ admin_copy('admin.profile.change_password') }}</h3>
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.profile.current_password') }}</label>
                    <input name="current_password" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="password" placeholder="{{ admin_copy('admin.profile.password_placeholder') }}"/>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.profile.new_password') }}</label>
                        <input name="new_password" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="password" placeholder="{{ admin_copy('admin.profile.password_min_hint') }}"/>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.profile.confirm_password') }}</label>
                        <input name="new_password_confirmation" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="password"/>
                    </div>
                </div>
            </div>
        </section>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">
            <button type="submit" class="px-8 py-2.5 rounded-lg text-sm font-bold bg-primary text-white hover:opacity-90 shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span>
                {{ admin_copy('admin.profile.save_button') }}
            </button>
        </div>
    </div>
</form>
@endsection
