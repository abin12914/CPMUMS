<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\AccountRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class AccountComponentComposer
{
    protected $accounts = [], $cashAccount, $errorHead = null;

    /**
     * Create a new account partial composer.
     *
     * @param  AccountRepository  $account
     * @return void
     */
    public function __construct(AccountRepository $accountRepo)
    {
        $errorCode          = 0;
        $this->errorHead    = config('settings.composer_code.AccountComponentComposer');
        $cashAccountId      = config('constants.accountConstants.Cash.id');
        
        try {
            $this->accounts     = $accountRepo->getAccounts([], null, true, false);
            $this->cashAccount  = $accountRepo->getAccount($cashAccountId);//retrieving cash account

            if(empty($this->accounts) && count($this->accounts) <= 0) {
                $this->cashAccount = collect([$this->cashAccount]); //making a collection
            }
            $this->accounts->push($this->cashAccount); //pushing cash account to account list
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
            
            //throw new AppCustomException("CustomError", ($this->errorHead + $errorCode));
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['accountsCombo' => $this->accounts]);
    }
}