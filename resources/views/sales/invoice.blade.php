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
        <div class="row">
            <div class="col-md-12">
                <h5 class="text-center">Bill of supply</h5>
                <table class="table table-bordered" style="margin-bottom: 0px;">
                    <tbody>
                        <tr>
                            <td>
                                <table class="table border-top-only-table" style="margin-bottom: 0px;">
                                    <tbody>
                                        <tr>
                                            <td style="width: 60%;">
                                                <table class="table border-top-only-table" style="width: 100%; margin-bottom: 0px;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-top: none !important;">
                                                                <b>{{ $sale->branch->name }}</b><br>
                                                                {{ $sale->branch->level > 0 ? 'Branch : ' : '' }}<b>{{ $sale->branch->place }}<br>
                                                                {{ $sale->branch->address }}</b><br><br>
                                                                <b>GSTIN/UIN</b> : {{ $sale->branch->gstin }} &emsp;<b>State</b> : Kerala &emsp;<b>Code</b> : 32
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 100%;">
                                                                <u><i>Buyer</i></u><br><br>
                                                                <b>{{ $sale->customer_name }}<br>
                                                                {{ $sale->customer_address }}</b><br>
                                                                <b>GSTIN/UIN</b> : {{ $sale->customer_gstin }} &emsp;<b>State</b> : Kerala &emsp;<b>Code</b> :32
                                                                <br>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width: 40%;">
                                                <table class="table table-bordered" style="width: 100%; margin-bottom: 0px;">
                                                    <tbody>
                                                        <tr>
                                                            <b>Composite Tax Dealer</b>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 40%;">
                                                                <b>Serial Number</b>
                                                            </td>
                                                            <td style="width: 60%;">
                                                                @if(!empty(config('constants.branchInvoiceCode')[$sale->branch_id]))
                                                                    {{ config('constants.branchInvoiceCode')[$sale->branch_id]. $sale->tax_invoice_number }}
                                                                @else
                                                                    {{ $sale->branch_id. "/". $sale->tax_invoice_number }}
                                                                @endif
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
                                                                <b>Vehicle Number</b>
                                                            </td>
                                                            <td style="width: 60%;">
                                                                {{ $sale->transportation->consignment_vehicle_number }}
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
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Amount</th>
                                            <th></th>
                                            <td>{{ ($sale->total_amount + $sale->discount) }}</td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Discount</th>
                                            <th></th>
                                            <td>{{ $sale->discount or 0}}</td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
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
                                                <i>Certify that the particulars given above are true and correct.</i>
                                                <br><br><br>
                                                <p class="text-center">(Authorized Signatory)</p>
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
                        <i class="fa fa-print"></i> Print Invoice
                    </button>
                </a>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection