@extends('layouts.user')

@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="d-none mt-2 text-center d-print-block">
                    <h5 class="mb-0 center" style="font-size: 25px"> <strong>{{ config('print.print_details.name') }}</strong> </h5>
                    <p class="mb-0 font-12">{{ config('print.print_details.address') }}</p>
                    <span class="mb-0 font-12">{{ config('print.print_details.mobile') }}</span>
                    <p class="mb-0" style="font-size: 15px">{{ Carbon\Carbon::now()->format('j F, Y h:i:s a') }}</p>
                </div>

                <div class="card current-stock">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="d-none d-print-block">Profit Loss Report</h4>
                        <h5 class="m-0 print-none">Profit Loss Report</h5>
                        <div class="action-area print-none" role="group" aria-label="Action area">
                            <a href="{{ route('profitLoss.index') }}" class="btn btn-primary" title="Refresh">
                                <i class="fa fa-refresh" aria-hidden="true"></i>
                            </a>
                            <a href="#" onclick="window.print();" title="Print" class="btn btn-warning"><i aria-hidden="true" class="fa fa-print"></i></a>
                        </div>
                    </div>

                    <!-- search form start -->
                    <div class="card-body print-none">
                        <form action="{{ route('profitLoss.index') }}" method="GET" class="row">
                            <input type="hidden" name="search" value="1">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="from_date" class="required">From Date</label>
                                        <input type="date" id="from_date" name="from_date" class="form-control" required value="{{ request()->from_date ?? date('Y-m-d') }}">
                                    </div>

                                    <div class="col-md-5">
                                        <label for="to_date" class="required">To Date</label>
                                        <input type="date" id="to_date" name="to_date" class="form-control" required value="{{ request()->to_date ?? date('Y-m-d') }}">
                                    </div>

                                    <div class="col-md-2" style="padding-top: 30px">
                                        <button type="submit" class="btn btn-primary" title="search">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(request()->search)
                        <div class="row m-2">
                            <div class="col-lg-4">
                                <a href="#" class="item-box color-1">
                                    <div class="content-box pb-3">
                                        <img src="{{ asset('public/images/bg-img/subtotal.png') }}" style="width: 100px" alt="">
                                        <div class="content">
                                            <p class="title">Total Sale Price (BDT)</p>
                                            <p style="padding-right: 5px;" class="number">{{ number_format($total_sale, 2) }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-4">
                                <a href="#" class="item-box color-2">
                                    <div class="content-box pb-3">
                                        <img src="{{ asset('public/images/bg-img/discount.png') }}" style="width: 100px" alt="">
                                        <div class="content">
                                            <p class="title">Total Discount (BDt)</p>
                                            <p class="number">{{ number_format($total_discount, 2) }}</p>
                                            {{--                                    <span>@lang('contents.sum_all_bank_balance')</span>--}}
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-4 ">
                                <a href="#" class="item-box color-3">
                                    <div class="content-box pb-3">
                                        <img src="{{ asset('public/images/bg-img/grand_total.png') }}" style="width: 100px" alt="">
                                        <div class="content">
                                            <p class="title">Grand Total (BDT)</p>
                                            <p class="number">
                                                {{ number_format($total_grand_total, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-4">
                                <a href="#" class="item-box color-5">
                                    <div class="content-box pb-3">
                                        <img src="{{ asset('public/images/bg-img/return.png') }}" style="width: 100px" alt="">
                                        <div class="content">
                                            <p class="title">Total Return (BDT)</p>
                                            <p class="number">
                                                {{ number_format($return_product_price_total, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-4">
                                <a href="#" class="item-box color-4">
                                    <div class="content-box pb-3">
                                        <img src="{{ asset('public/images/bg-img/sale.png') }}" style="width: 100px" alt="">
                                        <div class="content">
                                            <p class="title">Total Sale Without Return (BDT)</p>
                                            <p class="number">
                                                @php
                                                    $total_sale_without_return = $total_grand_total - $return_product_price_total;
                                                @endphp
                                                {{ number_format($total_grand_total - $return_product_price_total, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-4">
                                <a href="#" class="item-box color-6">
                                    <div class="content-box pb-3">
                                        <img src="{{ asset('public/images/bg-img/purchase.png') }}" style="width: 100px" alt="">
                                        <div class="content">
                                            <p class="title">Total Purchase Price (BDT)</p>
                                            <p class="number">
                                                {{ number_format($total_purchase_price - $return_product_price_total, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-6 mx-auto">
                                <a href="#" class="item-box color-7">
                                    <div class="content-box pb-3 justify-content-center">
                                        @php
                                            $total_loss_profit = ($total_sale_without_return - ($total_purchase_price - $return_product_price_total));
                                        @endphp
                                        @if($total_loss_profit > 0)
                                            <img src="{{ asset('public/images/bg-img/total-profit.png') }}" style="width: 100px" alt="">
                                        @else
                                            <img src="{{ asset('public/images/bg-img/total_loss.png') }}" style="width: 100px" alt="">
                                        @endif
                                        <div class="content">
                                            <p class="title">Total {{ ($total_loss_profit > 0) ? 'Profit' : 'Loss' }} (BDT)</p>
                                            <p class="number">
                                                {{ number_format(abs($total_loss_profit), 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

