@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-3">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Edit Advanced Salary for {{ $advanced_salary_details->advancedSalary->user->name }}</h5>
                        <div class="btn-group" role="group" area-level="Action area">
                            <a href="{{ route('admin.advancedSalary.show', $advanced_salary_details->advancedSalary->id) }}" class="btn btn-primary" title="Advanced salary list">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.advancedSalary.update', $advanced_salary_details->id) }}" method="POST" accept-charset="utf-8">
                            @csrf
                            @method('PATCH')
                            <div class="form-row">
                                <div class="form-group col-md-6 required">
                                    <label for="adv_amount">Amount</label>
                                    <input type="text" id="adv_amount" name="adv_amount" class="form-control" value="{{ $advanced_salary_details->adv_amount }}">
                                </div>

                                <div class="form-group col-md-6 required">
                                    <label for="installment_amount">Installment</label>
                                    <input type="text" class="form-control" value="{{ $advanced_salary_details->installment_amount }}" name="installment_amount" id="installment_amount">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12 required">
                                    <label for="note">Note</label>
                                    <textarea type="text" name="note" class="form-control" id="note">{{ $advanced_salary_details->note }}</textarea>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger">@lang('contents.reset')</button>
                                <button type="submit" class="btn btn-primary left">@lang('contents.save_changes')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
