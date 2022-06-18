@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.gl_accounts_details')</h5>

                        <div class="btn-group" role="group" aria-label="Action area">
                            <a href="{{ route('glAccount.index') }}" class="btn btn-primary" title="All bank">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp;
                                @lang('contents.back')
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <article>
                            <h3>{{ $gl_account->name }} ({{ $gl_account->code }})</h3>
                            
                            <p>
                                @lang('contents.created_by')
                                <strong>{{ $gl_account->operator->name }}</strong> 
                                @lang('contents.at') {{ $gl_account->created_at->format('j F, Y') }}
                                @lang('contents.and_last_updated_at') {{ $gl_account->updated_at->format('j F, Y') }}.
                                @lang('contents.current_status_is') {{ ($gl_account->status) ? 'Active' : 'Inactive' }}.
                            </p>

                            <p>{{ $gl_account->description }}</p>

                            <table class="table">
                                <thead>
                                    <th>GL Account Head</th>
                                    <th>Type</th>
                                </thead>

                                <tbody>
                                    @foreach ($gl_account->allGLAccountHead as $head)
                                        <tr>
                                            <td>
                                                <a href="{{ route('glAccountHead.show', $head->id) }}" target="_blank">
                                                    {{ $head->name }}
                                                </a>
                                            </td>
                                            <td>{{ ucwords($head->type) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </article>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
