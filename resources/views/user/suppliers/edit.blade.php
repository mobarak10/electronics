@extends('layouts.user')

@section('title', 'Supplier')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">@lang('contents.update_supplier')</h5>
                        <div>
                            <a href="{{ route('supplier.show', $supplier->id) }}" class="btn btn-primary" title="All cash">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i> &nbsp; Back 
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="col-12 py-2">
                            <form action="{{ route('supplier.update', $supplier->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="form-row">
                                    <div class="form-group col-md-5 required">
                                        <label for="name">@lang('contents.supplier_name')</label>
                                        <input type="text" name="name" value="{{ $supplier->name }}" class="form-control" id="name" placeholder="Name">
                                    </div>
                                    <div class="form-group col-md-3 required">
                                        <label for="phone">@lang('contents.phone')</label>
                                        <input type="text" name="phone" value="{{ $supplier->phone }}" class="form-control" id="phone" placeholder="Phone">
                                    </div>
                                    <div class="form-group col-md-3 ">
                                        <label for="phone">@lang('contents.image')</label>
                                        <input type="file" name="image"  class="form-control" id="phone" >
                                    </div>
                                    <div class="form-group col-md-1 ">
                                        <img src="{{ asset(($media) ? $media->real_path : 'public/images/avatar.jpeg') }}" alt="image" style="width: 120px; margin-top:-5px; margin-left:5px ">
                                        
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="email">@lang('contents.email')</label>
                                        <input type="email" name="email" value="{{ $supplier->email }}" class="form-control" id="email" placeholder="Email">
                                    </div>
                                    <div class="form-group col-md-6 required">
                                        <label for="balance">@lang('contents.balance')</label>
                                        <div class="input-group mb-3">
                                            <input type="number" name="balance" step="any" class="form-control" id="balance" value="{{ abs($supplier->balance) }}" placeholder="Balance">
                                            <div class="input-group-append">
                                                <select name="balance_status" class="form-control">
                                                    <option {{ ($supplier->balance < 0) ? 'selected' : '' }} value="payable">Payable</option>
                                                    <option {{ ($supplier->balance >= 0) ? 'selected' : '' }} value="receivable">Receivable</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="contact-person">@lang('contents.contact_person')</label>
                                        <input type="text" name="contact_person" value="{{ $supplier->getMetaValue('contact_person') }}" class="form-control" id="contact-person" placeholder="Contact Person">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="contact-person-number">@lang('contents.contact_person_phone')</label>
                                        <input type="text" name="contact_person_phone" value="{{ $supplier->getMetaValue('contact_person_phone') }}" class="form-control" id="contact-person-number" placeholder="Enter Phone Number">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="address">@lang('contents.address')</label>
                                        <textarea class="form-control" name="address" id="address" placeholder="Address">{{ $supplier->address }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="description">@lang('contents.description')</label>
                                        <textarea class="form-control" name="description" id="description" placeholder="Description">{{ $supplier->description }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <button type="reset" class="btn btn-danger">Reset</button>
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
