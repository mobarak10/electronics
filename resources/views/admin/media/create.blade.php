@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                @if(Session::has('medium'))
                <div class="alert alert-primary" role="alert">
                    {{ Session::get('medium')->title }} has been uploaded and code is : <strong>{{ Session::get('medium')->code }}</strong>
                </div>
                @endif
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.upload_new_media')</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="title">@lang('contents.file_title')</label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" id="title" placeholder="@lang('contents.media_title')">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="description">@lang('contents.file_description')</label>
                                        <textarea name="description" class="form-control" placeholder="@lang('contents.enter_file_description')" id="description" rows="5">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="file">@lang('contents.logo')</label>
                                        <input type="file" name="media" class="form-control" id="file">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">@lang('contents.upload')</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
