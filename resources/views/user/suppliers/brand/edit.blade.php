@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.update_brand')</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('brand.update', $brand->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="name">@lang('contents.brand_name')</label>
                                        <input type="text" name="name" value="{{ old('name') ?? $brand->name }}" class="form-control" id="name" placeholder="Name">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12 require">
                                        <label for="status">@lang('contents.status')</label>
                                        <select name="active" id="status" class="form-control">
                                            <option value="" disabled selected>Select Status</option>
                                            @foreach(config('coderill.common.input_field.active') as $key => $value)
                                                <option {{ ($brand->active == $key) ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                 <div class="text-right">
                                    <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                    <button type="submit" class="btn btn-primary">@lang('contents.save_changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
