@extends('layouts.user')

@section('title', __('contents.brand'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.brand_records')</h5>

                        <div class="action-area print-none" role="group" aria-label="Action area">
                            <button href="{{ route('brand.create') }}" data-toggle="modal" data-target="#newBrandModal" class="btn btn-primary" title="Create new brand">
                                <i class="fa fa-plus"></i>
                            </button>
                            <a href="{{ route('brand.viewTrashed') }}" title="view trashed" class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.brand_name')</th>
                                <th>@lang('contents.status')</th>
                                <th class="text-right">@lang('contents.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $brand->name }}</td>
                                    <td class="text-{{ ($brand->active) ? 'success' : 'danger' }}">
                                        {{ ($brand->active) ? 'Active' : 'Inactive' }}
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('brand.edit', $brand->id) }}" class="btn btn-sm btn-primary" title="Change brand information.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('brand.toggleActive', $brand->id) }}" class="btn btn-sm btn-{{ ($brand->active) ? 'warning' : 'success' }}" title="Click to {{ ($brand->active) ? 'deactivate' : 'activate' }}">
                                            <i class="fa fa-{{ ($brand->active) ? 'ban' : 'check-circle-o' }}"></i>
                                        </a>
                                        <a href="{{ route('brand.index') }}" class="btn btn-sm btn-danger" title="Trash" onClick="if(confirm('Are you sure want to move trashed this record?')){event.preventDefault();document.getElementById('delete-form-{{ $brand->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('brand.destroy', $brand->id) }}" method="post" id="delete-form-{{ $brand->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20" class="text-center">No brand available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $brands->links() }}
                        </div>

                        {{--Modal--}}
                        <div class="modal fade" id="newBrandModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('brand.store') }}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="insertModalLabel">@lang('contents.create_a_newbrand')</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group require">
                                                <label for="name">@lang('contents.brand_name')</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" placeholder="Enter brand name">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('contents.close')</button>
                                            <button type="submit" class="btn btn-primary">@lang('contents.save')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
