@extends('layouts.admin')

@section('title', __('contents.cash'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="alert {{ ($total_cash < 0) ? 'alert-danger' : 'alert-success' }}">
                    <strong>@lang('contents.total_cash_bdt') {{ number_format($total_cash, 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="row pt-2 pb-5">
            @foreach($cashes as $key => $cash)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">{{ $cash->title }}</div>

                        <div class="card-body">
                            <h5 class="card-title {{ ($cash->amount < 0) ? 'text-danger' : 'text-primary' }}">@lang('contents.bdt') {{ number_format($cash->amount, 2) }}</h5>
                            <p class="card-text">Last updated at {{ $cash->updated_at->format('j F, Y') }}</p>

                            <a href="{{ route('admin.cash.show', $cash->id) }}" class="btn btn-success text-white" title="Cash details.">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('admin.cash.edit', $cash->id) }}" class="btn btn-success" title="Change cash information.">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('admin.cash.index') }}" class="btn btn-danger float-right" title="Trash" onClick="if(confirm('Are you sure, You want to delete this record?')){event.preventDefault();document.getElementById('delete-form-{{ $cash->id }}').submit();} else {event.preventDefault();}">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>

                            <form action="{{ route('admin.cash.destroy', $cash->id) }}" method="post" id="delete-form-{{ $cash->id }}" style="display: none;">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-md-4">
                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#newCashModal" title="Create new cash" style="height:12.5rem;">
                    <i class="fa fa-plus h2 mb-0"></i>
                    <span class="d-block">@lang('contents.add_new_cash')</span>
                </button>
            </div>

            <div class="col-md-12">
                <!-- paginate -->
                <div class="float-right">
                    {{ $cashes->links() }}
                </div>
            </div>

            <!-- New cash modal start -->
            <div class="modal fade" id="newCashModal" tabindex="-1" role="dialog" aria-labelledby="insertModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('admin.cash.store') }}" method="post">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="insertModalLabel">@lang('contents.create_a_newcash')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group required">
                                    <label for="title">@lang('contents.cash_name') </label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Cash name must in 190 characters" required>
                                </div>

                                <div class="form-group required">
                                    <label for="amount">@lang('contents.initial_amount') </label>
                                    <input type="number" name="amount" class="form-control" id="amount" placeholder="0.00" step="any" required>
                                </div>

                                <div class="form-group required">
                                    <label for="business">@lang('contents.business') </label>
                                    <select name="business_id" class="form-control" id="business">
                                        <option selected disabled>Choose one</option>
                                        @foreach($businesses as $business)
                                            <option value="{{ $business->id }}">{{ $business->name }}</option>
                                        @endforeach
                                    </select>
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
            <!-- New cash modal end -->

        </div>
    </div>
@endsection


