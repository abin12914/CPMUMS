@extends('layouts.app')
@section('title', 'Vouchers & Receipts List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Vouchers & Receipts
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Vouchers & Receipts List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if (Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                    {{ Session::get('message') }}
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row  no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('voucher.index') }}" method="get" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-4 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                                            <label for="from_date" class="control-label">From Date : </label>
                                            <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="{{ !empty(old('from_date')) ? old('from_date') : $params[0]['paramValue'] }}" tabindex="1">
                                            @if(!empty($errors->first('from_date')))
                                                <p style="color: red;" >{{$errors->first('from_date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
                                            <label for="to_date" class="control-label">To Date : </label>
                                            <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="{{ !empty(old('to_date')) ? old('to_date') : $params[1]['paramValue'] }}" tabindex="2">
                                            @if(!empty($errors->first('to_date')))
                                                <p style="color: red;" >{{$errors->first('to_date')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label">Supplier : </label>
                                            <select class="form-control select2" name="account_id" id="account_id" style="width: 100%" tabindex="3">
                                                <option value="">Select account</option>
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ (old('account_id') == $account->id || $params[3]['paramValue'] == $account->id) ? 'selected' : '' }}>
                                                            {{ $account->account_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('account_id')))
                                                <p style="color: red;" >{{$errors->first('account_id')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4 {{ !empty($errors->first('transaction_type_debit')) ? 'has-error' : '' }}">
                                            <label class="control-label"><b style="color: red;">* </b> Transaction Type : </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="checkbox" name="transaction_type_debit" id="transaction_type_debit" value="1" {{ ((empty($params[2]['paramValue']) && empty($params[2]['paramValue1'])) || !empty($params[2]['paramValue'])) ? 'checked' : ''}} tabindex="4">
                                                </span>
                                                <label for="transaction_type_debit" class="form-control">Debit / Reciept</label>
                                            </div>
                                            @if(!empty($errors->first('transaction_type_debit')))
                                                <p style="color: red;" >{{$errors->first('transaction_type_debit')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('transaction_type_credit')) ? 'has-error' : '' }}">
                                            <label class="control-label"><b style="color: red;">* </b> Transaction Type : </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="checkbox" name="transaction_type_credit" id="transaction_type_credit" value="2"  {{ ((empty($params[2]['paramValue']) && empty($params[2]['paramValue1'])) || !empty($params[2]['paramValue1'])) ? 'checked' : ''}} tabindex="5">
                                                </span>
                                                <label for="transaction_type_credit" class="form-control">Credit / Voucher</label>
                                            </div>
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
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="7">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="8"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    {{-- page header for printers --}}
                    @include('sections.print-head')
                    <div class="box-header no-print">
                        @foreach($params as $param)
                            @if(!empty($param['paramValue']))
                                <b>Filters applied!</b>
                                @break
                            @endif
                        @endforeach
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 15%;">Date</th>
                                            <th style="width: 20%;">Account</th>
                                            <th style="width: 10%;">Transaction Type</th>
                                            <th style="width: 20%;">Cash Debit</th>
                                            <th style="width: 20%;">Cash Credit</th>
                                            <th style="width: 10%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($vouchers))
                                            @foreach($vouchers as $index => $voucher)
                                                <tr>
                                                    <td>{{ $index + $vouchers->firstItem() }}</td>
                                                    <td>{{ Carbon\Carbon::parse($voucher->date)->format('d-m-Y') }}</td>
                                                    @if($voucher->transaction_type == 1)
                                                        <td>{{ $voucher->transaction->creditAccount->account_name }}</td>
                                                        <td>Receipt</td>
                                                        <td>{{ $voucher->amount }}</td>
                                                        <td></td>
                                                    @else
                                                        <td>{{ $voucher->transaction->debitAccount->account_name }}</td>
                                                        <td>Voucher</td>
                                                        <td></td>
                                                        <td>{{ $voucher->amount }}</td>
                                                    @endif
                                                    <td class="no-print">
                                                        <a href="{{ route('vouchers.show', ['id' => $voucher->id]) }}">
                                                            <button type="button" class="btn btn-default">Details</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($vouchers))
                                    <div>
                                        Showing {{ $vouchers->firstItem(). " - ". $vouchers->lastItem(). " of ". $vouchers->total() }}<br>
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $vouchers->appends(Request::all())->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.boxy -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection