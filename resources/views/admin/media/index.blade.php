@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.all_media')</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($media as $medium)
                                <div class="col-md-4 py-2">
                                    <div class="card mb-3">
                                        <img src="{{ asset($medium->real_path) }}" class="card-img-top img-thumbnail" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $medium->title }}</h5>
                                            <p class="card-text">{{ $medium->description }}</p>
                                            <p class="card-text"><small class="text-muted">Last updated {{ $medium->updated_at->diffForHumans() }}</small></p>
                                            <div class="input-group mb-3 copy-area">
                                                <input type="text" class="form-control form-control-sm copy-code" value="{{ $medium->code }}" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary btn-sm copy-button" type="button">@lang('contents.copy')</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button onclick="if(confirm('Are you sure want to delete?')) { document.getElementById('media-delete-{{ $medium->id }}').submit() }" class="btn btn-danger btn-block btn-sm">Delete</button>

                                            <form id="media-delete-{{ $medium->id }}" action="{{ route('admin.media.destroy', $medium->id) }}" method="POST" style="display: none">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 py-2">
                                    @lang('contents.no_media_available')
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function(){
            $('.copy-button').on('click', function (event) {
                event.preventDefault();
                $(this).closest('.copy-area').find('.copy-code').select();
                document.execCommand("copy");
            })
        });
    </script>
@endpush
