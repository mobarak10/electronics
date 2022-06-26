@extends('layouts.user')

@section('title', 'Customer')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('contents.create_new_customer')</h5>

                    <a href="{{ route('customer.index') }}" class="btn btn-primary" title="Show All">
                        <i class="fa fa-list"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="col-12 py-2">
                        <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-12 pr-md-5">
                                    <div class="form-row">
                                        <div class="form-group col-md-5 required">
                                            <label for="name">@lang('contents.customer_name')</label>
                                            <input type="text" value="{{ old('name') }}" name="name" class="form-control" id="name" placeholder="Name">
                                        </div>

                                        <div class="form-group col-md-3 required">
                                            <label for="phone">@lang('contents.phone')</label>
                                            <input type="text" value="{{ old('phone') }}" name="phone" class="form-control" id="phone" placeholder="Phone">
                                        </div>
                                        <div class="form-group col-md-4 ">
                                            <label for="image">@lang('contents.image')</label>
                                            <input type="file" value="{{ old('image') }}" name="image" class="form-control" id="image" placeholder="Phone">
                                        </div>

                                        <div class="form-group col-md-12 required">
                                            <label for="balance">@lang('contents.balance')</label>
                                            <div class="input-group">
                                                <input type="number" value="{{ old('balance') }}" name="balance" class="form-control" min="0" id="balance" placeholder="Balance">
                                                <div class="input-group-append">
                                                    <select name="balance_status" class="form-control">
                                                        <option selected value="receivable">Receivable</option>
                                                        <option value="payable">Payable</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

{{--                                        <div class="form-group col-md-6 required">--}}
{{--                                            <label for="type">Customer Type</label>--}}
{{--                                            <select name="type" id="type" class="form-control">--}}
{{--                                                <option selected>Choose Type</option>--}}
{{--                                                @foreach(config('coderill.customer_type') as $type => $name)--}}
{{--                                                    <option value="{{ $type }}">{{ $name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

{{--                                        <div class="form-group col-md-6 required">--}}
{{--                                            <label for="credit_limit">Credit Limit</label>--}}
{{--                                            <input type="number" min="0" step="any" value="{{ old('credit_limit') }}" name="credit_limit" class="form-control" id="credit_limit" placeholder="Enter credit limit amount">--}}
{{--                                        </div>--}}

                                        <div class="form-group col-md-4">
                                            <label for="email">@lang('contents.email')</label>
                                            <input type="email" value="{{ old('email') }}" name="email" class="form-control" id="email" placeholder="Email">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="contact-person">@lang('contents.contact_person')</label>
                                            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control" id="contact-person" placeholder="Contact Person">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="contact-person-number">@lang('contents.contact_person_phone')</label>
                                            <input type="text" value="{{ old('contact_person_phone') }}" name="contact_person_phone" class="form-control" id="contact-person-number" placeholder="Enter Phone Number">
                                        </div>


                                        {{-- <div class="form-group col-6">
                                            <label for="logo">@lang('contents.logo_media_id')</label>
                                            <input type="number" value="{{ old('thumbnail') }}" name="thumbnail" class="form-control" id="logo" placeholder="Enter Media Code">
                                        </div> --}}

                                        <div class="form-group col-md-6">
                                            <label for="address">@lang('contents.address')</label>
                                            <textarea class="form-control" value="{{ old('address') }}" name="address" id="address" placeholder="Address"></textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="description">@lang('contents.description')</label>
                                            <textarea class="form-control" value="{{ old('description') }}" name="description" id="description" placeholder="Description"></textarea>
                                        </div>
                                    </div>
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
</div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
@endpush
