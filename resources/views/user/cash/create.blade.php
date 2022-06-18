@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.create_a_newcash')</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('cash.index') }}" class="btn btn-primary" title="All Cash">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('cash.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="title">@lang('contents.cash_name') </label>
                                    <input type="text" value="{{ old('title') }}" class="form-control" id="title" name="title" placeholder="Enter Warehouse name" required>
                                </div>

                                <div class="form-group col-md-6 required">
                                    <label for="amount">@lang('contents.initial_amount') </label>
                                    <input type="number" value="{{ old('amount') }}" name="amount" class="form-control" id="amount" placeholder="0.00" step="any" required>
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
    <!-- main-panel ends -->
@endsection
