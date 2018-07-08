@extends('layouts.app')
@section('title', 'Account Statement')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account Statement
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Account Statement</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
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
                                        <div class="col-md-4">
                                            <label for="account_id" class="control-label">Account : </label>
                                            {{-- adding account select component --}}
                                            @component('components.selects.accounts', ['selectedAccountId' => $params['account_id']['paramValue'], 'cashAccountFlag' => true, 'selectName' => 'account_id', 'activeFlag' => false, 'tabindex' => 1])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="from_date" class="control-label">Start Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="from_date" id="from_date" placeholder="Date" value="{{ $params['from_date']['paramValue'] or old('from_date') }}" tabindex="2">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'from_date'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="to_date" class="control-label">End Date : </label>
                                            <input type="text" class="form-control decimal_number_only datepicker" name="to_date" id="to_date" placeholder="Date" value="{{ $params['to_date']['paramValue'] or old('to_date') }}" tabindex="3">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'to_date'])
                                            @endcomponent
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label for="relation" class="control-label">Transaction Relation : </label>
                                            <select class="form-control select2" name="relation" id="relation" style="width: 100%" tabindex="4">
                                                @if(!empty($relations) && (count($relations) > 0))
                                                    <option value="">Select relation</option>
                                                    @foreach($relations as $key => $relation)
                                                        <option value="{{ $key }}" {{ ((old('relation') == $key ) || $params['relation']['paramValue'] == $key) ? 'selected' : '' }}>{{ $relation['displayName'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'relation'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="transaction_type" class="control-label">
                                                Transaction Type : 
                                            </label>
                                            <select class="form-control select2" name="transaction_type" id="transaction_type" style="width: 100%" tabindex="5">
                                                <option value="0" {{ (empty(old('transaction_type'))  || old('transaction_type') == 0 || $transactionType == 0) ? 'selected' : '' }}>All type</option>
                                                <option value="1" {{ ((old('transaction_type') == 1 ) || $transactionType == 1) ? 'selected' : '' }}>Debit</option>
                                                <option value="2" {{ ((old('transaction_type') == 2 ) || $transactionType == 2) ? 'selected' : '' }}>Credit</option>
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'transaction_type'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            {{-- adding no of records text component --}}
                                            @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 6])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'no_of_records'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row no-print">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="8">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="7"><i class="fa fa-search"></i> Get</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div><br>
                    <div class="box-body no-print">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                @if(!empty($account))
                                    <h4 style="color: darkblue;">Account Overview</h4>
                                    <table class="table table-responsive table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 45%;">Account Name</th>
                                                <th style="width: 25%;">Total Debit</th>
                                                <th style="width: 25%;">Total Credit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class="fa fa-bookmark-o"></i></td>
                                                <td class="text-orange">{{ $account->account_name }}</td>
                                                <td class="text-orange">{{ $outstandingDebit }}</td>
                                                <td class="text-orange">{{ $outstandingCredit }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><i class="fa fa-calculator"></i></td>
                                                @if($outstandingDebit > $outstandingCredit)
                                                    <td class="text-red"><strong> Balance To Get</strong></td>
                                                    <td></td>
                                                    <td class="text-red"><strong> {{ $outstandingDebit - $outstandingCredit }}</strong></td>
                                                @else
                                                    <td class="text-green"><strong> Balance To Pay</strong></td>
                                                    <td class="text-green"><strong> {{ $outstandingCredit - $outstandingDebit }}</strong></td>
                                                    <td></td>
                                                @endif
                                            </tr>
                                        </tfoot>
                                    </table>
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
                            @if(!empty($account))
                                Ledger of <b>{{ $account->account_name }}</b>
                                - [ {{ $params['from_date']['paramValue'] or 'starting' }} - {{ $params['to_date']['paramValue'] or 'end' }} ]
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
                                            <th style="width: 15%;">Date & Time</th>
                                            <th style="width: 60%;">Particulars</th>
                                            <th style="width: 10%;">Debit</th>
                                            <th style="width: 10%;">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($transactions))
                                            @foreach($transactions as $index => $transaction)
                                                <tr>
                                                    <td>
                                                        {{ $index + $transactions->firstItem() }}
                                                        <i class="no-print bg-info"> / {{ $transaction->id }}</i>
                                                    </td>
                                                    <td>{{ $transaction->transaction_date->format('d-m-Y') }}</td>
                                                    <td>{{ $transaction->particulars }}</td>
                                                    @if($transaction->debit_account_id == $account->id)
                                                        <td>{{ $transaction->amount }}</td>
                                                        <td></td>
                                                    @elseif($transaction->credit_account_id == $account->id)
                                                        <td></td>
                                                        <td>{{ $transaction->amount }}</td>
                                                    @else
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            @if(Request::get('page') == $transactions->lastPage() || $transactions->lastPage() == 1)
                                                <tr>
                                                    <td></td><td></td><td></td><td></td><td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>Sub Total</strong></td>
                                                    <td><strong>{{ $subTotalDebit }}</strong></td>
                                                    <td><strong>{{ $subTotalCredit }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    @if($obDebit > $obCredit)
                                                        <td class="text-red"><strong> Old Balance To Get</strong></td>
                                                        <td class="text-red"><strong> {{ $obDebit - $obCredit }}</strong></td>
                                                        <td></td>
                                                    @else
                                                        <td class="text-green"><strong> Old Balance To Pay</strong></td>
                                                        <td></td>
                                                        <td class="text-green"><strong> {{ $obCredit - $obDebit }}</strong></td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td><strong>Total Amount</strong></td>
                                                    <td><strong>{{ $subTotalDebit + $obDebit }}</strong></td>
                                                    <td><strong>{{ $subTotalCredit + $obCredit }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td></td><td></td><td></td><td></td><td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    @if($outstandingDebit > $outstandingCredit)
                                                        <td class="text-red"><strong> Outstanding Balance To Get</strong></td>
                                                        <td></td>
                                                        <td class="text-red"><strong> {{ $outstandingDebit - $outstandingCredit }}</strong></td>
                                                    @else
                                                        <td class="text-green"><strong> Balance To Pay</strong></td>
                                                        <td class="text-green"><strong> {{ $outstandingCredit - $outstandingDebit }}</strong></td>
                                                        <td></td>
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