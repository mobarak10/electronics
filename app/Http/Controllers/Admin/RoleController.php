<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\Menu;
use App\Models\Permission;

class RoleController extends Controller
{

    private $meta = [
        'title' => 'Roles',
        'menu' => 'roles',
        'submenu' => ''
    ];

    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.role.index', compact('roles', 'permissions'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $menus = Menu::all();
        return view('admin.role.create', compact('menus'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50|unique:roles',
            'permission.*'  => 'required',
            'description' => 'nullable'
        ]);

        $role = new Role;
        $role->name = $request->name;
        $role->slug = str_replace(' ', '-', strtolower($request->name));
        $role->description = $request->description;
        $role->save();

        $role->permissions()->sync($request->permission);

        return redirect(route('admin.role.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $permissions = [];
        $role = Role::find($id);

        foreach ($role->permissions as $permission) {
            $permissions[$permission->menu->slug][] = [
                'menu' => $permission->menu->name,
                'name' =>  $permission->name,
                'slug' =>  $permission->slug,
            ];
        }

        return view('admin.role.show', compact('role', 'permissions'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $role = Role::find($id);
        $permissions = Permission::all();

        return view('admin.role.edit', compact('role', 'permissions'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required|max:50'
        ]);

        $role = Role::find($id);
        $role->name = $request->name;
        $role->slug = str_replace(' ', '-', strtolower($request->name));
        $role->description = $request->description;
        $role->save();

        $role->permissions()->sync($request->permission);

        return redirect(route('admin.role.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Role::where('id', $id)->delete();

        return redirect()->back();
    }
}
