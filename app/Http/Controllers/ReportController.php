<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Http\Requests\TransactionFilterRequest;
use Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReportController extends Controller
{
    protected $transactionRepo, $accountRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(TransactionRepository $transactionRepo, AccountRepository $accountRepo)
    {
        $this->transactionRepo      = $transactionRepo;
        $this->accountRepo          = $accountRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.ReportController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accountStatement(TransactionFilterRequest $request)
    {
        $obDebit            = 0;
        $obCredit           = 0;
        
        $accountId          = $request->get('account_id');
        $fromDate           = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : "";
        $toDate             = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : "";
        $noOfRecords        = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;
        $relation           = $request->get('relation');
        $transactionType    = !empty($request->get('transaction_type')) ? $request->get('transaction_type') : 0;

        try {
            if(empty($accountId)) {
                //if no account is selected use cash account
                $accountId = config('constants.accountConstants.Cash.id');
            }

            //confirming account existency.
            $account = $this->accountRepo->getAccount($accountId, false);

            $params = [
                'from_date' =>  [
                    'paramName'     => 'transaction_date',
                    'paramOperator' => '>=',
                    'paramValue'    => $fromDate,
                ],
                'to_date'   =>  [
                    'paramName'     => 'transaction_date',
                    'paramOperator' => '<=',
                    'paramValue'    => $toDate,
                ],
            ];

            $debitParam = [
                'debit_account_id'   =>  [
                    'paramName'      => 'debit_account_id',
                    'paramOperator'  => '=',
                    'paramValue'     => $accountId,
                ],
            ];

            $creditParam = [
                'credit_account_id'  =>  [
                    'paramName'      => 'credit_account_id',
                    'paramOperator'  => '=',
                    'paramValue'     => $accountId,
                ]
            ];

            $obParam = [
                'from_date' =>  [
                    'paramName'     => 'transaction_date',
                    'paramOperator' => '<',
                    'paramValue'    => $fromDate,
                ]
            ];

            $orParams = array_merge($debitParam, $creditParam);

            if($transactionType == 1) {
                //if user select debit transactions only then remove credit transaction checking (or condition)
                unset($orParams['credit_account_id']);
            } elseif ($transactionType == 2) {
                //if user select credit transactions only then remove debit transaction checking (or condition)
                unset($orParams['debit_account_id']);
            } //else both transactions are included with or condition

            //display data
            $transactions   = $this->transactionRepo->getTransactions($params, $orParams, $relation, $noOfRecords);

            //subtotal values
            $subTotaltransactions = $this->transactionRepo->getTransactions($params, $orParams, $relation, null);
            $subTotalDebit  = $subTotaltransactions->where('debit_account_id', $accountId)->sum('amount');
            $subTotalCredit = $subTotaltransactions->where('credit_account_id', $accountId)->sum('amount');

            //outstanding values
            $outstandingDebit   = $this->transactionRepo->getTransactions([], $debitParam, null, null)->sum('amount');
            $outstandingCredit  = $this->transactionRepo->getTransactions([], $creditParam, null, null)->sum('amount');

            //old balance values
            if(!empty($fromDate)) {
                $obtransactions = $this->transactionRepo->getTransactions($obParam, $orParams, $relation, null);
                $obDebit        = $obtransactions->where('debit_account_id', $accountId)->sum('amount');
                $obCredit       = $obtransactions->where('credit_account_id', $accountId)->sum('amount');
            }

        } catch(Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }

            throw new AppCustomException("CustomError", $errorCode);
            
        }

        //params passing for auto selection
        $params['from_date']['paramValue']  = $request->get('from_date');
        $params['to_date']['paramValue']    = $request->get('to_date');
        $params['relation']['paramValue']   = $relation;
        $params['account_id']['paramValue'] = $accountId;
        $params = array_merge($params, $orParams);

        return view('reports.account-statement', [
            'transactions'          => $transactions,
            'params'                => $params,
            'relations'             => config('constants.transactionRelations'),
            'transactionType'       => $transactionType,
            'noOfRecords'           => $noOfRecords,
            'account'               => $account,
            'outstandingDebit'      => $outstandingDebit,
            'outstandingCredit'     => $outstandingCredit,
            'subTotalDebit'         => $subTotalDebit,
            'subTotalCredit'        => $subTotalCredit,
            'obDebit'               => $obDebit,
            'obCredit'              => $obCredit
        ]);
    }
}
