<?php

namespace App\Http\Controllers\User\Sms;

use App\Helpers\SMS;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Party;
use App\Models\SmsReport;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    private $paginate = 25;
    private $meta = [
        'title'   => 'SMS',
        'menu'    => 'sms',
        'submenu' => ''
    ];

    public $sender_id, $api_key;

    public function __construct()
    {
        $this->sender_id = env('SMS_SENDER_ID');
        $this->api_key = env('SMS_API_KEY');
    }



    public function groupSms()
    {
        // get resource
         if (\request()->type == 'supplier') {
             $parties = Party::paginate(request('paginate_number', 30));
         }else{
             $parties = Customer::paginate(request('paginate_number', 30));
         }
         $remaining_sms = SMS::smsCurrentBalance();
         $sms_reports = SmsReport::all();

        return view('user.sms.groupSms',compact('parties', 'remaining_sms', 'sms_reports'))->with($this->meta);
    }

     public function groupSmsSend(Request $request)
    {
        // return $request->all();
        $request->validate([
            'message' => 'required|string',
            'mobiles' => 'required|array',
            'mobiles.*' => 'string|size:11|starts_with:01'
        ]);

        $mobiles = join(',', $request->mobiles);
        $message = $request->message . " " . config('sms.regards');
        $report = SMS::groupSmsSend($this->sender_id, $this->api_key, $mobiles, $message); //send sms
        $data = explode('|', $report); //for decode data

        if (($data[0] ?? '00') == '1101') {
            foreach ($request->mobiles as $mobile) {
                $sms_report = new SmsReport();
                $sms_report->sent_to = $mobile;
                $sms_report->message = $request->message;
                $sms_report->total_character = $request->total_characters;
                $sms_report->total_sms = $request->total_messages;
                $sms_report->status = "success";

                $sms_report->save();
            }
            $success_message = "SMS has been send successfully.";
            // view
            return redirect()->route('sms.groupSms')->withSuccess($success_message);
        } else {
            return back()->withErrors('Your SMS has not send');
        }

    }


    public function customSms()
    {
        $remaining_sms = SMS::smsCurrentBalance();
        $sms_reports = SmsReport::all();
        return view('user.sms.customSms',compact('remaining_sms', 'sms_reports'))->with($this->meta);
    }

    public function customSmsSend(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'mobiles' => 'required|string',
            'mobiles.*' => 'string|size:11|starts_with:01'
        ]);

        // return $request->all();

        $message = $request->message . " " . config('sms.regards');
        $report = SMS::customSmsSend($this->sender_id, $this->api_key, $request->mobiles, $message); //send sms
        $mobiles = explode(',', $request->mobiles);
        $data = explode('|', $report); //for decode data

        if (($data[0] ?? '00') == '1101') {
            foreach ($mobiles as $mobile) {
                $sms_report = new SmsReport();
                $sms_report->sent_to = $mobile;
                $sms_report->message = $request->message;
                $sms_report->total_character = $request->total_characters;
                $sms_report->total_sms = $request->total_messages;
                $sms_report->status = "success";

                $sms_report->save();
            }
            $success_message = "SMS has been send successfully.";
            // view
            return redirect()->route('sms.customSms')->withSuccess($success_message);
        } else {
            return back()->withErrors('Your SMS has not send');
        }
    }

    public function smsReport()
    {
         $sms_reports = SmsReport::query()
         ->latest()
         ->paginate(30);
        $remaining_sms = SMS::smsCurrentBalance();
        if (request()->search) {
            $start_date = date('Y-m-d', strtotime(request()->get('form_date')));
            $end_date = date('Y-m-d', strtotime(request()->get('to_date')));
            $mobile = \request('mobile');
            $sms_reports = SmsReport::query();

            if ($mobile) {
                $sms_reports->Where('sent_to', $mobile);
            }
            if (request()->get('form_date') && request()->get('to_date') ) {
                $sms_reports->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:00']);

            }
            $sms_reports = $sms_reports->paginate(30);

        }

        return view('user.sms.smsReport', compact('sms_reports', 'remaining_sms'))->with($this->meta);
    }

}
