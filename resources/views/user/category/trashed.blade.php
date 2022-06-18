@extends('layouts.user')

@section('title', 'Trashed Category')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Trashed Category</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('category.index') }}" class="btn btn-primary" title="Show All">
                                <i class="fa fa-list"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.category_name')</th>
                                <th class="text-right">@lang('contents.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($trashedCategories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $category->name }}</td>

                                    <td class="text-right">
                                        <a
                                            href="{{ route('category.viewTrashed', $category->id) }}"
                                            class="btn btn-sm btn-primary"
                                            title="Restore Category."
                                            onClick="if(confirm('Are you sure want to restore this record?'))
                                            {event.preventDefault();document.getElementById('restore-form-{{ $category->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-repeat" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('category.restore', $category->id) }}" method="post" id="restore-form-{{ $category->id }}" style="display: none;">
                                            @csrf
                                            @method('POST')
                                        </form>

                                        <a href="{{ route('category.viewTrashed') }}" class="btn btn-sm btn-danger" title="Trash" onClick="if(confirm('Are you sure want to permanently delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $category->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('category.forceDelete', $category->id) }}" method="post" id="delete-form-{{ $category->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Empty Trashed</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
