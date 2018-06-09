@extends('layouts.app')
@section('title', 'Account Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account Statement
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Account Statement</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                    {{ Session::get('message') }}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <form action="{{ route('report.account-statement') }}" method="get" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-4 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label">Account : </label>
                                            <select class="form-control select2" name="account_id" id="account_id" tabindex="1" style="width: 100%">
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    <option value="">Select account</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ ((old('account_id') == $account->id ) || $params['account_id'] == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('account_id')))
                                                <p style="color: red;" >{{$errors->first('account_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                                            <label for="from_date" class="control-label">Start Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="from_date" id="from_date" placeholder="Date" value="{{ $params['from_date'] or old('from_date') }}" tabindex="2">
                                            @if(!empty($errors->first('from_date')))
                                                <p style="color: red;" >{{$errors->first('from_date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
                                            <label for="to_date" class="control-label">End Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="to_date" id="to_date" placeholder="Date" value="{{ $params['to_date'] or old('to_date') }}" tabindex="3">
                                            @if(!empty($errors->first('to_date')))
                                                <p style="color: red;" >{{$errors->first('to_date')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4 {{ !empty($errors->first('relation_type')) ? 'has-error' : '' }}">
                                            <label for="relation_type" class="control-label">Transaction Relation : </label>
                                            <select class="form-control select2" name="relation_type" id="relation_type" style="width: 100%" tabindex="4">
                                                @if(!empty($relations) && (count($relations) > 0))
                                                    <option value="">Select relation</option>
                                                    @foreach($relations as $key => $relation)
                                                        <option value="{{ $key }}" {{ ((old('relation_type') == $key ) || $params['relation_type'] == $key) ? 'selected' : '' }}>{{ $relation }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('relation_type')))
                                                <p style="color: red;" >{{$errors->first('relation_type')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('transaction_type')) ? 'has-error' : '' }}">
                                            <label for="transaction_type" class="control-label">
                                                Transaction Type : 
                                            </label>
                                            <select class="form-control select2" name="transaction_type" id="transaction_type" style="width: 100%" tabindex="5">
                                                <option value="">All type</option>
                                                <option value="1" {{ ((old('transaction_type') == 1 ) || $params['transaction_type'] == 1) ? 'selected' : '' }}>Debit</option>
                                                <option value="2" {{ ((old('transaction_type') == 2 ) || $params['transaction_type'] == 2) ? 'selected' : '' }}>Credit</option>
                                            </select>
                                            @if(!empty($errors->first('transaction_type')))
                                                <p style="color: red;" >{{$errors->first('transaction_type')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('no_of_records')) ? 'has-error' : '' }}">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            <input type="text" class="form-control" name="no_of_records" id="no_of_records" value="{{ !empty(old('no_of_records')) ? old('no_of_records') : $noOfRecords }}" tabindex="6">
                                            @if(!empty($errors->first('no_of_records')))
                                                <p style="color: red;" >{{$errors->first('no_of_records')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row no-print">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="7">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="8"><i class="fa fa-search"></i> Get</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                @if(!empty($accounts->firstWhere('id', $params['account_id'])) && !empty($accounts->firstWhere('id', $params['account_id'])->account_name))
                                    <div class="box-header">
                                        <div class="pad margin no-print">
                                            <div class="callout callout-default">
                                                <h4 style="color: green;">Account Overview</h4>
                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="color: orangered;">
                                                        <tr>
                                                            <th style="width:45%">
                                                                <span class="badge bg-black"><i class="fa fa-book"></i></span>&nbsp&nbsp Account Name
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-light-blue" style="width:100%; font-size: 15px;">
                                                                    @if(!empty($accounts->firstWhere('id', $params['account_id'])))
                                                                        {{ $accounts->firstWhere('id', $params['account_id'])->account_name }}
                                                                    @else
                                                                        Error
                                                                    @endif
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <span class="badge bg-black"><i class="fa fa-arrow-down"></i></span>&nbsp&nbsp Total Debit
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-yellow" style="width:100%; font-size: 15px;">{{ $overviewDebit }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <span class="badge bg-black"><i class="fa fa-arrow-up"></i></span>&nbsp&nbsp Total Credit
                                                                <b class="pull-right">:</b>
                                                            </th>
                                                            <td>
                                                                <span class="badge bg-orange" style="width:100%; font-size: 15px;">
                                                                    {{ $overviewCredit }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            @if($overviewDebit >= $overviewCredit)
                                                                <th>
                                                                    <span class="badge bg-black"><i class="fa fa-inr"></i></span>&nbsp&nbsp Balance To Get
                                                                    <b class="pull-right">:</b>
                                                                </th>
                                                                <td>
                                                                    <span class="badge bg-green" style="width:100%; font-size: 15px;">
                                                                        {{ $overviewDebit - $overviewCredit }}
                                                                    </span>
                                                                </td>
                                                            @else
                                                                <th>
                                                                    <span class="badge bg-black"><i class="fa fa-inr"></i></span>&nbsp&nbsp Balance To Pay
                                                                    <b class="pull-right">:</b>
                                                                </th>
                                                                <td>
                                                                    <span class="badge bg-red" style="width:100%; font-size: 15px;">{{ $overviewCredit - $overviewDebit }}</span>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    {{-- page header for printers --}}
                    @include('sections.print-head')
                    <div class="box-header">
                        @foreach($params as $param)
                            @if(!empty($param))
                                <b class="no-print">Filters applied!</b>
                                @break
                            @endif
                        @endforeach
                        <h4>
                            @if($accounts->firstWhere('id', $params['account_id']))
                                Ledger of <b>{{ $accounts->firstWhere('id', $params['account_id'])->account_name }}</b>
                                - [ {{ $params['from_date'] or 'starting' }} - {{ $params['to_date'] or 'end' }} ]
                            @else
                                Error
                            @endif
                        </h4>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 10%;">Date & Time</th>
                                            <th style="width: 5%;" class="no-print">Ref No.</th>
                                            <th style="width: 60%;">Particulars</th>
                                            <th style="width: 10%;">Debit</th>
                                            <th style="width: 10%;">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($transactions))
                                            @foreach($transactions as $index => $transaction)
                                                <tr>
                                                    <td>{{ $index + $transactions->firstItem() }}</td>
                                                    <td>{{ Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                                                    <td class="no-print">{{ $transaction->id }}</td>
                                                    <td>{{ $transaction->particulars }}</td>
                                                    @if($transaction->debit_account_id == $params['account_id'])
                                                        <td>{{ $transaction->amount }}</td>
                                                        <td>-</td>
                                                    @elseif($transaction->credit_account_id == $params['account_id'])
                                                        <td>-</td>
                                                        <td>{{ $transaction->amount }}</td>
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            @if(Request::get('page') == $transactions->lastPage() || $transactions->lastPage() == 1)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="no-print"></td>
                                                    <td>Sub Total</td>
                                                    <td>{{ $subtotalDebit }}</td>
                                                    <td>{{ $subtotalCredit }}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="no-print"></td>
                                                    <td>Old Balance</td>
                                                    <td>{{ $obDebit }}</td>
                                                    <td>{{ $obCredit }}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="no-print"></td>
                                                    <td>Total Amount</td>
                                                    <td>{{ $subtotalDebit + $obDebit }}</td>
                                                    <td>{{ $subtotalCredit + $obCredit }}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="no-print"></td>
                                                    <td>{{ 'Balance' }}</td>
                                                    @if($totalDebit <= $totalCredit)
                                                        <td>{{ $totalCredit - $totalDebit }}</td>
                                                        <td></td>
                                                    @else
                                                        <td></td>
                                                        <td>{{ $totalDebit - $totalCredit }}</td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($transactions))
                                    <div>
                                        Showing {{ $transactions->firstItem(). " - ". $transactions->lastItem(). " of ". $transactions->total() }}<br>
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $transactions->appends(Request::all())->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection