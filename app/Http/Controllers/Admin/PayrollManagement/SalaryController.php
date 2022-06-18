<?php

namespace App\Http\Controllers\Admin\PayrollManagement;

use App\Helpers\SMS;
use App\Models\SmsReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\Salary;
use App\Models\AdvancedSalary;
use App\Models\AdvancedSalaryDetails;
use App\Models\AdvancedSalaryPaidDetails;
use App\Models\SalaryDetails;
use App\Models\InstallmentPay;
use Illuminate\Support\Carbon;

class SalaryController extends Controller
{
    private $meta = [
        'title' => 'Salary',
        'menu' => 'payroll',
        'submenu' => ''
    ];

    public $sender_id, $api_key;

    public function __construct()
    {
        $this->middleware('auth');
        $this->sender_id = env('SMS_SENDER_ID');
        $this->api_key = env('SMS_API_KEY');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $current_date = Carbon::today()->subMonth(2);
        // get user This salary is given in previous month
        $paidUser = User::whereHas('salaries', function ($query){
            $query->where('salary_of_month_year', '>', Carbon::today()->subMonth()->subDay(31));
        })->get();

        // in last month salary given user create a column last_month_paid_status = true
        $paidUser->map(function($user) {
            return $user['last_month_paid_status'] = true;
        });

        // get user This salary is not given in previous month
        $unpaidUser = User::whereDoesntHave('salaries')->orWhereHas('salaries', function ($query){
            $query->where('salary_of_month_year','<' ,Carbon::today()->subMonth()->subDay(1));
        })->get();

        // in last month salary is not given user create a column last_month_paid_status = false
        $unpaidUser->map(function($user) {
            return $user['last_month_paid_status'] = false;
        });

        // get all user this salary given & is not given
        $users = $unpaidUser->merge($paidUser)->sortBy('created_at');

        // if search for salary in selected month
        if (request()->search) {
            // add day 01 in requested month
            $month = request()->month.'-'.'01';
            // get user where salary is given in requested month
            $paidUser = User::whereHas('salaries', function ($query) use($month){
                $query->where('salary_of_month_year', $month);
            })->get();

            // in last month salary given user create a column last_month_paid_status = true
            $paidUser->map(function($user) {
                return $user['last_month_paid_status'] = true;
            });

            // get user where salary is not given in requested month
            $unpaidUser = User::whereDoesntHave('salaries')->orWhereHas('salaries', function ($query) use($month){
                $query->where('salary_of_month_year', '!=', $month);
            })->get();

            // get user where salary is not given in requested month
            $unpaidUser->map(function($user) {
                return $user['last_month_paid_status'] = false;
            });

            // merge paid user and unpaid user
            $users = $unpaidUser->merge($paidUser)->sortBy('created_at');

            $user_id = $users->pluck('id')->all();

        }

        return view('admin.payroll-management.salary.index', compact('users',))->with($this->meta);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $records = collect([]);
        $records['users'] = User::all();
        $records['allowance'] = config('coderill.allowance');
        return view('admin.payroll-management.salary.create', compact('records'))->with($this->meta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add day in requested year & amount
        $modify_year_month = $request->salary_of_month_year.'-'.'01';
        // get month for request user
        $exist = Salary::where('salary_of_month_year', $modify_year_month)->where('user_id', $request->user_id)->first();
        if ($exist) {
            return response()->json($exist, 422);
        }
        // validate salary info
        $salary_info = $request->validate([
            'user_id' => 'required|integer',
            'salary_of_month_year' => 'required',
            'given_date' => 'required|date',
        ]);
        // add day in requested year & amount
        $salary_info['salary_of_month_year'] = $request->salary_of_month_year.'-'.'01';
        // create salary
        $salary = Salary::create($salary_info);

        // validation salary details
        $salaryDetails = $request->validate([
            'basic_salary' => 'required|numeric',
            'home_allowance' => 'required|numeric',
            'medical_allowance' => 'required|numeric',
            'transport_allowance' => 'required|numeric',
            'installments' => 'required|numeric',
            'deductions' => 'required|numeric',
        ]);

        // find requested user
        $user = User::find($request->user_id);
        // get installment amount
        $installment_amount = $request->installments;

        if ($request->sms) {
            $message = "You have paid salary of month ". $salary->salary_of_month_year->format('F ');
            $message = $message . " " . config('sms.regards');
            $report = SMS::customSmsSend($this->sender_id, $this->api_key, $user->phone, $message); //send sms
            $data = explode('|', $report); //for decode data

            if (($data[0] ?? '00') == '1101') {
                $sms_report = new SmsReport();
                $sms_report->sent_to = $user->phone;
                $sms_report->message = $message;
                $sms_report->total_character = strlen($message);
                $sms_report->total_sms = 1;
                $sms_report->status = "success";
                $sms_report->save();
            }
        }

        // if it has advanced salaries
        if ($installment_amount > 0) {
            // get advanced salaries where is paid is 0 or false
            $advancedSalaries = $user->advancedSalaries->advancedSalaryDetails->filter(function($item) {
                return !$item->is_paid;
            });

            foreach ($advancedSalaries as  $details) {
                // if installment amount is smaller than first advances salaries due
                if ($installment_amount < $details->total_due) {
                    $details->advancedSalaryPaidDetails()->create([
                        'installment_pay' => $installment_amount,
                    ]);
                    break;
                }else{
                    // if installment amount is bigger than first advances salaries due
                    $details->advancedSalaryPaidDetails()->create([
                        'installment_pay' => $details->total_due,
                    ]);
                    // if installment amount & advanced due amount is equal then is_paid is 1 or true
                    $details->is_paid = true;
                    $details->save(); // save installment paid details
                    // then installment amount is subtract paid amount
                    $installment_amount = $installment_amount - $details->total_due;
                }
            }
        }

        // salary details save
        foreach ($salaryDetails as $key => $value){
            if ($value > 0){
                if ($key == 'installments') {
                    $salary->salaryDetails()->create([
                        'purpose' => $key,
                        'dtls_amount' => $value,
                        'type' => 'decrement',
                    ]);
                }
                elseif ($key == 'deductions'){
                    $salary->salaryDetails()->create([
                        'purpose' => $key,
                        'dtls_amount' => $value,
                        'type' => 'decrement',
                    ]);
                }
                else{
                    $salary->salaryDetails()->create([
                        'purpose' => $key,
                        'dtls_amount' => $value,
                        'type' => 'increment',
                    ]);
                }
            }
        }
        // return salary id
        return response()->json($salary->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function salaryDetails(Request $request){
        $basicSalary = User::where('id', $request->id)
                    ->with(['advancedSalaries.advancedSalaryDetails' => function ($query) {
                        $query->where('is_paid', 0);
                    },'advancedSalaries.advancedSalaryDetails.advancedSalaryPaidDetails'])->with('metas')->first();

        return response()->json($basicSalary, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function salaryPay($id)
    {
//        $records = collect([]);
        $user = User::find($id);
//        $records['allowance'] = config('coderill.allowance');
        return view('admin.payroll-management.salary.selectedUserSalary', compact('user'))->with($this->meta);
    }

    /**
     * @return array
     */
    public function salaryView($id) {
        $salary = Salary::with('salaryDetails')->find($id);
        $salary["increment"] = $salary->salaryDetails->where("type","increment")->sum("dtls_amount");
        $salary["decrement"] = $salary->salaryDetails->where("type","decrement")->sum("dtls_amount");

        $salary["basic_salary"] = $salary->salaryDetails->where('purpose', 'basic_salary')->first();
        $salary["home_allowance"] = $salary->salaryDetails->where('purpose', 'home_allowance')->first();
        $salary["medical_allowance"] = $salary->salaryDetails->where('purpose', 'medical_allowance')->first();
        $salary["transport_allowance"] = $salary->salaryDetails->where('purpose', 'transport_allowance')->first();
        $salary["installments"] = $salary->salaryDetails->where('purpose', 'installments')->first();
        $salary["deductions"] = $salary->salaryDetails->where('purpose', 'deductions')->first();
        return view('admin.payroll-management.salary.salaryView', compact('salary'))->with($this->meta);
    }

}
