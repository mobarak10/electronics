@extends('layouts.user')

@section('title', 'Customer')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.update_customer')</h5>
                    </div>

                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="form-row">
                                    <div class="form-group col-md-12 pr-md-5">
                                        <input type="hidden" name="page" value="{{ $page }}">
                                        <div class="form-row">
                                            <div class="form-group col-md-5 required">
                                                <label for="name">@lang('contents.customer_name')</label>
                                                <input type="text" name="name" value="{{ $customer->name }}" class="form-control" id="name" placeholder="Name">
                                            </div>
                                            <div class="form-group col-md-3 required">
                                                <label for="phone">@lang('contents.phone')</label>
                                                <input type="text" name="phone" value="{{ $customer->phone }}" class="form-control" id="phone" placeholder="Phone">
                                            </div>
                                            <div class="form-group col-md-3 ">
                                                <label for="phone">@lang('contents.image')</label>
                                                <input type="file" name="image"  class="form-control" id="phone" placeholder="Phone">
                                            </div>
                                            <div class="form-group col-md-1 ">
                                                <img src="{{ asset(($media) ? $media->real_path : 'public/images/avatar.jpeg') }}" alt="image" style="width: 120px; margin-top:-5px; margin-left:15px ">

                                            </div>

                                            <div class="form-group col-md-12 required">
                                                <label for="balance">@lang('contents.balance')</label>
                                                <div class="input-group">
                                                    <input type="text" value="{{ abs($customer->balance) }}" name="balance" class="form-control" min="0" id="balance" placeholder="Balance">
                                                    <div class="input-group-append">
                                                        <select name="balance_status" class="form-control">
                                                            <option {{ $customer->balance >= 0 ? 'selected' : '' }} value="receivable">Receivable</option>
                                                            <option {{ $customer->balance < 0 ? 'selected' : '' }} value="payable">Payable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

{{--                                            <div class="form-group col-md-6 required">--}}
{{--                                                <label for="type">@lang('contents.balance')</label>--}}
{{--                                                <select name="type" id="type" class="form-control">--}}
{{--                                                    @foreach(config('coderill.customer_type') as $type => $name)--}}
{{--                                                        <option {{ $customer->type === $type ? 'selected' : '' }} value="{{ $type }}">{{ $name }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </div>--}}

{{--                                            <div class="form-group col-md-6 required">--}}
{{--                                                <label for="credit_limit">Credit Limit</label>--}}
{{--                                                <input type="number" min="0" step="any" value="{{ abs($customer->credit_limit) }}" name="credit_limit" class="form-control" id="credit_limit" placeholder="Enter credit limit amount">--}}
{{--                                            </div>--}}

                                            <div class="form-group col-md-4">
                                                <label for="email">@lang('contents.email')</label>
                                                <input type="email" name="email" value="{{ $customer->email }}" class="form-control" id="email" placeholder="Email">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="contact-person">@lang('contents.contact_person')</label>
                                                <input type="text" name="contact_person" value="{{ $customer->getMetaValue('contact_person') }}" class="form-control" id="contact-person" placeholder="Contact Person">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="contact-person-number">@lang('contents.contact_person_phone')</label>
                                                <input type="text" name="contact_person_phone" value="{{ $customer->getMetaValue('contact_person_phone') }}" class="form-control" id="contact-person-number" placeholder="Enter Phone Number">
                                            </div>



                                            <div class="form-group col-md-6">
                                                <label for="address">@lang('contents.address')</label>
                                                <textarea class="form-control" name="address" id="address" placeholder="Address">{{ $customer->address }}</textarea>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="description">@lang('contents.description')</label>
                                                <textarea class="form-control" name="description" id="description" placeholder="Description">{{ $customer->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="text-right">
                                    <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                    <button type="submit" class="btn btn-primary">@lang('contents.update')</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
