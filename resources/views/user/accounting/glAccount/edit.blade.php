@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-12 py-3">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"> 
                    <h5 class="m-0">@lang('contents.update_gl_account_details')</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('glAccount.index') }}" class="btn btn-primary" title="List of account">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('glAccount.update', $gl_account->id) }}" method="post">
                        @csrf
                        @method('PATCH')

                        <div class="form-group col-md-12 required">
                            <label for="name">@lang('contents.account_name')</label>
                            <input type="text" class="form-control" name="name" value="{{ $gl_account->name }}" required>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="description">@lang('contents.description')</label>
                            <textarea class="form-control" name="description">{{ $gl_account->description }}</textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="row">
                                <label class="col-sm-1">@lang('contents.status')</label>
                                <div class="col-sm-auto">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" {{ ($gl_account->status == 1) ? 'checked' : '' }} type="radio" name="status" id="status-active" value="1">
                                        <label class="form-check-label" for="status-active">Active</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" {{ ($gl_account->status == 0) ? 'checked' : '' }} type="radio" name="status" id="status-inactive" value="0">
                                        <label class="form-check-label" for="status-inactive">Inactive</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                            <button type="submit" class="btn btn-primary">@lang('contents.save_changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main-panel ends -->
@endsection
