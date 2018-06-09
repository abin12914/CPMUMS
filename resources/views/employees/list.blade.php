@extends('layouts.app')
@section('title', 'Employee List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Employee
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Employee List</li>
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
                        <form action="{{ route('employee.index') }}" method="get" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-4 {{ !empty($errors->first('wage_type')) ? 'has-error' : '' }}">
                                            <label for="wage_type" class="control-label">Wage Type : </label>
                                            <select class="form-control select2" name="wage_type" id="wage_type" style="width: 100%" tabindex="1">
                                                <option value="">Select wage type</option>
                                                @if(!empty($wageTypes) && (count($wageTypes) > 0))
                                                    @foreach($wageTypes as $key => $wageType)
                                                        <option value="{{ $key }}" {{ (old('wage_type') == $key || $params['wage_type'] == $key) ? 'selected' : '' }}>{{ $wageType }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('wage_type')))
                                                <p style="color: red;" >{{$errors->first('wage_type')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('employee_id')) ? 'has-error' : '' }}">
                                            <label for="employee_id" class="control-label">Employee Account : </label>
                                            <select class="form-control select2" name="employee_id" id="employee_id" style="width: 100%" tabindex="2">
                                                <option value="">Select account</option>
                                                @if(!empty($employeesCombo) && (count($employeesCombo) > 0))
                                                    @foreach($employeesCombo as $employee)
                                                        <option value="{{ $employee->id }}" {{ (old('employee_id') == $employee->id || $params['id'] == $employee->id) ? 'selected' : '' }}>{{ $employee->account->account_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('employee_id')))
                                                <p style="color: red;" >{{$errors->first('employee_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('no_of_records')) ? 'has-error' : '' }}">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            <input type="text" class="form-control number_only" name="no_of_records" id="no_of_records" value="{{ !empty(old('no_of_records')) ? old('no_of_records') : $noOfRecords }}" tabindex="3">
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
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="4">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="5"><i class="fa fa-search"></i> Search</button>
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
                        @if(!empty($params['wage_type']) || !empty($params['employee_id']))
                            <b>Filters applied!</b>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12" style="overflow:scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 20%;">Employee Name</th>
                                            <th style="width: 20%;">Wage Type</th>
                                            <th style="width: 20%;">Wage</th>
                                            <th style="width: 20%;">Account Name</th>
                                            <th style="width: 15%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($employees))
                                            @foreach($employees as $index => $employee)
                                                <tr>
                                                    <td>{{ $index + $employees->firstItem() }}</td>
                                                    <td>{{ $employee->account->name }}</td>
                                                    @if(!empty($wageTypes))
                                                        <td>
                                                            {{ !empty($wageTypes[$employee->wage_type]) ? $wageTypes[$employee->wage_type] : "Error!" }}
                                                        </td>
                                                    @else
                                                        <td>Error</td>
                                                    @endif
                                                    @if($employee->wage_type == 3)
                                                        <td>{{ $employee->wage }} <b>%</b></td>
                                                    @else
                                                        <td>{{ $employee->wage }}</td>
                                                    @endif
                                                    <td>{{ $employee->account->account_name }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('employees.show', ['id' => $employee->id]) }}">
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
                                @if(!empty($employees))
                                    <div>
                                        Showing {{ $employees->firstItem(). " - ". $employees->lastItem(). " of ". $employees->total() }}
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $employees->appends(Request::all())->links() }}
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