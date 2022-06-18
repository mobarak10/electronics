@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                @if(Session::has('medium'))
                    <div class="alert alert-primary" role="alert">
                        {{ Session::get('medium')->title }} 
                        has been uploaded and code is : 
                        <strong>{{ Session::get('medium')->code }}</strong>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Upload New Media</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="title">File Title</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" id="title" placeholder="Media Title">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="description">File Description</label>
                                        <textarea name="description" class="form-control" placeholder="Enter File Description" id="description" rows="5">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="file">Logo</label>
                                        <input type="file" name="media" class="form-control" id="file">
                                    </div>
                                </div>

                                 <div class="text-right">
                                    <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
