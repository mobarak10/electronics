@extends('layouts.user')

@section('title', $title)

@section('content')
<div class="container">
    <div class="row"> 
        <div class="col-md-12 py-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center"> 
                    <h5 class="m-0">@lang('contents.edit_unit')</h5>

                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('unit.index') }}" class="btn btn-primary" title="All Unit">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('unit.update', $unit->id) }}" method="post">
                        @csrf
                        @method('PATCH')

                        <div class="form-group required">
                            <label for="name">@lang('contents.unit_name')</label>
                            <input type="text" class="form-control" value="{{ $unit->name }}" id="name" name="name" required>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 required">
                                <label for="label">@lang('contents.labels')</label>
                                <input type="text" class="form-control" value="{{ $unit->labels }}" id="label" name="label" required>
                            </div>

                            <div class="form-group col-md-6 required">
                                <label for="relation">@lang('contents.relation')</label>
                                <input type="text" class="form-control" value="{{ $unit->relation }}" id="relation" name="relation" required>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label for="description">@lang('contents.description')</label>
                            <textarea name="description" class="form-control" id="description">{{ $unit->description }}</textarea>
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
