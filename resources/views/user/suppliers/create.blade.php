@extends('layouts.user')

@section('title', 'Supplier')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 py-3">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('contents.create_new_supplier')</h5>
                    <div class="btn-group" role="group" aria-label="Action area">
                        <a href="{{ route('supplier.index') }}" class="btn btn-primary" title="All supplier">
                            <i class="fa fa-list" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="col-12 py-2">
                        <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4 required">
                                    <label for="name">@lang('contents.supplier_name')</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                                </div>
                                <div class="form-group col-md-4 required">
                                    <label for="phone">@lang('contents.phone')</label>
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
                                </div>
                                <div class="form-group col-md-4 ">
                                    <label for="image">@lang('contents.image')</label>
                                    <input type="file" value="{{ old('image') }}" name="image" class="form-control" id="image" placeholder="Phone">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email">@lang('contents.email')</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="balance">@lang('contents.balance')</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="balance" step="any" class="form-control" id="balance" value="0.00" placeholder="Balance">
                                        <div class="input-group-append">
                                            <select name="balance_status" class="form-control">
                                                <option selected value="payable">Payable</option>
                                                <option value="receivable">Receivable</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="contact-person">@lang('contents.contact_person')</label>
                                    <input type="text" name="contact_person" class="form-control" id="contact-person" placeholder="Contact Person">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="contact-person-number">@lang('contents.contact_person_phone')</label>
                                    <input type="text" name="contact_person_phone" class="form-control" id="contact-person-number" placeholder="Enter Phone Number">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="division">Division</label>
                                    <input class="form-control" name="division" id="division" placeholder="Enter division" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="district">District</label>
                                    <input class="form-control" name="district" id="district" placeholder="Enter district" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="thana">Thana</label>
                                    <input class="form-control" name="thana" id="thana" placeholder="Enter thana" />
                                </div>
                            </div>

                            

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="address">@lang('contents.address')</label>
                                    <textarea class="form-control" name="address" id="address" placeholder="Address"></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description">@lang('contents.description')</label>
                                    <textarea class="form-control" name="description" id="description" placeholder="Description"></textarea>
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
