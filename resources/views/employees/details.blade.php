@extends('layouts.app')
@section('title', 'Employee Details')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Employee
            <small>Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('employee.index') }}"> Employee</a></li>
            <li class="active"> Details</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        @if(!empty($employee))
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow">
                                <div class="widget-user-image">
                                    <img class="img-circle" src="{{ $employee->account->image or "/images/accounts/default_account.png" }}" alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">{{ $employee->account->name }}</h3>
                                <h5 class="widget-user-desc">Employee</h5>
                            </div>
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-paperclip margin-r-5"></i> Reference Number
                                            </strong>
                                            <p class="text-muted multi-line">
                                                #{{ $employee->id. "/". $employee->account_id }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-user-o margin-r-5"></i> Name
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $employee->account->name }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-phone margin-r-5"></i> Phone
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $employee->account->phone }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-map-marker margin-r-5"></i> Address
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $employee->account->address or "-" }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-tags margin-r-5"></i> Wage Type
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ !empty($wageTypes) && !empty($wageTypes[$employee->wage_type]) ? $wageTypes[$employee->wage_type] : 'error'}}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-inr margin-r-5"></i> Wage Amount
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $employee->wage_rate }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-book margin-r-5"></i> Account Name
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $employee->account->account_name }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-calculator margin-r-5"></i> Opening Balance
                                            </strong>
                                            <p class="text-muted multi-line">
                                                @if($employee->account->financial_status == 1)
                                                    Creditor - 
                                                @elseif($employee->account->financial_status == 2)
                                                    Debitor - 
                                                @endif
                                                {{ $employee->account->opening_balance }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="clearfix"> </div>
                                    <div class="row">
                                        <div class="col-md-5"></div>
                                        <div class="col-md-2">
                                            <form action="{{ route('employee.edit', $employee->id) }}" method="get" class="form-horizontal">
                                                <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box -->
                        @endif
                    </div>
                    <!-- /.widget-user -->
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection