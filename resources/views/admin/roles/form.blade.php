@extends('admin.layout')

@section('title', isset($role) ? admin_copy('admin.roles.edit_role') : admin_copy('admin.roles.add_role'))

@section('content')
@php
    $isSystemRole = isset($role) && $role->name === \App\Support\AccessControl::SUPER_ADMIN_ROLE;
@endphp

<div class="mb-8">
    <h2 class="text-2xl font-bold tracking-tight">{{ isset($role) ? admin_copy('admin.roles.edit_role') : admin_copy('admin.roles.add_role') }}</h2>
    <p class="text-slate-500 dark:text-slate-400 mt-1">{{ isset($role) ? admin_copy('admin.roles.edit_subtitle') : admin_copy('admin.roles.add_subtitle') }}</p>
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

@if ($isSystemRole)
    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
        {{ admin_copy('admin.roles.system_role_notice') }}
    </div>
@endif

<form action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}" method="POST">
    @csrf
    @if (isset($role))
        @method('PUT')
    @endif

    <div class="space-y-8">
        <section class="space-y-2">
            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ admin_copy('admin.general.role') }}</label>
            <input name="name" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 focus:border-primary focus:ring-primary/20 dark:text-white transition-all px-4 py-2.5 disabled:opacity-60" type="text" value="{{ old('name', $role->name ?? '') }}" placeholder="{{ admin_copy('admin.roles.name_placeholder') }}" required @disabled($isSystemRole)/>
        </section>

        <section class="space-y-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ admin_copy('admin.general.permissions') }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.roles.permissions_help') }}</p>
            </div>

            <input type="hidden" name="permissions[]" value="{{ \App\Support\AccessControl::DASHBOARD_VIEW }}"/>

            <div class="space-y-6">
                @foreach ($permissionGroups as $module => $permissions)
                    <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <h4 class="text-sm font-bold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">{{ admin_copy('admin.rbac.modules.' . $module) }}</h4>
                            </div>
                            @if ($module === 'dashboard')
                                <span class="text-xs font-semibold text-primary">{{ admin_copy('admin.roles.required_permission_hint') }}</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($permissions as $permission)
                                @php
                                    $checked = in_array($permission, old('permissions', $selectedPermissions ?? []), true) || $permission === \App\Support\AccessControl::DASHBOARD_VIEW;
                                    $locked = $permission === \App\Support\AccessControl::DASHBOARD_VIEW || $isSystemRole;
                                @endphp
                                <label class="flex items-start gap-3 rounded-lg border border-slate-200 dark:border-slate-800 px-4 py-3 {{ $locked ? 'bg-slate-50 dark:bg-slate-800/60' : 'bg-white dark:bg-slate-900' }}">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission }}"
                                        class="mt-1 rounded border-slate-300 text-primary focus:ring-primary"
                                        @checked($checked)
                                        @disabled($locked)
                                    />
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-900 dark:text-slate-100">{{ admin_copy('admin.rbac.permissions.' . str_replace('.', '_', $permission)) }}</span>
                                        <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $permission }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">
            <a href="{{ route('admin.roles.index') }}" class="px-6 py-2.5 rounded-lg text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-800 transition-all">
                {{ admin_copy('admin.general.cancel') }}
            </a>
            @unless($isSystemRole)
                <button class="px-8 py-2.5 rounded-lg text-sm font-bold bg-accent text-white hover:opacity-90 shadow-lg shadow-accent/20 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    {{ isset($role) ? admin_copy('admin.roles.edit_role') : admin_copy('admin.roles.add_role') }}
                </button>
            @endunless
        </div>
    </div>
</form>
@endsection
