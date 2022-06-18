@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="m-0">@lang('contents.update_cash_record')</h5>
                            <small></small>
                        </div>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('cash.index') }}" class="btn btn-primary" title="All cash">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('cash.update', $cash->id) }}" method="post">
                            @csrf
                            @method('PATCH')

                            <div class="form-group required">
                                <label for="title">@lang('contents.cash_name') </label>
                                <input type="text" name="title" value="{{ $cash->title }}" class="form-control" id="title" placeholder="Cash title" required>
                            </div>

                            <div class="form-group required">
                                <label for="amount">@lang('contents.initial_amount') </label>
                                <input type="number" name="amount" value="{{ $cash->amount }}" class="form-control" id="amount" placeholder="0.00" step="any" required>
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
@endsection
