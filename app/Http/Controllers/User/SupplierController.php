<?php

namespace App\Http\Controllers\User;

use App\Imports\SupplierImport;
use App\Models\Party;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    private $meta = [
        'title'   => 'Supplier',
        'menu'    => 'setting',
        'submenu' => ''
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->meta['submenu'] = 'list';

        $business_id = Auth::user()->business_id;
        $suppliers = Party::where('business_id', $business_id)->paginate(15);
        $total = Party::where('business_id', $business_id)->sum('balance');

        if (request()->search) {
            // set conditions
            $where = [
                ['business_id', '=', $business_id]
            ];

            foreach (request()->condition as $input => $value) {
                // return $input;
                if ($value != null) {
                    if ($input == 'name') {
                        $where[] = [$input, 'like', '%' . $value . '%'];
                    } else {
                        $where[] = [$input, '=', $value];
                    }
                }
            }

            $suppliers = Party::where($where)->paginate(15);
        }

        return view('user.suppliers.index', compact('suppliers', 'total'))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';

        return view('user.suppliers.create')
            ->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $suppliers = Party::all();
        $request->validate([
            'balance_status' => 'required'
        ]);

        foreach ($suppliers as $supplier) {
            if ($request->phone == $supplier->phone) {
                return back()->with('error', 'Supplier already exist for this mobile number!!!');
                // return session()->flash('error', 'Supplier already exist for this mobile number!!!');
            }
        }

        $data = $request->validate([
            'name'           => 'required|string|max:191',
            'phone'          => 'required|max:45',
            'email'          => 'nullable|email',
            'balance'        => 'nullable',
            'address'        => 'nullable|string',
            'division'       => 'nullable|string',
            'district'       => 'nullable|string',
            'thana'          => 'nullable|string',
            'description'    => 'nullable|string',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->image) {
            //Media
            $file                  = $request->file('image');
            $datas['code']          = now()->timestamp . rand(100, 999);
            $datas['title']         = $request->name;
            $datas['description']   = $request->description;
            $datas['extension']     = $file->getClientOriginalExtension();
            $datas['mime_type']     = $file->getMimeType();
            $datas['size']          = $file->getSize();
            $datas['file_path']     = $file->store('upload/media', 'public'); //store in public disk
            $datas['real_path']     = 'public/storage/' . $datas['file_path']; //real path of file
            $datas['absolute_path'] = asset($datas['real_path']);

            $medium = Media::create($datas);

            $data['thumbnail'] = $medium->code;
        }


        $metas = $request->validate([
            'contact_person'       => 'nullable|string',
            'contact_person_phone' => 'nullable|string'
        ]);

        $data['code']  = 'PRT' . str_pad(Party::withTrashed()->max('id') + 1, 8, '0', STR_PAD_LEFT);
        $data['business_id'] = Auth::user()->business_id;

        if ($request->balance_status == 'payable') {
            $data['balance'] = -abs($data['balance']);
        } elseif ($request->balance_status == 'receivable') {
            $data['balance'] = abs($data['balance']);
        }

        $party = Party::create($data); //create party

        foreach ($metas as $key => $meta) {
            $party->metas()->create(['meta_key' => $key, 'meta_value' => $meta]);
        }

        return redirect()->back()->withSuccess('Suppliers created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Party $supplier)
    {
        $this->meta['aside'] = false;
        $this->meta['header'] = false;
        $media =Media::where('code', $supplier->thumbnail)->first();

        return view('user.suppliers.show', compact('supplier','media'))->with($this->meta);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Party $supplier)
    {
        $media =Media::where('code', $supplier->thumbnail)->first();
        return view('user.suppliers.edit', compact('supplier','media'))
            ->with($this->meta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Party $supplier)
    {
        $request->validate([
            'balance_status' => 'required'
        ]);

        $data = $request->validate([
            'name'        => 'required|string|max:191',
            'phone'       => 'required|max:45',
            'email'       => 'nullable|email',
            'balance'     => 'nullable',
            'address'     => 'nullable|string',
            'description' => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->image) {

            $media = Media::where('code', $supplier->thumbnail)->first();
            if ($media) {
                unlink(storage_path('app/public/'.$media->file_path));
                // $media->delete();
            }
            //Media
            $file                  = $request->file('image');
            // $datas['code']          = now()->timestamp . rand(100, 999);
            $datas['title']         = $request->name;
            $datas['description']   = $request->description;
            $datas['extension']     = $file->getClientOriginalExtension();
            $datas['mime_type']     = $file->getMimeType();
            $datas['size']          = $file->getSize();
            $datas['file_path']     = $file->store('upload/media', 'public'); //store in public disk
            $datas['real_path']     = 'public/storage/' . $datas['file_path']; //real path of file
            $datas['absolute_path'] = asset($datas['real_path']);

            if ($media) {
                $media->update($datas);
            }else{
            $datas['code']          = now()->timestamp . rand(100, 999);
            $medium = Media::create($datas);

            $data['thumbnail'] = $medium->code;
            }
        }

        $metas = $request->validate([
            'contact_person'       => 'nullable|string',
            'contact_person_phone' => 'nullable|string'
        ]);

        if ($request->balance_status == 'payable') {
            $data['balance'] = -abs($data['balance']);
        } elseif ($request->balance_status == 'receivable') {
            $data['balance'] = abs($data['balance']);
        }

        $supplier->update($data); //update supplier

        foreach ($metas as $key => $value) {
            $supplier->metas()->updateOrCreate(['meta_key' => $key], ['meta_value' => $value]);
        }

        return redirect()->route('supplier.index')->withSuccess('Supplier updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Party $supplier)
    {
        $supplier->delete();

        return redirect()->back()->withSuccess('Supplier deleted successfully');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|ViewAlias
     */
    public function viewTrashed()
    {
        $trashedSuppliers = Party::suppliers()->onlyTrashed()->get();

        return view('user.suppliers.trashed', compact('trashedSuppliers'))->with($this->meta);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        $supplier = Party::suppliers()->onlyTrashed()->findOrFail($id);
        $supplier->forceDelete();
        return redirect()->back()->withSuccess('Party delete successfully');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $supplier = Party::suppliers()->onlyTrashed()->findOrFail($id);
        $supplier->deleted_at = null;
        $supplier->save();

        return redirect()->back()->withSuccess('Party restore successfully');
    }

    /*------------------------AJAX Request Methods Start------------------------*/

    /**
     * Get Party details
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function partyDetails(Request $request)
    {
        return response(Party::find($request->id), 200);
    }

    /**
     * Get Brands from Suppliers
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function brands(Request $request)
    {
        $brands = Party::where('id', $request->supplierId)->first()->brands;
        return response()->json($brands, 200);
    }

    /**
     * Get all supplier
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function allActiveSuppliers()
    {
        $business_id = Auth::user()->business_id;
        return response(Party::where('business_id', $business_id)->active()->orderBy('created_at', 'DESC')->get(), 200);
    }

    // status change
    public function changeSuppliersStatus($id)
    {
        $party = Party::find($id);
        $party->active = ($party->active) ? 0 : 1;
        $party->save();

        return redirect()->back()->withSuccess($party->name . ' Supplier successfully ' . ($party->active == true ? 'Activated' : 'Deactivated'));
    }


    /**
     * Get all active product of supplier
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allActiveProducts(Request $request)
    {
        return response()->json(Party::find($request->id)->products()->with('warehouses')->active()->get(), 200);
    }


    /*-------------------------AJAX Request Methods End------------------------*/

    /**
     * @return Application|Factory|View
     */
    public function excel()
    {
        return view('user.suppliers.excel')->with($this->meta);
    }

    public function import()
    {
        Excel::import(new SupplierImport(), request()->file('supplier_excel'));

        return redirect(route('supplier.index'))->with('success', 'All good!')->with($this->meta);
    }
}
