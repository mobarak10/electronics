@extends('layouts.user')
@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="print-show text-center">SMS Reports</h3>
                    <div class="print-none" style="margin-top: 10px;">
                        <p class="mb-2"> 1. Please select date to see the specific date's SMS report.</p>
                        <p class="mb-2"> 2. Please search SMS report by mobile number.</p>
                        <strong class="mb-3">
                            {{-- Total SMS : 2000 &nbsp;&nbsp;&nbsp; --}}
                            Remaining SMS : {{$remaining_sms}}&nbsp;&nbsp;&nbsp;
                            Sent SMS : {{count($sms_reports)}}
                        </strong>
                    </div>

                            <!-- form section start -->
                <form class="print-none mt-2" action="{{ route('sms.smsReport') }}" method="get">
                    <input type="hidden" name="search" value="1">
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date" class="control-label">Date (From)</label>
                                <div class='input-group date' id='date'>
                                    <input type="date" value="{{ (request()->search) ? request()->form_date : "" }}" id="form_date" name="form_date" class="form-control" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date" class="control-label">Date (To)</label>
                                <div class='input-group date' id='date'>
                                    <input type="date" value="{{ (request()->search) ? request()->to_date : "" }}" id="to_date" name="to_date" class="form-control" placeholder="YYYY-MM-DD">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mobile" class="control-label">Mobile no</label>
                                <div class="input-group">
                                    <input class="form-control" name="mobile" type="text" placeholder="Search by mobile no. "
                                            value="{{ request('mobile') ?? '' }}"
                                            aria-label="Search">
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-2">
                            <div class="form-group ">
                                <label for="">&nbsp;</label>
                                <button type="submit"
                                        data-toggle="tooltip" data-placement="top" title="Search"
                                        class="btn btn-success me-2">Search</button>

                                <a href="{{ route('sms.smsReport') }}"
                                   data-toggle="tooltip" data-placement="top" title="Reset"
                                   class="btn btn btn-danger text-decoration-none" type="reset">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- form section end -->

                <table class="table table-sm table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Send To</th>
                        <th>Message</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sms_reports as $report)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{$report->created_at->format("Y-m-d")}}</td>
                            <td>{{$report->sent_to}}</td>
                            <td class="w-75">{{$report->message}}</td>
                            <td class="text-success">{{$report->status}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No data found</td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
                    <!-- paginate -->
                    <div class="float-right mx-2">
                        {{ $sms_reports->links() }}
                    </div>
            </div>

        </div>

    </div>
@endsection





