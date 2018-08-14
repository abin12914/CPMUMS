@extends('layouts.app')
@section('title', 'Sale Invoice')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sales<small>Invoice</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('sale.index') }}"> Sale</a></li>
            <li class="active"> Invoice</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-12">
                <h3>
                    <i class="fa fa-globe"></i> {{ $sale->branch->name }}
                </h3>
                <h4>
                    {{ $sale->branch->address }}
                    <small class="pull-right">
                        GSTIN : {{ $sale->branch->gstin }}
                    </small>
                </h4>
                <h4>
                    <small>
                        Phone : {{ $sale->branch->primary_phone }} {{ $sale->branch->secondary_phone ? ', '. $sale->branch->secondary_phone : '' }}
                    </small>
                    <small class="pull-right">
                        Coposite Tax Dealer
                    </small>
                </h4>
                <h2 class="page-header"></h2>
                <h2 class="page-header text-center">
                    Bill of supply
                </h2>
            </div>
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-xs-12 col-md-12 col-lg-12">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 invoice-col">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td style="width: 40%;">Serial Number</td>
                                <td style="width: 60%;">:&emsp;<strong>{{ $sale->id }}</strong></td>
                            </tr>
                            <tr>
                                <td>Date of Issue</td>
                                <td>:&emsp;<strong>{{ $sale->date->format('d-m-Y') }}</strong></td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 invoice-col">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td style="width: 40%;">State</td>
                                <td style="width: 60%;">:&emsp;<strong>Kerala</strong></td>
                            </tr>
                            <tr>
                                <td>State Code</td>
                                <td>:&emsp;<strong>32</strong></td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <h2 class="page-header"></h2>
        <div class="row invoice-info">
            <div class="col-xs-12 col-md-12 col-lg-12">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 invoice-col">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Details of Receiver</th>
                                <td style="width: 60%;">(Billed to) :</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>:&emsp;<strong>{{ $sale->customer_name }}</strong></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>:&emsp;<strong>{{ $sale->customer_address }}</strong></td>
                            </tr>
                            <tr>
                                <td>GSTIN/UIN</td>
                                <td>:&emsp;<strong>{{ $sale->customer_gstin }}</strong></td>
                            </tr>
                            <tr>
                                <td>State & Code</td>
                                <td>:&emsp;<strong>Kerala - 32</strong></td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.col -->
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6 invoice-col">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Details of Consignee</th>
                                <td style="width: 60%;">(Shipped to) :</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>:&emsp;<strong>{{ $sale->transportation->consignee_name }}</strong></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>:&emsp;<strong>{{ !empty($sale->transportation->consignee_name) ? $sale->transportation->consignee_address : '' }}</strong></td>
                            </tr>
                            <tr>
                                <td>GSTIN/UIN</td>
                                <td>:&emsp;<strong>{{ $sale->transportation->consignee_gstin }}</strong></td>
                            </tr>
                            <tr>
                                <td>State & Code</td>
                                <td>:&emsp;<strong></strong></td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <h2 class="page-header"></h2><br>
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 30%;">Description of Product/Service</th>
                            <th style="width: 10%;">HSN</th>
                            <th style="width: 10%;">UOM</th>
                            <th style="width: 15%;">Quantity</th>
                            <th style="width: 15%;">Rate</th>
                            <th style="width: 15%;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->hsn_code }}</td>
                                <td>{{ $product->uom_code }}</td>
                                <td>{{ $product->saleDetail->quantity }}</td>
                                <td>{{ $product->saleDetail->rate }}</td>
                                <td>{{ ($product->saleDetail->quantity * $product->saleDetail->rate) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-7 col-md-7 col-lg-7">
            <br>
                <p class="text-muted well well-sm no-shadow">
                    <b><u>Terms And Conditions</u></b>
                    <br>&emsp;1. Seller is not responsible for any loss or damage of goods in transport
                    <br>&emsp;1. Dispute if any will be subject to seller court jurisdiction
                </p>
            </div>
            <!-- /.col -->
            <div class="col-xs-5 col-md-5 col-lg-5">
                <br>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Amount:</th>
                            <td>{{ ($sale->total_amount - $sale->discount) }}</td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td>{{ $sale->discount or 0}}</td>
                        </tr>
                        <tr>
                            <th>Value of supply:</th>
                            <td>{{ $sale->total_amount }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-6 text-center no-print">
                    <a>
                        <button type="button" class="btn btn-lg btn-default" onclick="event.preventDefault(); print();">
                            <i class="fa fa-print"></i> Print Invoice
                        </button>
                    </a>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-6 pull-right text-center">
                    <p class="text-muted well well-sm no-shadow">
                        <i>Certify that the particulars given above are true and correct.</i>
                        <br><br><br>
                        <p class="text-center">(Authorized Signatory)</p>
                    </p>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {
            //window.print();
        });
    </script>
@endsection