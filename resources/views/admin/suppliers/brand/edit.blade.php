@extends('layouts.admin')

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
                            <form action="{{ route('admin.brand.update', $brand->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="supplier-name">@lang('contents.supplier_name')</label>
                                        <select name="party_id" id="supplier-name" class="form-control">
                                            <option value="" disabled selected>Select Company/Supplier Name</option>
                                            @forelse($suppliers as $supplier)
                                                <option {{ (old('party_id') ?? $brand->party_id == $supplier->id) ? 'selected' : '' }} value="{{ $supplier->id }}" >{{ $supplier->name }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 required">
                                        <label for="name">@lang('contents.brand_name')</label>
                                        <input type="text" name="name" value="{{ old('name') ?? $brand->name }}" class="form-control" id="name" placeholder="Name">
                                    </div>
                                </div>

                                <div class="form-group require">
                                    <label>@lang('contents.categories')</label>
                                    @foreach($categories as $category)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" name="categories[]" {{ in_array($category->id, $brand->categories->pluck('id')->all()) ? 'checked' : '' }} type="checkbox" id="category-{{ $loop->iteration }}" value="{{ $category->id }}">
                                            <label class="form-check-label" for="category-{{ $loop->iteration }}">{{ $category->name }}</label>
                                        </div>
                                    @endforeach
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
                                <button type="submit" class="btn btn-primary">@lang('contents.update')</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
