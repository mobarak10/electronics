<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

use App\Models\Business;
use App\Models\Admin;
use Auth;

class SettingsController extends Controller {

    private $meta = [
        'title' => 'Settings',
        'menu' => 'settings',
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
    public function index()
    {
       $businesses = Business::all();
       
       if (request()->search) {
           $settings = Setting::where('business_id', request()->business_id)->get(['meta_key', 'meta_value'])->map(function ($item){
               if ($item->meta_key == 'thumbnail'){
                   return [$item->meta_key => $item->media->real_path];
               }else{

                   return [$item->meta_key => $item->meta_value];
               }
           })->collapse();
           $busines = Business::where('id', request()->business_id)->first();
       }
        return view('admin.setting.index', compact('settings', 'businesses', 'busines'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $businesses = Business::all();
        return view('admin.setting.create', compact('businesses'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $setting = Setting::all();

        $metas = $request->validate([
            'phone'       => 'required|max:45',
            'address'     => 'required|string',
            'email'       => 'required|email',
            'thumbnail'   => 'required|integer'
        ]);
        
        foreach ($metas as $key => $value){
            $data["meta_key"] = $key;
            $data["meta_value"] = $value;
            $data['business_id'] = $request->business_id;
            $data['meta_description'] = $request->description;
            Setting::create($data);
        }
        
        return redirect()->back()->with('success', 'Data inserted successfully!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $setting = Business::find($id);
        dd($setting->getMetaValue('phone'));
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
