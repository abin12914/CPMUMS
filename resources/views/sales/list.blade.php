@extends('layouts.app')
@section('title', 'Sale List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sale
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sale List</li>
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
                        <form action="{{ route('sale.index') }}" method="get" class="form-horizontal" autocomplete="off">
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
                                        <div class="col-md-4 {{ !empty($errors->first('branch_id')) ? 'has-error' : '' }}">
                                            <label for="branch_id" class="control-label">Branch : </label>
                                            {{-- adding branch select component --}}
                                            @component('components.selects.branches', ['selectedBranchId' => $params[2]['paramValue'], 'selectName' => 'branch_id', 'tabindex' => 3])
                                            @endcomponent
                                            @if(!empty($errors->first('branch_id')))
                                                <p style="color: red;" >{{$errors->first('branch_id')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4 {{ !empty($errors->first('customer_type')) ? 'has-error' : '' }}">
                                            <label for="customer_type" class="control-label">Customer Type : </label>
                                            <select class="form-control select2" name="customer_type" id="customer_type" tabindex="6" style="width: 100%;">
                                                <option value="" {{ empty(old('customer_type')) ? 'selected' : '' }}>Select customer type</option>
                                                <option value="1" {{ (old('relation_type') == 1 || $params[3]['paramValue'] == 1) ? 'selected' : '' }}>Long term credit customer</option>
                                                <option value="2" {{ (old('relation_type') == 2 || $params[3]['paramValue'] == 2) ? 'selected' : '' }}>Short term credit customer</option>
                                            </select>
                                            @if(!empty($errors->first('relation_type')))
                                                <p style="color: red;" >{{$errors->first('relation_type')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('customer_account_id')) ? 'has-error' : '' }}">
                                            <label for="customer_account_id" class="control-label">Customer : </label>
                                            {{-- adding account select component --}}
                                            @component('components.selects.accounts', ['selectedAccountId' => $params[4]['paramValue'], 'cashAccountFlag' => true, 'selectName' => 'customer_account_id', 'tabindex' => 5])
                                            @endcomponent
                                            @if(!empty($errors->first('customer_account_id')))
                                                <p style="color: red;" >{{$errors->first('customer_account_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('no_of_records')) ? 'has-error' : '' }}">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            {{-- adding no of records text component --}}
                                            @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 6])
                                            @endcomponent
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
                            <div class="col-md-12" style="overflow:scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 10%;">Date</th>
                                            <th style="width: 25%;">Branch</th>
                                            <th style="width: 35%;">Customer</th>
                                            <th style="width: 15%;">Bill Amount</th>
                                            <th style="width: 5%;" class="no-print"></th>
                                            <th style="width: 5%;" class="no-print"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($saleRecords))
                                            @foreach($saleRecords as $index => $saleRecord)
                                                <tr>
                                                    <td>{{ $index + $saleRecords->firstItem() }}</td>
                                                    <td>{{ Carbon\Carbon::parse($saleRecord->date)->format('d-m-Y') }}</td>
                                                    <td>{{ $saleRecord->branch->name }}</td>
                                                    @if($saleRecord->transaction->debitAccount->status == 1)
                                                        <td>{{ $saleRecord->transaction->debitAccount->account_name }}</td>
                                                    @else
                                                        <td>
                                                            {{ $saleRecord->transaction->debitAccount->account_name }}
                                                            &nbsp;<i class="fa fa-clock-o text-orange" title="Short term credit customer"></i>
                                                        </td>
                                                    @endif
                                                    <td>{{ $saleRecord->total_amount }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('sale.edit', ['id' => $saleRecord->id]) }}" style="float: left;">
                                                            <button type="button" class="btn btn-warning">Edit</button>
                                                        </a>
                                                    </td>
                                                    <td class="no-print">
                                                        <form action="{{ route('sale.destroy', $saleRecord->id) }}" method="post" class="form-horizontal">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
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
                                @if(!empty($saleRecords))
                                    <div>
                                        Showing {{ $saleRecords->firstItem(). " - ". $saleRecords->lastItem(). " of ". $saleRecords->total() }}<br>
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $saleRecords->appends(Request::all())->links() }}
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