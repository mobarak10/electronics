<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User\User;
use App\Models\Role;
use App\Models\Business;

class EmployeeController extends Controller
{
    private $meta = [
        'title' => 'Employee',
        'menu' => 'employee',
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
        $users = User::all();
        return view('admin.employee.index', compact('users'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $businesses = Business::all();

        return view('admin.employee.create', compact('businesses'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request,[
            'password' => 'nullable|string|min:6|confirmed',
            'business_id' => 'required|integer',
        ], [
            'password.required' => 'Confirmation password does not match'
        ]);

        // meta data and validation
        $metas = $request->validate([
            'dob' => 'nullable|string',
            'father_name' => 'nullable|string',
            'mother_name' => 'nullable|string',
            'contact_person_number' => 'nullable|string',
            'nid_number' => 'nullable|string',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'basic_salary' => 'required|numeric',
            'home_allowance' => 'numeric',
            'medical_allowance' => 'numeric',
            'transport_allowance' => 'numeric',
        ]);

        $employee = new User;

        $employee->name = $request->name;
        $employee->phone = $request->phone_no;
        $employee->username = $request->user_name;
        $employee->email = $request->email;
        $employee->thumbnail = $request->thumbnail;
        $employee->business_id = $request->business_id;
        $employee->account_type = $request->account_type;
        $employee->password = Hash::make($request->password);
        // $employee->password = bcrypt($value, $options = []);

        if ($employee->save()) {
            // create meta data
            foreach ($metas as $key => $value){
                $employee->metas()->updateOrCreate(['meta_key' => $key], ['meta_value' => $value]);
            }

            // set relation
            $employee->roles()->attach($request->roles);

            session()->flash('success', 'Employee added successfully.');

            return redirect(route('admin.employee.index'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $record = User::find($id);
        $accessLogs = $record->accessLogs()->limit(30)->get();

        return view('admin.employee.show', compact('record', 'accessLogs'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::find($id);
        $roles = Role::all();
        $businesses = Business::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.employee.edit', compact('user', 'roles', 'userRoles', 'businesses'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // dd($request->all());

        // get user
        $employee = User::findOrFail($id);

        // update primary data
        if($request->section == 'basic') {
            // validation
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                // 'username' => 'required|string|unique:users,username,' . $id,
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $id,
                'thumbnail' => 'nullable|string',
                'status' => 'nullable|integer',
                'business_id' => 'required|integer',
            ]);

            // set status
            $data['status'] = ($request->status) ? $request->status : 0;

            // meta data and validation
            $metas = $request->validate([
                'dob' => 'nullable|string',
                'present_address' => 'nullable|string',
                'permanent_address' => 'nullable|string',
                'father_name' => 'nullable|string',
                'mother_name' => 'nullable|string',
                'nid_number' => 'nullable|string',
                'contact_person_number' => 'nullable|string',
                'basic_salary' => 'required|numeric'
            ]);

            // change employee data
            $employee->update($data);

            // update meta data
            foreach ($metas as $key => $value){
                $employee->metas()->updateOrCreate(['meta_key' => $key], ['meta_value' => $value]);
            }

            // set relation
            $employee->roles()->sync($request->roles);
        }

        // update password data
        if($request->section == 'password') {
            $data = $request->validate([
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            $data['password'] = bcrypt($request->password);

            // change employee password
            $employee->update($data);
        }

        // flash data
        session()->flash('success', 'Employee account update successfully.');

        // view
        return redirect(route('admin.employee.index'));
    }

    // status change
    public function changeEmployeeStatus($id){
        $employee = User::find($id);
        $employee->status = ($employee->status) ? 0 : 1;
        $employee->save();

        return redirect()->back()->withSuccess($employee->name . ' Employee successfully ' .($employee->status == true ? 'Activated' : 'Deactivated'));
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
