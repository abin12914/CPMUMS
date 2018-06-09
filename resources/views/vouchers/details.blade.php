@extends('layouts.app')
@section('title', 'Vouchers & Receipts Details')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Vouchers & Receipts
            <small>Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Vouchers & Receipts Details</li>
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
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        @if(!empty($voucher))
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow">
                                <div class="widget-user-image">
                                    <img class="img-circle" src="/images/public/voucher.png" alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                @if($voucher->transaction_type == 1)
                                    <h3 class="widget-user-username">Receipt</h3>
                                    <h5 class="widget-user-desc">{{ $voucher->transaction->creditAccount->account_name }}</h5>
                                @else
                                    <h3 class="widget-user-username">Voucher</h3>
                                    <h5 class="widget-user-desc">{{ $voucher->transaction->debitAccount->account_name }}</h5>
                                @endif
                            </div>
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-paperclip margin-r-5"></i> Reference Number
                                            </strong>
                                            <p class="text-muted multi-line">
                                                #{{ $voucher->transaction->id }}/{{ $voucher->id }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-calendar margin-r-5"></i> Date
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ Carbon\Carbon::parse($voucher->date)->format('d-m-Y') }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-sort margin-r-5"></i> Transaction Type
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $voucher->transaction_type == 1 ? "Receipt" : "Voucher" }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-inr margin-r-5"></i> {{ $voucher->transaction_type == 1 ? "Receipt" : "Voucher" }} Amount
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $voucher->amount }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-user-o margin-r-5"></i> Debit Account
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $voucher->transaction->debitAccount->account_name }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-user-o margin-r-5"></i> Credit Account
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $voucher->transaction->creditAccount->account_name }}
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
                                            <div class="col-md-6">
                                                <form action="{{ route('vouchers.edit', $voucher->id) }}" method="get" class="form-horizontal">
                                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="post" class="form-horizontal">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="button" class="btn btn-danger btn-block btn-flat delete_button">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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