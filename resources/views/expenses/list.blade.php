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
                                        <div class="col-md-4">
                                            <label for="from_date" class="control-label">From Date : </label>
                                            <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="{{ !empty(old('from_date')) ? old('from_date') : $params['from_date']['paramValue'] }}" tabindex="1">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'from_date'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="to_date" class="control-label">To Date : </label>
                                            <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="{{ !empty(old('to_date')) ? old('to_date') : $params['to_date']['paramValue'] }}" tabindex="2">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'to_date'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="branch_id" class="control-label">Branch : </label>
                                            {{-- adding branch select component --}}
                                            @component('components.selects.branches', ['selectedBranchId' => $params['branch_id']['paramValue'], 'selectName' => 'branch_id', 'tabindex' => 3])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'branch_id'])
                                            @endcomponent
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label for="service_id" class="control-label">Service/Expense : </label>
                                            {{-- adding services select component --}}
                                            @component('components.selects.services', ['selectedServiceId' => $params['service_id']['paramValue'], 'selectName' => 'service_id', 'tabindex' => 4])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'service_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="supplier_account_id" class="control-label">Supplier : </label>
                                            {{-- adding account select component --}}
                                            @component('components.selects.accounts', ['selectedAccountId' => $params['supplier_account_id']['paramValue'], 'cashAccountFlag' => true, 'selectName' => 'supplier_account_id', 'activeFlag' => true, 'tabindex' => 5])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'supplier_account_id'])
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
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="8">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="7"><i class="fa fa-search"></i> Search</button>
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
                            <div class="col-md-12" style="overflow-x:scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 15%;">Date</th>
                                            <th style="width: 20%;">Branch</th>
                                            <th style="width: 15%;">Service</th>
                                            <th style="width: 20%;">Supplier</th>
                                            <th style="width: 15%;">Amount</th>
                                            <th style="width: 5%;" class="no-print">Edit</th>
                                            <th style="width: 5%;" class="no-print">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($expenses))
                                            @foreach($expenses as $index => $expense)
                                                <tr>
                                                    <td>{{ $index + $expenses->firstItem() }}</td>
                                                    <td>{{ $expense->date->format('d-m-Y') }}</td>
                                                    <td>{{ $expense->branch->name }}</td>
                                                    <td>{{ $expense->transaction->creditAccount->account_name }}</td>
                                                    <td>{{ $expense->service->name }}</td>
                                                    <td>{{ $expense->bill_amount }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('expense.edit', ['id' => $expense->id]) }}" style="float: left;">
                                                            <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
                                                        </a>
                                                    </td>
                                                    <td class="no-print">
                                                        <form action="{{ route('expense.destroy', $expense->id) }}" method="post" class="form-horizontal">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}
                                                            <button type="button" class="btn btn-danger delete_button"><i class="fa fa-trash"></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if(Request::get('page') == $expenses->lastPage() || $expenses->lastPage() == 1)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-red"><b>Total Amount</b></td>
                                                    <td></td>
                                                    <td class="text-red"><b>{{ $totalExpense }}</b></td>
                                                    <td class="no-print"></td>
                                                    <td class="no-print"></td>
                                                </tr>
                                            @endif
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