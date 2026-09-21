@extends('admin.layout')

@section('title', admin_copy('admin.users.management'))

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-extrabold tracking-tight">{{ admin_copy('admin.users.management') }}</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">{{ admin_copy('admin.users.subtitle') }}</p>
    </div>
    @can('users.create')
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary/90 text-white font-bold rounded-lg shadow-sm transition-colors">
            <span class="material-symbols-outlined text-xl">person_add</span>
            <span>{{ admin_copy('admin.users.add_new') }}</span>
        </a>
    @endcan
</div>

<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                    @include('admin.partials.sortable-th', ['sort' => 'id', 'label' => admin_copy('admin.general.id'), 'defaultSort' => 'id', 'defaultDirection' => 'desc'])
                    @include('admin.partials.sortable-th', ['sort' => 'name', 'label' => admin_copy('admin.general.name'), 'defaultSort' => 'id', 'defaultDirection' => 'desc'])
                    @include('admin.partials.sortable-th', ['sort' => 'email', 'label' => admin_copy('admin.users.email'), 'defaultSort' => 'id', 'defaultDirection' => 'desc'])
                    @include('admin.partials.sortable-th', ['sort' => 'role', 'label' => admin_copy('admin.general.role'), 'defaultSort' => 'id', 'defaultDirection' => 'desc'])
                    @canany(['users.update', 'users.delete'])
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">{{ admin_copy('admin.general.actions') }}</th>
                    @endcanany
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono text-slate-500">#{{ $user->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-primary/10 text-primary px-3 py-1 text-xs font-bold uppercase tracking-wide">
                                {{ $user->roles->pluck('name')->first() ?? admin_copy('admin.users.no_role') }}
                            </span>
                        </td>
                        @canany(['users.update', 'users.delete'])
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @can('users.update')
                                        <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors" title="{{ admin_copy('admin.general.edit') }}">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                    @endcan
                                    @can('users.delete')
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors delete-button" title="{{ admin_copy('admin.general.delete') }}">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        @endcanany
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->canany(['users.update', 'users.delete']) ? 5 : 4 }}" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                            {{ admin_copy('admin.users.empty_state') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
        {{ $users->links() }}
    </div>
</div>

<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('.delete-form');

            Swal.fire({
                title: "{{ admin_copy('admin.users.delete_confirm_title') }}",
                text: "{{ admin_copy('admin.users.delete_confirm_text') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: "{{ admin_copy('admin.users.delete_confirm_yes') }}",
                cancelButtonText: "{{ admin_copy('admin.users.delete_confirm_no') }}",
                reverseButtons: true,
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#0f172a',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
