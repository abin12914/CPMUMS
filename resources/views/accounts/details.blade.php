@extends('layouts.app')
@section('title', 'Account Details')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account
            <small>Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('account.index') }}"> Accounts</a></li>
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
                        @if(!empty($account))
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow">
                                <div class="widget-user-image">
                                    <img class="img-circle" src="{{ $account->image or "/images/accounts/default_account.png" }}" alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">{{ $account->account_name }}</h3>
                                <h5 class="widget-user-desc">
                                    {{ (!empty($relationTypes) && !empty($relationTypes[$account->relation])) ? $relationTypes[$account->relation] : "Error" }}
                                </h5>
                            </div>
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-paperclip margin-r-5"></i> Reference Number
                                            </strong>
                                            <p class="text-muted multi-line">
                                                #{{ $account->id }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-book margin-r-5"></i> Account Name
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $account->account_name }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-file-text-o margin-r-5"></i> Description
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $account->description or "-" }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-user-o margin-r-5"></i> Name
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $account->name }}
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
                                                {{ $account->phone or "-" }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-map-marker margin-r-5"></i> Address
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $account->address or "-" }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-link margin-r-5"></i> Relation
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ (!empty($relationTypes) && !empty($relationTypes[$account->relation])) ? $relationTypes[$account->relation] : "Error" }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-calculator margin-r-5"></i> Opening Balance
                                            </strong>
                                            <p class="text-muted multi-line">
                                                @if($account->financial_status == 1)
                                                    Creditor - 
                                                @elseif($account->financial_status == 2)
                                                    Debitor - 
                                                @endif
                                                {{ $account->opening_balance }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-university margin-r-5"></i> GSTIN
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ strtoupper($account->gstin) }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="clearfix"> </div>
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="col-md-{{ $account->relation == 1 ? "6" : "12" }}">
                                                <form action="{{ route('account.edit', $account->id) }}" method="get" class="form-horizontal">
                                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                                </form>
                                            </div>
                                            @if($account->relation == 1)
                                                <div class="col-md-6">
                                                    <a href="{{ route('employee.show', $account->employee->id) }}">
                                                        <button type="button" class="btn btn-info btn-block btn-flat">Employee Details</button>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection
