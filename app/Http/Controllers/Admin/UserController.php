<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AccessControl;
use App\Support\TableSort;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:users.view')->only('index');
        $this->middleware('can:users.create')->only(['create', 'store']);
        $this->middleware('can:users.update')->only(['edit', 'update']);
        $this->middleware('can:users.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $roleNameSubquery = DB::table('roles')
            ->select('roles.name')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereColumn('model_has_roles.model_id', 'users.id')
            ->where('model_has_roles.model_type', User::class)
            ->where('roles.guard_name', AccessControl::GUARD)
            ->limit(1);

        $usersQuery = User::query()
            ->with('roles')
            ->select('users.*');

        [$sort, $direction] = TableSort::apply(
            $usersQuery,
            $request->string('sort')->toString(),
            $request->string('direction')->toString(),
            [
                'id' => 'id',
                'name' => 'name',
                'email' => 'email',
                'role' => fn ($query, $sortDirection) => $query
                    ->orderBy($roleNameSubquery, $sortDirection)
                    ->orderBy('users.id'),
            ],
            'id',
            'desc',
        );

        $users = $usersQuery->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'sort', 'direction'));
    }

    public function create()
    {
        $roles = Role::query()
            ->where('guard_name', AccessControl::GUARD)
            ->orderBy('name')
            ->get();

        return view('admin.users.form', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', AccessControl::GUARD),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success', admin_copy('admin.users.create_success'));
    }

    public function edit(User $user)
    {
        $roles = Role::query()
            ->where('guard_name', AccessControl::GUARD)
            ->orderBy('name')
            ->get();

        return view('admin.users.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', AccessControl::GUARD),
            ],
        ]);

        if ($this->isLastSuperAdminRemoval($user, $validated['role'])) {
            return back()
                ->withInput()
                ->with('error', admin_copy('admin.users.cannot_remove_last_super_admin'));
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->syncRoles([$validated['role']]);

        $redirectRoute = $this->redirectRouteForCurrentUser();

        return redirect()->route($redirectRoute)->with('success', admin_copy('admin.users.update_success'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->hasRole(AccessControl::SUPER_ADMIN_ROLE) && $this->superAdminCount() <= 1) {
            return back()->with('error', admin_copy('admin.users.cannot_delete_last_super_admin'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', admin_copy('admin.users.delete_success'));
    }

    private function isLastSuperAdminRemoval(User $user, string $newRole): bool
    {
        if ($newRole === AccessControl::SUPER_ADMIN_ROLE) {
            return false;
        }

        if (! $user->hasRole(AccessControl::SUPER_ADMIN_ROLE)) {
            return false;
        }

        if ((int) $user->id !== (int) auth()->id()) {
            return $this->superAdminCount() <= 1;
        }

        return $this->superAdminCount() <= 1;
    }

    private function superAdminCount(): int
    {
        return User::role(AccessControl::SUPER_ADMIN_ROLE)->count();
    }

    private function redirectRouteForCurrentUser(): string
    {
        $currentUser = auth()->user()?->fresh();

        if ($currentUser && $currentUser->can(AccessControl::USER_VIEW)) {
            return 'admin.users.index';
        }

        return 'admin.dashboard';
    }
}
