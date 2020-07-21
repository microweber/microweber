<?php

namespace MicroweberPackages\Role\Http\Controllers;

 use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\User\User;
use Spatie\Permission\Models\Role;

class UserController extends AdminController
{
    use ValidatesRequests;

    /**
     * @var User;
     */
    protected $model;

    public function __construct()
    {
        parent::__construct();

        $this->model = User::class;
        $fileds = ['email', 'name'];
        array_unshift($fileds, config('admin.auth.login.username', 'email'));
        View::share('user_name_fileds', array_filter(array_unique($fileds)));
    }

    public function index(Request $request)
    {
        $model = $this->model::query();
        if (!Auth::user()->hasRole('superadmin'))
            $model->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'superadmin');
            });

        $search = $request->query('search');
        if (!empty($search)) {
            $model->where(function ($query) use ($search) {
                $query->where('email', 'like', "%{$search}%");
                $query->orWhere('name', 'like', "%{$search}%");
                $query->orWhereHas('metas', function ($build) use ($search) {
                    $build->where('meta_value', 'like', "%{$search}%");
                });
            });
        }

        $results = $model->paginate();

        return $this->view('role::admin.users.index', compact('results'));
    }

    public function resetPassword(Request $request){
        $input = $request->validate([
            'id' => ['required'],
            'password' => ['required', 'string'],
        ]);

        $model = $this->model::query()->findOrFail($input['id']);
        $model->password = $input['password'];
        $model->save();
    }

    public function create()
    {
        $passwrod = Str::random(32);
        session(['create_password' => $passwrod]);
        return $this->_form(new $this->model())->with('password', $passwrod);
    }

    protected function _form($model)
    {
        $role = Role::query();
        /*if (!Admin::user()->hasRole('superadmin'))
            $role->whereIn('name', Admin::user()->roles->pluck('name')->all());*/

        $roles = $role->get();

        return $this->view('add_edit', compact('model', 'roles'));
    }

    public function edit($id)
    {
        if (Auth::user()->id != $id)
            $this->authorize('edit_user');

        $model = $this->model::findOrFail($id);

        return $this->_form($model);
    }

    public function changePassword()
    {
        return $this->view('change_password');
    }

    public function savePassword(Request $request)
    {
        $input = $request->validate([
            'old_password' => ['required', 'string', 'max:255',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail(trans('admin.old_password').trans('admin.error'));
                    }
                },],
            'password' => array_merge(['required', 'string', 'confirmed'], config('admin.auth.password.rule', [])),
        ], [], [
            'old_password' => trans('admin.old_password')
        ]);

        Auth::user()->password = $input['password'];
        Auth::user()->save();

        return redirect(route('admin.logout'));
    }

    public function store()
    {
        $model = new $this->model();
        $model->password = session('create_password');
        abort_unless($model->password, 422, "password is invald.");
        return $this->save($model);
    }

    public function update($id)
    {
        if (Admin::user()->id != $id)
            $this->authorize('edit_user');

        $model = $this->model::findOrFail($id);
        return $this->save($model);
    }

    protected function save($model)
    {
        $request = request()->replace(array_filter(request()->all()));

        $validates = [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'max:255'],
            'metas' => 'array',
            //'role' => Rule::requiredIf(Admin::user()->can('edit_role')),
            'role.*' => function ($attribute, $value, $fail) {
                /*if (!Admin::user()->hasRole('superadmin') && !in_array($value, Admin::user()->roles->pluck('name')->all())) {
                    $fail($attribute . ' is invalid.');
                }*/
            },
            //'password' => [Rule::requiredIf(!$model->id), 'min:6', 'confirmed']
        ];

        $login_filed = config('admin.auth.login.username', 'email');
        $validates[$login_filed] = array_merge($validates[$login_filed], ['required', Rule::unique('users')->ignore($model->id)]);

        $input = $request->validate($validates);

        $roles = array_filter($request->input('role', []));

        $model->fill($input);

        $model->save();

        if (!empty($roles) && Admin::user()->can('edit_role')) {
            $model->roles()->detach();

            if (!Admin::user()->hasRole('superadmin'))
                $roles = array_diff($roles, ['superadmin']);

            $roles = collect($roles)
                ->flatten()
                ->map(function ($role) {
                    if (empty($role)) {
                        return false;
                    }

                    $role = explode(':', $role);
                    return Role::findByName(...$role);
                })
                ->filter(function ($role) {
                    return $role instanceof \Spatie\Permission\Contracts\Role;
                })
                ->map->id
                ->all();

            $model->roles()->sync($roles, false);
        }

        $url = Admin::user()->can('view_user') ? Admin::action('index') : Admin::action('edit', $model->id);

        return redirect($url)->with('success', trans('admin.save_succeeded'));
    }

    public function destroy($id, Request $request)
    {
        $ids = $id ? [$id] : $request->input('ids');

        foreach ($ids as $id) {
            if ($id == 1) continue;
            $model = $this->model::findOrFail($id);
            $model->delete();
        }

        return response([
            'message' => trans('admin.delete_succeeded'),
        ]);
    }

    protected function abilitiesMap()
    {
        return [
            'index' => 'view_user',
            'create' => 'add_user',
            'store' => 'add_user',
            'destroy' => 'delete_user',
            'resetPassword' => 'reset_password',
        ];
    }
}
