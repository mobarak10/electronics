<?php

namespace App\Http\Controllers\Admin;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    private $meta = [
        'title' => 'Media',
        'menu' => 'media',
        'submenu' => ''
    ];

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->meta['submenu'] = 'list';

        $media = Media::latest()->get();

        return view('admin.media.index', compact('media'))
            ->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->meta['submenu'] = 'add';
        return view('admin.media.create')
            ->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'media' => 'file|max:5000'
        ]);

        $file = $request->file('media');
        $data['code'] = now()->timestamp . rand(100, 999);
        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['extension'] = $file->getClientOriginalExtension();
        $data['mime_type'] = $file->getMimeType();
        $data['size'] = $file->getSize();
        $data['file_path'] = $file->store('upload/media', 'public'); //store in public disk
        $data['real_path'] = 'public/storage/' . $data['file_path']; //real path of file
        $data['absolute_path'] = asset($data['real_path']);

        $medium = Media::create($data);

        return redirect()->back()->withSuccess('Media uploaded successfully. File Title: ' . $medium->title . ' and Code is: ' . $medium->code);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $media = Media::find($id);

        Storage::delete($media->file_path);

        $media->delete();

        return redirect()->back()->withSuccess('Media deleted successfully');
    }
}
