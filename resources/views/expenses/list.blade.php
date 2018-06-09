@extends('layouts.app')
@section('title', 'Expense List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Expense
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Expense List</li>
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
                        <form action="{{ route('expense.index') }}" method="get" class="form-horizontal" autocomplete="off">
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
                                        <div class="col-md-4 {{ !empty($errors->first('supplier_account_id')) ? 'has-error' : '' }}">
                                            <label for="supplier_account_id" class="control-label">Supplier : </label>
                                            <select class="form-control select2" name="supplier_account_id" id="supplier_account_id" style="width: 100%" tabindex="3">
                                                <option value="">Select account</option>
                                                @if(!empty($accounts) && (count($accounts) > 0))
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ (old('supplier_account_id') == $account->id || $params[4]['paramValue'] == $account->id) ? 'selected' : '' }}>
                                                            {{ $account->account_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('supplier_account_id')))
                                                <p style="color: red;" >{{$errors->first('supplier_account_id')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4 {{ !empty($errors->first('truck_id')) ? 'has-error' : '' }}">
                                            <label for="truck_id" class="control-label">Truck : </label>
                                            <select class="form-control select2" name="truck_id" id="truck_id" style="width: 100%" tabindex="4">
                                                <option value="">Select truck</option>
                                                @if(!empty($trucks) && (count($trucks) > 0))
                                                    @foreach($trucks as $truck)
                                                        <option value="{{ $truck->id }}" {{ (old('truck_id') == $truck->id || $params[2]['paramValue'] == $truck->id) ? 'selected' : '' }}>{{ $truck->reg_number }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('truck_id')))
                                                <p style="color: red;" >{{$errors->first('truck_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('service_id')) ? 'has-error' : '' }}">
                                            <label for="service_id" class="control-label">Service/Expense : </label>
                                            <select class="form-control select2" name="service_id" id="service_id" style="width: 100%" tabindex="5">
                                                <option value="">Select service</option>
                                                @if(!empty($services) && (count($services) > 0))
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" {{ (old('service_id') == $service->id || $params[3]['paramValue'] == $service->id) ? 'selected' : '' }}>{{ $service->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('service_id')))
                                                <p style="color: red;" >{{$errors->first('service_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('no_of_records')) ? 'has-error' : '' }}">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            <input type="text" class="form-control" name="no_of_records number_only" id="no_of_records" value="{{ !empty(old('no_of_records')) ? old('no_of_records') : $noOfRecords }}" tabindex="6">
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
                                            <th style="width: 15%;">Date</th>
                                            <th style="width: 20%;">Truck</th>
                                            <th style="width: 20%;">Supplier</th>
                                            <th style="width: 15%;">Service</th>
                                            <th style="width: 15%;">Amount</th>
                                            <th style="width: 10%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($expenses))
                                            @foreach($expenses as $index => $expense)
                                                <tr>
                                                    <td>{{ $index + $expenses->firstItem() }}</td>
                                                    <td>{{ Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                                                    <td>{{ $expense->truck->reg_number }}</td>
                                                    <td>{{ $expense->transaction->creditAccount->account_name }}</td>
                                                    <td>{{ $expense->service->name }}</td>
                                                    <td>{{ $expense->bill_amount }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('expenses.show', ['id' => $expense->id]) }}">
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
                                @if(!empty($expenses))
                                    <div>
                                        Showing {{ $expenses->firstItem(). " - ". $expenses->lastItem(). " of ". $expenses->total() }}<br>
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $expenses->appends(Request::all())->links() }}
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