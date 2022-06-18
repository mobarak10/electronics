@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.gl_accounts_details')</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('admin.glAccountHead.index') }}" class="btn btn-primary" title="All bank">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp;
                                @lang('contents.back')
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <article>
                            <h3>{{ $gl_head->name }} ({{ $gl_head->code }})</h3>

                            <p>
                                @lang('contents.created_by')
                                <strong>{{ $gl_head->operator->name }}</strong> 
                                @lang('contents.at') {{ $gl_head->created_at->format('j F, Y') }}
                                @lang('contents.and_last_updated_at') {{ $gl_head->updated_at->format('j F, Y') }}.
                                @lang('contents.current_status_is') <strong>{{ ($gl_head->status) ? 'Active' : 'Inactive' }}</strong> .
                            </p>

                            <p>
                                @lang('contents.account_name')
                                <strong><a href="{{ route('admin.glAccount.show', $gl_head->glAccount->id) }}">{{ $gl_head->glAccount->name }}</a></strong> 
                                @lang('contents.type')
                                <strong>{{ ucwords( $gl_head->type) }}</strong>.
                            </p>

                            <p>{{ $gl_head->description }}</p>
                        </article>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
