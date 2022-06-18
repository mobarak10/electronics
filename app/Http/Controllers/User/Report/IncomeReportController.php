<?php

namespace App\Http\Controllers\User\Report;

use App\Http\Controllers\Controller;
use App\Models\Expenditure;
use App\Models\IncomeRecord;
use App\Models\IncomeSector;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class IncomeReportController extends Controller
{
    private $meta = [
        'title' => 'Income Report',
        'menu' => 'reports',
        'submenu' => ''
    ];

    public $data = [];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->meta['submenu'] = 'income-report';
        $incomes = '';
        $income_sectors = [];

        if (request()->search) {
            $income_sectors = IncomeSector::query()
                ->with([
                    'incomeRecords' => function (HasMany $query) {
                        $query->select('income_sector_id', 'date', 'amount')
                            ->whereYear('date', request()->year);
                    }
                ])
                ->get()
                ->map(function (IncomeSector $sector) {
                    $records = $sector->incomeRecords->map(function (IncomeRecord $record) {
                        $record['month'] = $record->date->format('M');
                        return $record;
                    })->groupBy('month')->map(function ($item) {
                        return $item->sum('amount');
                    });

                    $sector['sum_of_each_month'] = $records;

                    return $sector;
                });
        }

        return view('user.reports.income.index', compact('income_sectors', 'incomes'))->with($this->meta);
    }
}
