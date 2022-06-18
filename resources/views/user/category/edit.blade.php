@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Update Category</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('category.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="name">Category Name</label>
                                        <input type="text" name="name" value="{{ old('name') ?? $category->name }}" class="form-control" id="name" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="description">Category Description</label>
                                        <textarea name="description" class="form-control" id="description" rows="5">{{ old('description') ?? $category->description }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-1">Status</label>
                                    <div class="col-sm-auto">
                                        @foreach(config('coderill.common.input_field.active') as $key => $value)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" {{ ($category->active == $key) ? 'checked' : '' }} type="radio" name="active" id="status-{{ $loop->iteration }}" value="{{ $key }}">
                                                <label class="form-check-label" for="status-{{ $loop->iteration }}">{{ $value }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                 <div class="text-right">
                                    <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
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
