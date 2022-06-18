<?php

return [
    // code goes here
    'common' => [
        'input_field' => [
            'active' => [
                1 => 'Active',
                0 => 'Inactive'
            ]
        ]
    ],

    'customer_type' => [
        'dealer' => 'Dealer',
        'sub_dealer' => 'Sub Dealer',
        'cash_customer' => 'Cash Customer',
        'semi_cash_customer' => 'Semi Cash Customer',
        'one_third_customer' => 'One Third Customer',
        'semi_credit' => 'Semi Credit',
        'regular_credit' => 'Regular Credit',
    ],

    'bank' => [
        'account' => [
            'kind' => [
                'Checking account',
                'Savings account',
                'Money market account',
                'Certificate of deposit (CD)',
                'Individual retirement arrangement (IRA)',
                'Brokerage account',
            ],
        ],
    ],
    'expense' => [
        'meta_key' => [
            'expense_sectors' => 'expense_sectors'
        ],
    ],
    'admin' => [
        'meta' => [
            'dob'                   => 'Date of birth',
            'father_name'           => 'Father\'s Name',
            'mother_name'           => 'Mother\'s Name',
            'contact_person_number' => 'Contact Person Number',
            'nid_number'            => 'NID Number',
            'present_address'       => 'Present Address',
            'permanent_address'     => 'Permanent Address',
            'basic_salary'          => 'Basic Salary',
            'home_allowance'        => 'Home Allowance',
            'transport_allowance'   => 'Transport Allowance',
            'medical_allowance'     => 'Medical Allowance',
            'address'               => 'Address',
            'gender'                => 'Gender',
        ]
    ],

    'party' => [
        'supplier' => [
            'meta' => [
                'contact_person' => 'Contact person',
                'contact_person_phone' => 'Contact person phone',
            ],

        ],
        'customer' => [
            'meta' => [
                'contact_person' => 'Contact person',
                'contact_person_phone' => 'Contact person phone',
                'first_guarantor_name' => 'First Guarantor Name',
                'first_guarantor_mobile' => 'First Guarantor Mobile',
                'first_guarantor_address' => 'First Guarantor Address',
                'second_guarantor_name' => 'Second Guarantor Name',
                'second_guarantor_mobile' => 'Second Guarantor Mobile',
                'second_guarantor_address' => 'Second Guarantor Address',
            ],

        ]
    ],
    'actions' => [
        'create'    => 'Create',
        'view'      => 'View',
        'show'      => 'Show',
        'edit'      => 'Edit',
        'destroy'   => 'Destroy'
    ],

    'months' => [
        1      => 'January',
        2      => 'February',
        3      => 'March',
        4      => 'April',
        5      => 'May',
        6      => 'June',
        7      => 'July',
        8      => 'August',
        9      => 'September',
        10     => 'October',
        11     => 'November',
        12     => 'December'
    ],

    'single_months' => [
        1      => 'Jan',
        2      => 'Feb',
        3      => 'Mar',
        4      => 'Apr',
        5      => 'May',
        6      => 'Jun',
        7      => 'Jul',
        8      => 'Aug',
        9      => 'Sep',
        10     => 'Oct',
        11     => 'Nov',
        12     => 'Dec'
    ],

    'statements' => [
        'asset'     => 'Assets',
        'revenue'   => 'Revenues',
        'expense'   => 'Expenses',
        'liabilitie'=> 'Liabilities'
    ],

    'allowance' => [
        'house_allowance' => [
            'title' => 'House Allowance',
            'type' => 'increment'
        ],
        'medical_allowance' => [
            'title' => 'Medical Allowance',
            'type' => 'increment'
        ],
        'transport_allowance' => [
            'title' => 'Transport Allowance',
            'type' => 'increment'
        ],
        'deductions' => [
            'title' => 'Deductions',
            'type' => 'decrement'
        ],

    ]

];
