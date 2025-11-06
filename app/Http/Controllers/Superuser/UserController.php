<?php

namespace App\Http\Controllers\Superuser;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableRequest;
use App\Models\Position;
use App\Models\DivisionModels;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = Position::all();
        $divisions = DivisionModels::all();

        return Inertia::render('Superuser/User/Index')->with([
            'roles' => Role::get(),
            'positions' => $positions,
            'divisions' => $divisions,
            'permissions' => Permission::get(),
        ]);
    }

    /**
     * @param \App\Http\Requests\DataTableRequest $request
     * @return \Illuminate\Http\Response
     */
    public function paginate(DataTableRequest $request)
    {
        $request->validated();

        return User::where(function (Builder $query) use ($request) {
                        $search = '%' . $request->input('search') . '%';
                        $columns = ['name', 'username', 'position_id', 'division_id', 'email', 'email_verified_at', 'created_at', 'updated_at'];

                        foreach ($columns as $column)
                            $query->orWhere($column, 'like', $search);

                        $query->orWhereRelation('permissions', 'name', 'like', $search);
                        $query->orWhereRelation('roles', 'name', 'like', $search);
                        $query->orWhereHas('positions', function ($q) use ($search) {
                            $q->where('position', 'like', $search);
                        });

                        $query->orWhereHas('divisions', function ($q) use ($search) {
                            $q->where('division_name', 'like', $search);
                        });
                    })
                    ->orderBy($request->input('order.key') ?: 'name', $request->input('order.dir') ?: 'asc')
                    ->with(['permissions', 'roles', 'positions', 'divisions'])
                    ->paginate($request->per_page ?: 10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'position_id.*' => 'nullable|integer',
            'division_id.*' => 'nullable|integer',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'roles.*' => 'nullable|integer|exists:roles,id',
            'permissions.*' => 'nullable|integer|exists:permissions,id',
        ]);

        $post['password'] = Hash::make($post['password']);

        if ($user = User::create($post)) {
            $user->permissions()->sync($request->input('permissions', []));
            $user->roles()->sync($request->input('roles', []));
            $user->position_id = $request->input('position_id', null);
            $user->division_id = $request->input('division_id', null);
            $user->email_verified_at = now();
            $user->save();

            return redirect()->back()->with('success', __(
                'user `:name` has been created', [
                    'name' => $user->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t create user'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'username' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'position_id' => 'nullable|int',
            'division_id' => 'nullable|int',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'password_confirmation' => ['nullable', 'same:password'],
            'roles.*' => ['nullable', 'integer', 'exists:roles,id'],
            'permissions.*' => ['nullable', 'integer', 'exists:permissions,id'],
        ]);

        if ($user->update($request->only(['name', 'username', 'position_id', 'division_id', 'email']))) {
            $user->permissions()->sync($request->input('permissions', []));
            $user->roles()->sync($request->input('roles', []));

            if ($password = $request->input('password')) {
                $user->update([
                    'password' => Hash::make($password),
                ]);
            }

            return redirect()->back()->with('success', __(
                'user `:name` has been updated', [
                    'name' => $user->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update user'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return redirect()->back()->with('success', __(
                'user `:name` has been deleted', [
                    'name' => $user->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t delete user'
        ));
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\DivisionModels $permission
     * @return \Illuminate\Http\Response
     */
    public function detachDivision(User $user, DivisionModels $division)
    {
        if ($user->division()->detach([$division->id])) {
            return redirect()->back()->with('success', __(
                'division `:division from user `:user` has been detached`', [
                    'division' => $division->division_name,
                    'user' => $user->name,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t detach division `:division` from user `:user`', [
                'division' => $division->division_name,
                'user' => $user->name,
            ]
        ));
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Position $permission
     * @return \Illuminate\Http\Response
     */
    public function detachPosition(User $user, Position $position)
    {
        if ($user->positions()->detach([$position->id])) {
            return redirect()->back()->with('success', __(
                'position `:position from user `:user` has been detached`', [
                    'position' => $position->position,
                    'user' => $user->name,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t detach position `:position` from user `:user`', [
                'position' => $position->position,
                'user' => $user->name,
            ]
        ));
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function detachPermission(User $user, Permission $permission)
    {
        if ($user->permissions()->detach([$permission->id])) {
            return redirect()->back()->with('success', __(
                'permission `:permission from user `:user` has been detached`', [
                    'permission' => $permission->name,
                    'user' => $user->name,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t detach permission `:permission` from user `:user`', [
                'permission' => $permission->name,
                'user' => $user->name,
            ]
        ));
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function detachRole(User $user, Role $role)
    {
        if ($user->roles()->detach([$role->id])) {
            return redirect()->back()->with('success', __(
                'role `:role from user `:user` has been detached`', [
                    'role' => $role->name,
                    'user' => $user->name,
                ]
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t detach role `:role` from user `:user`', [
                'role' => $role->name,
                'user' => $user->name,
            ]
        ));
    }
}
