<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    private $meta = [
        'title' => 'Profile',
        'menu' => 'profile',
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
        // return Auth::id();
        $users = Admin::all();
        return view('admin.account.index', compact('users'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.account.create')->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|string|email|max:255',
            'username' => 'required|string|unique:admins',
            'password' => 'required|string|min:6|confirmed',
            'thumbnail' => 'nullable|string',
        ]);

        $data['status'] = 1;
        $data['password'] = bcrypt($request->password);
        
        $metas = $request->validate([
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'nullable|string'
        ],
        [
            'dob' => 'Date of birth'
        ]);

        // create account information
        $admin = Admin::create($data);
        
        // update meta data
        foreach ($metas as $key => $meta){
            $admin->metas()->create(['meta_key' => $key, 'meta_value' => $meta]);
        }

        // flash data
        session()->flash('success', 'New account successfully create.');

        // view
        return redirect(route('admin.account.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $this->meta['header'] = false;

        $record = Admin::find($id);
        $accessLogs = $record->accessLogs()->limit(30)->get();
        return view('admin.account.show', compact('record', 'accessLogs'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = Admin::find($id);
        if (Auth::id() == $user->id) {
            return view('admin.account.edit', compact('user'))->with($this->meta);
        }else{
            // flash data
            session()->flash('error', 'You have not permitted for this action.');

            // view
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $admin = Admin::findOrFail($id);

        if($request->section == 'basic') {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'email' => 'required|string|email|max:255',
                'thumbnail' => 'nullable|string',
                'status' => 'nullable|integer',
            ], [
                'dob' => 'Date of birth'
            ]);

            $data['status'] = ($request->status) ? $request->status : 0;
            
            $metas = $request->validate([
                'dob' => 'nullable|string',
                'address' => 'nullable|string',
                'gender' => 'nullable|string',
            ]);

            // change Admin data
            $admin->update($data);
            
            // update meta data
            foreach ($metas as $key => $value){
                $admin->metas()->updateOrCreate(['meta_key' => $key], ['meta_value' => $value]);
            }
        }

        // update password data
        if($request->section == 'password') {
            $data = $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);

            $data['password'] = bcrypt($request->password);

            // change admin password
            $admin->update($data);
        }

        // flash data
        session()->flash('success', 'Your account information update successfully.');

        // view
        return redirect(route('admin.account.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = Admin::find($id);
        if (Auth::id() == $user->id) {
            $user->delete();
            return redirect()->back()->with('message', 'Admin is deleted successfully!');
        }else{
            // flash data
            session()->flash('error', 'You have not permitted for this action.');

            // view
            return redirect()->back();
        }
    }
}
