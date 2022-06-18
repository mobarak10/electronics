@extends('layouts.user')

@section('title', 'Trashed Product')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Trashed Product</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('product.index') }}" class="btn btn-primary" title="Show All">
                                <i class="fa fa-list"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('contents.product_name')</th>
                                <th class="text-right">@lang('contents.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($trashedProducts as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td>{{ $product->name }}</td>

                                    <td class="text-right">
                                        <a
                                            href="{{ route('product.viewTrashed', $product->id) }}"
                                            class="btn btn-sm btn-primary"
                                            title="Restore Product."
                                            onClick="if(confirm('Are you sure want to restore this record?'))
                                            {event.preventDefault();document.getElementById('restore-form-{{ $product->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-repeat" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('product.restore', $product->id) }}" method="post" id="restore-form-{{ $product->id }}" style="display: none;">
                                            @csrf
                                            @method('POST')
                                        </form>

                                        <a href="{{ route('product.viewTrashed') }}" class="btn btn-sm btn-danger" title="Trash" onClick="if(confirm('Are you sure want to permanently delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $product->id }}').submit();} else {event.preventDefault();}">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>

                                        <form action="{{ route('product.forceDelete', $product->id) }}" method="post" id="delete-form-{{ $product->id }}" style="display: none;">
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
