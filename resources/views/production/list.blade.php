@extends('layouts.app')
@section('title', 'Production List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Production
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Production</a></li>
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
                        <form action="{{ route('production.index') }}" method="get" class="form-horizontal" autocomplete="off">
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
                                            <label for="product_id" class="control-label">Product : </label>
                                            {{-- adding product select component --}}
                                            @component('components.selects.products', ['selectedProductId' => $params['product_id']['paramValue'], 'selectName' => 'product_id', 'tabindex' => 4])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'product_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="employee_id" class="control-label">Employee : </label>
                                            {{-- adding employee select component --}}
                                            @component('components.selects.employees', ['selectedEmployeeId' => $params['employee_id']['paramValue'],  'selectName' => 'employee_id', 'tabindex' => 5])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'employee_id'])
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
                                            <th style="width: 10%;">Date</th>
                                            <th style="width: 15%;">Branch</th>
                                            <th style="width: 15%;">Employee</th>
                                            <th style="width: 15%;">Product</th>
                                            <th style="width: 15%;">No of Moulds</th>
                                            <th style="width: 15%;">No of Pieces</th>
                                            <th style="width: 5%;" class="no-print"></th>
                                            <th style="width: 5%;" class="no-print"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($productionRecords))
                                            @foreach($productionRecords as $index => $productionRecord)
                                                <tr>
                                                    <td>{{ $index + $productionRecords->firstItem() }}</td>
                                                    <td>{{ $productionRecord->date->format('d-m-Y') }}</td>
                                                    <td>{{ $productionRecord->branch->name }}</td>
                                                    <td>{{ $productionRecord->employee->account->account_name }}</td>
                                                    <td>{{ $productionRecord->product->name }}</td>
                                                    <td>{{ $productionRecord->mould_quantity }}</td>
                                                    <td>{{ $productionRecord->piece_quantity }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('production.edit', ['id' => $productionRecord->id]) }}" style="float: left;">
                                                            <button type="button" class="btn btn-warning">Edit</button>
                                                        </a>
                                                    </td>
                                                    <td class="no-print">
                                                        <form action="{{ route('production.destroy', $productionRecord->id) }}" method="post" class="form-horizontal">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}
                                                            <button type="button" class="btn btn-danger delete_button">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if(Request::get('page') == $productionRecords->lastPage() || $productionRecords->lastPage() == 1)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-red"><b>Total</b></td>
                                                    <td class="text-red"><b>{{ $noOfMoulds }}</b></td>
                                                    <td class="text-red"><b>{{ $noOfPieces }}</b></td>
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
                                @if(!empty($productionRecords))
                                    <div>
                                        Showing {{ $productionRecords->firstItem(). " - ". $productionRecords->lastItem(). " of ". $productionRecords->total() }}<br>
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $productionRecords->appends(Request::all())->links() }}
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