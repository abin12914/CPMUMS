<?php

return [

    'controller_code' =>  [
        'AccountController'       => '01',
        'BranchController'        => '02',
        'EmployeeController'      => '03',
        'EmployeeWageController'  => '04',
        'ExpenseController'       => '05',
        'HomeController'          => '06',
        'ProductController'       => '07',
        'ProductionController'    => '08',
        'PurchaseController'      => '09',
        'ReportController'        => '10',
        'SaleController'          => '11',
        'VoucherController'       => '12',
    ],
    'repository_code' =>  [
        'AccountRepository'         => 100,
        'BranchRepository'          => 200,
        'EmployeeRepository'        => 300,
        'EmployeeWageRepository'    => 400,
        'ExpenseRepository'         => 500,
        'MaterialRepository'        => 600,
        'ProductionRepository'      => 700,
        'ProductRepository'         => 800,
        'PurchaseRepository'        => 900,
        'SaleRepository'            => 1000,
        'ServiceRepository'         => 1100,
        'TransactionRepository'     => 1200,
        'TransportationRepository'  => 1300,
        'UserRepository'            => 1400,
        'VoucherRepository'         => 1500,
    ],
    'composer_code' =>  [
        'BranchComponentComposer'   => 5000,
        'AccountComponentComposer'  => 5100,
        'EmployeeComponentComposer' => 5200,
        'ProductComponentComposer'  => 5300,
        'MaterialComponentComposer' => 5400,
        'ServiceComponentComposer'  => 5500,
    ],
    'no_of_record_per_page' => env('NO_OF_RECORD_PER_PAGE', 25),
    'print_head_flag'       => env('PRINT_HEAD_FLAG', true),
    'display_phone_flag'    => env('DISPLAY_PHONE_FLAG', false),
    'company_name'          => env('COMPANY_NAME', 'CPMUMS'),
    'company_address'       => env('COMPANY_ADDRESS', 'ADDRESS'),
    'company_phones'        => env('COMPANY_PHONE', 'PHONE'),
    'company_GSTIN'         => env('COMPANY_GSTIN', 'GSTIN'),
];
