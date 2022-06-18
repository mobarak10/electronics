<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Models\User\User;
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
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return redirect(route('account.show', Auth::user()->id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return redirect(route('account.show', Auth::user()->id));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $this->meta['header'] = false;

        $record = User::find($id);
        return view('user.account.show', compact('record'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::find($id);
        return view('user.account.edit', compact('user'))->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        // update basic data
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

            // change user data
            $user->update($data);

            // update meta data
            foreach ($metas as $key => $value){
                $user->metas()->updateOrCreate(['meta_key' => $key], ['meta_value' => $value]);
            }
        }

        // update password data
        if($request->section == 'password') {
            $data = $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);

            $data['password'] = bcrypt($request->password);

            // change user password
            $user->update($data);
        }

        // flash data
        session()->flash('success', 'Your account information update successfully.');

        // view
        return redirect(route('account.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        User::where('id', $id)->delete();
        return redirect()->back()->with('message', 'Admin is deleted successfully!');
    }
}
