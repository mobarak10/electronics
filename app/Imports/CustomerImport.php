<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    public function model(array $row): Customer
    {
        return new Customer([
            'code' => 'CUST' . str_pad(Customer::max('id') + 1, 8, '0', STR_PAD_LEFT),
            'name' => $row['name'],
            'description' => $row['description'],
            'phone' => $row['phone'],
            'balance' => $row['balance'],
            'email' => $row['email'],
            'type' => $row['type'],
            'credit_limit' => $row['credit_limit'],
            'address' => $row['address'],
            'thumbnail' => $row['thumbnail'],
            'active' => $row['active'],
            'business_id' => $row['business_id'],
        ]);
    }
}
