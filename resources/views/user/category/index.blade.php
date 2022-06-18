@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.category_records')</h5>

                        <div class="action-area print-none" role="group" aria-label="Action area">
                            <button href="{{ route('category.create') }}" data-toggle="modal" data-target="#newCategoryModal" class="btn btn-primary" title="Create new category">
                                <i class="fa fa-plus"></i>
                            </button>
                            <a href="{{ route('category.viewTrashed') }}" title="view trashed" class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </div>

                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.category_name')</th>
                                <th>@lang('contents.status')</th>
                                <th class="text-right">@lang('contents.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $category->name }}</td>
                                    <td class="text-{{ ($category->active) ? 'success' : 'danger' }}">
                                        {{ ($category->active) ? 'Active' : 'Inactive' }}
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('category.edit', $category->id) }}" class="btn btn-sm btn-primary" title="Change category information.">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>

                                        <a href="{{ route('category.toggleActive', $category->id) }}" class="btn btn-sm btn-{{ ($category->active) ? 'warning' : 'success' }}">
                                            <i class="fa fa-{{ ($category->active) ? 'ban' : 'check-circle-o' }}"></i>
                                        </a>

                                        <a href="{{ route('category.index') }}" class="btn btn-sm btn-danger" title="Trash" onClick="if(confirm('Are you sure want to move trashed this record?')){event.preventDefault();document.getElementById('delete-form-{{ $category->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('category.destroy', $category->id) }}" method="post" id="delete-form-{{ $category->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">@lang('contents.no_category_available')</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- paginate -->
                        <div class="float-right mx-2">
                            {{ $categories->links() }}
                        </div>

                        {{--Modal--}}
                        <div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('category.store') }}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="insertModalLabel">Create a new category </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group required">
                                                <label for="name">Category Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" id="name" placeholder="Enter category name">
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Category Description</label>
                                                <textarea name="description" class="form-control" id="description" rows="5">{{ old('description') }}</textarea>
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
