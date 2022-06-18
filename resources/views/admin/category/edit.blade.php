@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.update_category')</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('admin.category.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="name">@lang('contents.category_name')</label>
                                        <input type="text" name="name" value="{{ old('name') ?? $category->name }}" class="form-control" id="name" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="description">@lang('contents.category_description')</label>
                                        <textarea name="description" class="form-control" id="description" rows="5">{{ old('description') ?? $category->description }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-1">@lang('contents.status')</label>
                                    <div class="col-sm-auto">
                                        @foreach(config('coderill.common.input_field.active') as $key => $value)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" {{ ($category->active == $key) ? 'checked' : '' }} type="radio" name="active" id="status-{{ $loop->iteration }}" value="{{ $key }}">
                                                <label class="form-check-label" for="status-{{ $loop->iteration }}">{{ $value }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">@lang('contents.update')</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
