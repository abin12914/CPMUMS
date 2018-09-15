<?php

return [
    'userRoles' => [
        'superadmin' => 0,
        'admin'      => 1,
        'user'       => 2,
    ],

    'accountRelationTypes' => [
        1 => 'Employees',
        2 => 'Supplier',
        3 => 'Customer',
        4 => 'Contractor',
        5 => 'General',
    ],

    'accountTypes' => [
        1 => 'Real',
        2 => 'Nominal',
        3 => 'Personal',
    ],

    'employeeWageTypes' => [
        1 => 'Per Month',
        2 => 'Per Day',
        3 => 'Per Mould',
    ],

    'transactionRelations' => [
        1 => [
            'relationName'  => 'purchase',
            'displayName'   => 'Purchase'
        ],
        2 => [
            'relationName'  => 'employeeWage',
            'displayName'   => 'Employee Wage'
        ],
        3 => [
            'relationName'  => 'sale',
            'displayName'   => 'Sale'
        ],
        4 => [
            'relationName'  => 'transportation',
            'displayName'   => 'Transportation'
        ],
        5 => [
            'relationName'  => 'expense',
            'displayName'   => 'Expense'
        ],
        6 => [
            'relationName'  => 'voucher',
            'displayName'   => 'Voucher'
        ]
    ],

    'branchInvoiceCode' => [
        1  => 'A',
        2  => 'B',
        3  => 'C',
        4  => 'D',
        5  => 'E',
        6  => 'F',
        7  => 'G',
        8  => 'H',
        9  => 'I',
        10 => 'J',
    ],

    'accountConstants' => [
        'Cash' => [
            'id'                => 1,
            'account_name'      => 'Cash', //account id : 1
            'description'       => 'Cash account',
            'type'              => 1, //real account
            'relation'          => 0, //real
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Cash account',
            'phone'             => '0000000001',
            'status'            => 1,
        ],

        'Sale' => [
            'id'                => 2,
            'account_name'      => 'Sales', //account id : 2
            'description'       => 'Sales account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Sales account',
            'phone'             => '0000000002',
            'status'            => 1,  
        ],

        'Purchase' => [
            'id'                => 3,
            'account_name'      => 'Purchases', //account id : 3
            'description'       => 'Purchases account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Purchases account',
            'phone'             => '0000000003',
            'status'            => 1,  
        ],

        'EmployeeWage' => [
            'id'                => 4,
            'account_name'      => 'Employee Wage', //account id : 4
            'description'       => 'Employee wage account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Employee wage account',
            'phone'             => '0000000004',
            'status'            => 1,
        ],

        'EmployeeSalary' => [
            'id'                => 5,
            'account_name'      => 'Employee Salary', //account id : 5
            'description'       => 'Employee salary account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Employee salary account',
            'phone'             => '0000000005',
            'status'            => 1,
        ],

        'ServiceAndExpense' => [
            'id'                => 6,
            'account_name'      => 'Service And Expenses', //account id : 6
            'description'       => 'Service and expense account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Service and expense account',
            'phone'             => '0000000006',
            'status'            => 1,
        ],

        'AccountOpeningBalance' => [
            'id'                => 7,
            'account_name'      => 'Account Opening Balance', //account id : 7
            'description'       => 'Account opening balance account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Account opening balance account',
            'phone'             => '0000000007',
            'status'            => 1,
        ],

        'TransportationChargeAccount' => [
            'id'                => 8,
            'account_name'      => 'Transportation Charge Account', //account id : 8
            'description'       => 'Transportation charge account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Transportation charge account',
            'phone'             => '0000000008',
            'status'            => 1,
        ],

        'LoadingChargeAccount' => [
            'id'                => 9,
            'account_name'      => 'Loading Charge Account', //account id : 9
            'description'       => 'Loading charge account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Loading charge account',
            'phone'             => '0000000009',
            'status'            => 1,
        ],

        'Temp2' => [
            'id'                => 10,
            'account_name'      => 'Temp2', //account id : 10
            'description'       => 'Temporary account 2',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Temporary account 2',
            'phone'             => '0000000010',
            'status'            => 0,
        ],
    ],
];