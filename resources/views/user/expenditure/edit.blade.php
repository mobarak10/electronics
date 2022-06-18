@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"> 
                    <h5 class="m-0">Edit Expenditure</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('expenditure.update', $expenditure->id) }}" method="post">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="form-group col-md-6 required">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" value="{{ $expenditure->date }}" id="date" name="date" required>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" value="{{ $expenditure->amount }}" id="amount" name="amount" step="any" required>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label>GL Account</label>
                                <select name="gl_account_id" class="form-control" required disabled>
                                    @foreach($glAccounts as $account)
                                        <option value="{{ $account->id }}" {{ $account->id == $expenditure->gl_account_id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label>GL Account</label>
                                <select name="gl_account_head_id" class="form-control" required>
                                    @foreach($expenditure->glAccount->allGLAccountHead as $head)
                                        <option value="{{ $head->id }}" {{ $head->id == $expenditure->gl_account_head_id ? 'selected' : '' }}>
                                            {{ $head->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control">{{ $expenditure->note }}</textarea>
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
