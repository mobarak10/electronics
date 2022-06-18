@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Loan Account Edit</h5>
                        <div class="btn-group" role="group" area-level="Action area">
                            <a href="{{ route('loanAccount.index') }}" class="btn btn-primary" title="All Loan Account">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('loanAccount.update', $account->id) }}" method="POST" accept-charset="utf-8">
                            @csrf
                            @method('PATCH')
                            <div class="form-row">
                                <div class="form-group col-md-12 required">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" placeholder="Enter bank name" class="form-control" value="{{ $account->name }}" id="name">
                                </div>

                                <div class="col-md-12 required">
                                    <label for="phone" class="form-label required">Phone</label>
                                    <input type="text" required class="form-control" name="phone" value="{{ $account->phone }}" id="phone" placeholder="Enter phone number"/>
                                </div>

                                <div class="col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" name="address" id="address" placeholder="Enter address">{{ $account->address }}</textarea>
                                </div>
                            </div>
                            <div class="text-right mt-2">
                                <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                <button type="submit" class="btn btn-primary left">@lang('contents.save_changes')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
