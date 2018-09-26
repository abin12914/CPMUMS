@extends('layouts.app')
@section('title', 'Sale Invoice')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sales<small>Estimate</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('sale.index') }}"> Sale</a></li>
            <li class="active"> Estimate</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" style="margin-bottom: 0px;">
                    <tbody>
                        <tr>
                            <td>
                                <table class="table border-top-only-table" style="margin-bottom: 0px;">
                                    <tbody>
                                        <tr>
                                            <h3  class="text-center">Estimate</h3>
                                        </tr>
                                        <tr>
                                            <td style="width: 50%; vertical-align: top;">
                                                <br><br>
                                                <b>Name &emsp;: &emsp;</b>{{ $sale->customer_name }}<br>
                                                <b>Address : &emsp;</b>{{ $sale->customer_address }}<br>
                                                <b>Phone&emsp;: </b>&emsp;{{ $sale->customer_phone }}
                                                <br>
                                            </td>
                                            <td style="width: 50%;">
                                                <table class="table table-bordered" style="width: 100%; margin-bottom: 0px;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width: 40%;">
                                                                <b>Ref. No.</b>
                                                            </td>
                                                            <td style="width: 60%;">
                                                                {{ $sale->branch_id. "/". $sale->id }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 40%;">
                                                                <b>Date</b>
                                                            </td>
                                                            <td style="width: 60%;">
                                                                {{ $sale->date->format('d-m-Y') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 40%;">
                                                                <b>Destination</b>
                                                            </td>
                                                            <td style="width: 60%;">
                                                                {{ $sale->transportation->consignee_address }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 40%;">
                                                                <b>Transportation Charge</b>
                                                            </td>
                                                            <td style="width: 60%;">
                                                                {{ $sale->transportation->consignment_charge }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 40%;">
                                                                Notes
                                                            </td>
                                                            <td style="width: 60%;">
                                                                
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">#</th>
                                            <th style="width: 30%;">Description of Product/Service</th>
                                            <th style="width: 20%;">Quantity</th>
                                            <th style="width: 20%;">Rate</th>
                                            <th style="width: 20%;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->products as $index => $product)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->saleDetail->quantity }}</td>
                                                <td>{{ $product->saleDetail->rate }}</td>
                                                <td>{{ ($product->saleDetail->quantity * $product->saleDetail->rate) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Amount</th>
                                            <th></th>
                                            <td>{{ ($sale->total_amount + $sale->discount) }}</td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Discount</th>
                                            <th></th>
                                            <td>{{ $sale->discount or 0}}</td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>Value of supply</th>
                                            <th></th>
                                            <td>{{ $sale->total_amount }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table table-bordered" style="margin-bottom: 0px;">
                                    <tr>
                                        <td style="width: 50%;">
                                            <p class="text-muted well well-sm no-shadow">
                                                <b><u>Terms And Conditions</u></b>
                                                <br>&emsp;1. Seller is not responsible for any loss or damage of goods in transport
                                                <br>&emsp;1. Dispute if any will be subject to seller court jurisdiction
                                            </p>
                                        </td>
                                        <td style="width: 50%;"><br>
                                            <p class="text-muted well well-sm no-shadow">
                                                <br><br><br>
                                                <p class="text-center">Thank You..</p>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row no-print">
            <div class="col-md-12">
                <a>
                    <button type="button" class="btn btn-lg btn-default" onclick="event.preventDefault(); print();">
                        <i class="fa fa-print"></i> Print
                    </button>
                </a>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection