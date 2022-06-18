 @extends('layouts.admin')

@section('title', $title)

@section('content')
<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">Permission Details</h5>

                    <div class="btn-group" role="group" aria-level="Action area">
                        <a href="{{ route('admin.permission.index') }}" class="btn btn-primary" title="Back">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <h4>{{ $records->menu->name }}</h4>
                    <form action="{{ route('admin.permission.update', $records->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        @foreach (config('coderill.actions') as $slug => $action)
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="action[]"
                                    id="action-{{ $slug }}"
                                    value="{{ $slug }}"
                                        {{ (in_array($slug, $records->permissionNames->pluck('slug')->all())) ? 'checked' : '' }}
                                    >
                                <label class="form-check-label" for="action-{{ $slug }}">{{ $action }}</label>
                            </div>
                        @endforeach

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
@endsection
