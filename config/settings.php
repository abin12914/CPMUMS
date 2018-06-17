<?php

return [

    'controller_code'           =>  [
                                    'Branch'        => '01',
                                    'Account'       => '02',
                                    'Transaction'   => '03',
                                    'Employee'      => '04',
                                    'Expense'       => '05',
                                ],
    'repository_code'           =>  [
                                    'BranchRepository'      => 100,
                                    'AccountRepository'     => 200,
                                    'TransactionRepository' => 300,
                                    'EmployeeRepository'    => 400,
                                    'ExpenseRepository'     => 500,
                                ],
    'model_namespace'       => "App\Models\\",
    'no_of_record_per_page' => 15,
    'company_name'          => "CPMUMS",
    'company_address'       => "ELAVANTHY, ANAPPARA",
    'company_phones'        => "+91 9447171143, +91 9645901143",
];
