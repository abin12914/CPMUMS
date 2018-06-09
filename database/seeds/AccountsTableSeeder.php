<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            [
                'account_name'      => 'Cash', //account id : 1
                'description'       => 'Cash account',
                'type'              => 1, //real account
                'relation'          => 0, //real
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Cash account',
                'status'            => 1,
            ],
            [
                'account_name'      => 'Sales', //account id : 2
                'description'       => 'Sales account',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Sales account',
                'status'            => 1,  
            ],
            [
                'account_name'      => 'Purchases', //account id : 3
                'description'       => 'Purchases account',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Purchases account',
                'status'            => 1,  
            ],
            [
                'account_name'      => 'Temporary Credit', //account id : 4
                'description'       => 'Temporary credit account',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Temporary credit account',
                'status'            => 1,
            ],
            [
                'account_name'      => 'Employee Wage', //account id : 5
                'description'       => 'Employee wage account',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Employee wage account',
                'status'            => 1,
            ],
            [
                'account_name'      => 'Employee Salary', //account id : 6
                'description'       => 'Employee salary account',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Employee salary account',
                'status'            => 1,
            ],
            [
                'account_name'      => 'Service And Expenses', //account id : 7
                'description'       => 'Service and expense account',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Service and expense account',
                'status'            => 1,
            ],
            [
                'account_name'      => 'Account Opening Balance', //account id : 8
                'description'       => 'Account opening Balance account',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Account opening Balance account',
                'status'            => 1,
            ],
            [
                'account_name'      => 'Temp1', //account id : 9
                'description'       => 'Temporary account 1',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Temporary account 1',
                'status'            => 0,
            ],
            [
                'account_name'      => 'Temp2', //account id : 10
                'description'       => 'Temporary account 2',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Temporary account 2',
                'status'            => 0,
            ],
            [
                'account_name'      => 'Temp3', //account id : 11
                'description'       => 'Temporary account 3',
                'type'              => 2, //nominal account
                'relation'          => 0, //nominal
                'financial_status'  => 0, //none
                'opening_balance'   => 0,
                'name'              => 'Temporary account 3',
                'status'            => 0,
            ],
        ]);
    }
}
