@extends('layouts.user')
@section('title', $title)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="header">
                  <h4 class="main-title">Custom SMS Send</h4>
                    <p><small>Can send <strong>Custom SMS</strong> from here.</small></p>
                </div>
                <div class="mt-2">
                   <p class="mb-2 text-muted"> 1. Type Mobile Number and Use Comma to separate more than one Number.</p>
                   <p class="mb-2 text-muted"> 2. Type Message  and then click Send button to Send SMS.</p>
                    <div class="mt-4">
                        <strong>
                            {{-- <span>Total SMS: 500 </span> &nbsp; &nbsp; --}}
                            Remaining SMS : {{$remaining_sms}}&nbsp;&nbsp;&nbsp;
                            Sent SMS : {{count($sms_reports)}}
                        </strong>
                    </div>
                </div>

            </div>

        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="mb-3 d-block">
                    <form action="{{ route('sms.customSms') }}" method="POST">
                        @csrf
                            <div class="p-0 mt-4 card-body">
                                <div class="mb-3 row">
                                    <!-- Write number Start-->
                                    <div class="col-12">
                                        <label for="mobile" class="mt-1 form-label required">Mobile Number</label>
                                            <textarea name="mobiles" class="form-control" id="mobile" rows="3"
                                                placeholder="Use comma to separate number" required></textarea>

                                            <!-- error -->
                                            @error('mobiles')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                    </div>
                                                <!-- Write number End-->

                                                <!-- Write Message Start-->
                                    <div class="col-12 mt-2">
                                        <label for="message" class="mt-1 form-label required">Message</label>
                                            <textarea name="message" class="form-control" id="message" rows="4"
                                                placeholder="Type message here.." required>{{ old('message')}}</textarea>

                                            <!-- error -->
                                            @error('message')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                    </div>
                                                <!-- Write Message End-->

                                                <!-- SMS & Character count start -->
                                    <div class="col-md-12 mt-3">
                                        <p class="text-muted">
                                            <span>
                                                <strong>Total Characters</strong>
                                                    <input type="text" id="total_characters" name="total_characters" value="21" readonly>
                                            </span>
                                            &nbsp;
                                            <span>
                                                <strong>Total Messages</strong>
                                                    <input  type="text" id="total_messages" value="1" name="total_messages" readonly>
                                            </span>
                                        </p>
                                    </div>
                                    <!-- SMS & Character count end -->
                            </div>

                            <div class="mb-3 row">
                                <div class="col-2">
                                    <label class="mt-1 form-label">&nbsp;</label>
                                </div>

                                <div class="col-12">
                                    <button type="reset" class="btn btn-danger me-2">
                                        <i class="bi bi-dash-square"></i>
                                        <span class="p-1">Reset</span>
                                    </button>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-envelope"></i>
                                        <span class="p-1">Send</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection

    @push('script')
        <!-- SMS & Character count js start -->
        <script src="{{ asset('/public/js/sms/sms.js') }}"></script>
        <!-- SMS & Character count js end -->
    @endpush



