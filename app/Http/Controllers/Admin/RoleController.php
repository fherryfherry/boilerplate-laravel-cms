<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AccessControl;
use App\Support\TableSort;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:roles.view')->only('index');
        $this->middleware('can:roles.create')->only(['create', 'store']);
        $this->middleware('can:roles.update')->only(['edit', 'update']);
        $this->middleware('can:roles.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $rolesQuery = Role::query()
            ->where('guard_name', AccessControl::GUARD)
            ->with('permissions')
            ->withCount('permissions');

        [$sort, $direction] = TableSort::apply(
            $rolesQuery,
            $request->string('sort')->toString(),
            $request->string('direction')->toString(),
            [
                'id' => 'id',
                'name' => 'name',
                'permissions' => fn ($query, $sortDirection) => $query
                    ->orderBy('permissions_count', $sortDirection)
                    ->orderBy('name'),
            ],
            'name',
            'asc',
        );

        $roles = $rolesQuery->paginate(10)->withQueryString();

        return view('admin.roles.index', compact('roles', 'sort', 'direction'));
    }

    public function create()
    {
        return view('admin.roles.form', [
            'permissionGroups' => AccessControl::groupedPermissions(),
            'selectedPermissions' => [AccessControl::DASHBOARD_VIEW],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->where('guard_name', AccessControl::GUARD),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')->where('guard_name', AccessControl::GUARD),
            ],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => AccessControl::GUARD,
        ]);

        $role->syncPermissions($this->normalizePermissions($validated['permissions'] ?? []));

        return redirect()->route('admin.roles.index')->with('success', admin_copy('admin.roles.create_success'));
    }

    public function edit(Role $role)
    {
        abort_unless($role->guard_name === AccessControl::GUARD, 404);

        return view('admin.roles.form', [
            'role' => $role->load('permissions'),
            'permissionGroups' => AccessControl::groupedPermissions(),
            'selectedPermissions' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_unless($role->guard_name === AccessControl::GUARD, 404);

        if (AccessControl::isSystemRole($role->name)) {
            return back()->with('error', admin_copy('admin.roles.system_role_immutable'));
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->where('guard_name', AccessControl::GUARD)
                    ->ignore($role->id),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')->where('guard_name', AccessControl::GUARD),
            ],
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        $role->syncPermissions($this->normalizePermissions($validated['permissions'] ?? []));

        return redirect()->route($this->redirectRouteForCurrentUser())->with('success', admin_copy('admin.roles.update_success'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_unless($role->guard_name === AccessControl::GUARD, 404);

        if (AccessControl::isSystemRole($role->name)) {
            return back()->with('error', admin_copy('admin.roles.cannot_delete_system_role'));
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', admin_copy('admin.roles.delete_success'));
    }

    private function normalizePermissions(array $permissions): array
    {
        return collect($permissions)
            ->push(AccessControl::DASHBOARD_VIEW)
            ->unique()
            ->values()
            ->all();
    }

    private function redirectRouteForCurrentUser(): string
    {
        $currentUser = auth()->user()?->fresh();

        if ($currentUser && $currentUser->can(AccessControl::ROLE_VIEW)) {
            return 'admin.roles.index';
        }

        return 'admin.dashboard';
    }
}
