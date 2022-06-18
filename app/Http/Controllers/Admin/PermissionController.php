<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Menu;
use App\Models\Permission;

class PermissionController extends Controller
{
    private $meta = [
        'title' => 'Permission',
        'menu' => 'permission',
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
    public function index()  {
        $menus = Menu::all();
        $permissions = Permission::groupBy('menu_id')->get();

        return view('admin.permission.index', compact('menus', 'permissions'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = [];

        // validation
        $this->validate($request, [
            'menu' => 'required|max:50',
            'action.*' => 'required',
            'description' => 'nullable|string',
        ]);

        // process data
        foreach($request->action as $action) {
            $data[] = [
                'menu_id' => $request->menu,
                'name' => config('coderill.actions')[$action],
                'slug' => $action,
                // 'description' => $request->description,
            ];
        }

        // check data is exist
        $exist = Permission::where('menu_id', $request->menu)->first();

        if (!$exist) {
            // insert and flash message
            if(Permission::insert($data)) {
                session()->flash('success', 'New permission successfully create.');
            }
        }else{
            return redirect()->back()->withError('Permission allredy exist');
        }

        // view
        return redirect(route('admin.permission.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $permissions = Permission::where('menu_id', $id)->get();
        return view('admin.permission.show', compact('permissions'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
