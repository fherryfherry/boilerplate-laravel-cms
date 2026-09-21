@extends('admin.layout')

@section('title', isset($user) ? admin_copy('admin.users.edit_user') : admin_copy('admin.users.add_user'))

@section('content')
@php
    $selectedRole = old('role', isset($user) ? $user->roles->pluck('name')->first() : '');
@endphp

<div class="mb-8">
    <h2 class="text-2xl font-bold tracking-tight">{{ isset($user) ? admin_copy('admin.users.edit_user') : admin_copy('admin.users.add_user') }}</h2>
    <p class="text-slate-500 dark:text-slate-400 mt-1">{{ isset($user) ? admin_copy('admin.users.edit_subtitle') : admin_copy('admin.users.add_subtitle') }}</p>
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

<form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
    @csrf
    @if (isset($user))
        @method('PUT')
    @endif

    <div class="space-y-8">
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.general.name') }}</label>
                <input name="name" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="text" value="{{ old('name', $user->name ?? '') }}" required/>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.users.email') }}</label>
                <input name="email" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="email" value="{{ old('email', $user->email ?? '') }}" required/>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.general.role') }}</label>
                <select name="role" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" required>
                    <option value="">{{ admin_copy('admin.users.role_placeholder') }}</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected($selectedRole === $role->name)>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.users.password_label') }}</label>
                <input name="password" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="password" placeholder="{{ isset($user) ? admin_copy('admin.users.password_optional') : admin_copy('admin.users.password_required') }}" {{ isset($user) ? '' : 'required' }}/>
            </div>
        </section>

        <section class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.users.password_confirmation_label') }}</label>
            <input name="password_confirmation" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5" type="password"/>
        </section>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-lg text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 transition-all">
                {{ admin_copy('admin.general.cancel') }}
            </a>
            <button class="px-8 py-2.5 rounded-lg text-sm font-bold bg-primary text-white hover:opacity-90 shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span>
                {{ isset($user) ? admin_copy('admin.users.edit_user') : admin_copy('admin.users.add_user') }}
            </button>
        </div>
    </div>
</form>
@endsection
