<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class InvoicesExport implements FromView
{
    public function view(): View
    {
        return view('user.exports.invoices', [
            'sales' => Sale::all()
        ]);
    }
}
