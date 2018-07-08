@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('purchase.index') }}" style="color: black;">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-truck"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Purchase</span>
                                <span class="info-box-number">{{ $purchaseCount or 0 }} <small>Records</small></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('production.index') }}" style="color: black;">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="ion ion-ios-gear-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Production</span>
                                <span class="info-box-number">{{ $productionCount or 0 }} <small>Records</small></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('sale.index') }}" style="color: black;">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Sales</span>
                                <span class="info-box-number">{{ $saleCount or 0 }} <small>Records</small></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ route('account.index') }}" style="color: black;">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Accounts</span>
                                <span class="info-box-number">{{ $accountCount or 0 }} <small>Records</small></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </a>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection