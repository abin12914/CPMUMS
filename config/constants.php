<?php

return [

    'accountRelationTypes' => [
        1   => 'Supplier',
        2   => 'Customer',
        3   => 'Contractor',
        4   => 'General',
        5   => 'Employees',
    ],

    'accountTypes' => [
        1   => 'Real',
        2   => 'Nominal',
        3   => 'Personal',
    ],

    'employeeWageTypes' => [
        1   => 'Per Month',
        2   => 'Per Day',
        3   => 'Per Piece',
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

        'ShortTermCredit' => [
            'id'                => 4,
            'account_name'      => 'Short Term Credit', //account id : 4
            'description'       => 'Short term credit account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Short term credit account',
            'phone'             => '0000000004',
            'status'            => 1,
        ],

        'EmployeeWage' => [
            'id'                => 5,
            'account_name'      => 'Employee Wage', //account id : 5
            'description'       => 'Employee wage account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Employee wage account',
            'phone'             => '0000000005',
            'status'            => 1,
        ],

        'EmployeeSalary' => [
            'id'                => 6,
            'account_name'      => 'Employee Salary', //account id : 6
            'description'       => 'Employee salary account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Employee salary account',
            'phone'             => '0000000006',
            'status'            => 1,
        ],

        'ServiceAndExpense' => [
            'id'                => 7,
            'account_name'      => 'Service And Expenses', //account id : 7
            'description'       => 'Service and expense account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Service and expense account',
            'phone'             => '0000000007',
            'status'            => 1,
        ],

        'AccountOpeningBalance' => [
            'id'                => 8,
            'account_name'      => 'Account Opening Balance', //account id : 8
            'description'       => 'Account opening Balance account',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Account opening Balance account',
            'phone'             => '0000000008',
            'status'            => 1,
        ],

        'Temp1' => [
            'id'                => 9,
            'account_name'      => 'Temp1', //account id : 9
            'description'       => 'Temporary account 1',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Temporary account 1',
            'phone'             => '0000000009',
            'status'            => 0,
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

        'Temp3' => [
            'id'                => 11,
            'account_name'      => 'Temp3', //account id : 11
            'description'       => 'Temporary account 3',
            'type'              => 2, //nominal account
            'relation'          => 0, //nominal
            'financial_status'  => 0, //none
            'opening_balance'   => 0,
            'name'              => 'Temporary account 3',
            'phone'             => '0000000011',
            'status'            => 0,
        ],
    ],
];