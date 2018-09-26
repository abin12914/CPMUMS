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
            <li><a> Sale</a></li>
            <li class="active"> List</li>
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
                        <form action="{{ route('sale.index') }}" method="get" class="form-horizontal" autocomplete="off">
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
                                            <label for="customer_type" class="control-label">Customer Type : </label>
                                            <select class="form-control select2" name="customer_type" id="customer_type" tabindex="4" style="width: 100%;">
                                                <option value="" {{ empty(old('customer_type')) ? 'selected' : '' }}>Select customer type</option>
                                                <option value="1" {{ (old('relation_type') == 1 || $params['customer_type']['paramValue'] == 1) ? 'selected' : '' }}>Long term credit customer</option>
                                                <option value="2" {{ (old('relation_type') == 2 || $params['customer_type']['paramValue'] == 2) ? 'selected' : '' }}>Short term credit customer</option>
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'customer_type'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="customer_account_id" class="control-label">Customer : </label>
                                            {{-- adding account select component --}}
                                            @component('components.selects.accounts', ['selectedAccountId' => $params['customer_account_id']['paramValue'], 'cashAccountFlag' => true, 'selectName' => 'customer_account_id', 'activeFlag' => false, 'tabindex' => 5])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'customer_account_id'])
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
                                            <th style="width: 15%;">Date & Invoice No.</th>
                                            <th style="width: 15%;">Branch</th>
                                            <th style="width: 15%;">Account</th>
                                            <th style="width: 15%;">Customer Name</th>
                                            <th style="width: 10%;">No Of Products</th>
                                            <th style="width: 15%;">Bill Amount</th>
                                            <th style="width: 5%;" class="no-print">Details</th>
                                            <th style="width: 5%;" class="no-print">Print</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($saleRecords))
                                            @foreach($saleRecords as $index => $saleRecord)
                                                <tr>
                                                    <td>{{ $index + $saleRecords->firstItem() }}</td>
                                                    <td>{{ $saleRecord->date->format('d-m-Y') }}
                                                        @if(!empty($saleRecord->tax_invoice_number))
                                                            /{{ config('constants.branchInvoiceCode')[$saleRecord->branch_id]. $saleRecord->tax_invoice_number }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $saleRecord->branch->name }}</td>
                                                    <td>
                                                        {{ $saleRecord->transaction->debitAccount->account_name }}
                                                        @if($saleRecord->transaction->debitAccount->status != 1)
                                                            &emsp;<i class="fa fa-clock-o text-orange no-print" title="Short term credit customer"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $saleRecord->customer_name }}
                                                    </td>
                                                    <td>{{ count($saleRecord->products) }}</td>
                                                    <td>{{ $saleRecord->total_amount }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('sale.show', ['id' => $saleRecord->id]) }}">
                                                            <button type="button" class="btn btn-info"> Details</button>
                                                        </a>
                                                    </td>
                                                    <td class="no-print">
                                                        @if(!empty($saleRecord->tax_invoice_number) && $saleRecord->tax_invoice_number > 0)
                                                            <a href="{{ route('sale.invoice', ['id' => $saleRecord->id]) }}">
                                                                <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Invoice</button>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('sale.invoice', ['id' => $saleRecord->id]) }}">
                                                                <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Estimate</button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if(Request::get('page') == $saleRecords->lastPage() || $saleRecords->lastPage() == 1)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-red"><b>Total Amount</b></td>
                                                    <td></td>
                                                    <td class="text-red"><b>{{ $totalAmount }}</b></td>
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