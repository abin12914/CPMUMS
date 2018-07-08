@extends('layouts.app')
@section('title', 'Sale Edit')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Edit
            <small>Sale</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('sale.index') }}"> Sale</a></li>
            <li class="active"> Edit</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="float: left;">Sale Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div><br>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('sale.update', $sale->id)}}" method="post" class="form-horizontal" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                {{ method_field('PUT') }}
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="branch_id" class="control-label"><b style="color: red;">* </b> Branch : </label>
                                                    {{-- adding branch select component --}}
                                                    @component('components.selects.branches', ['selectedBranchId' => !empty(old('branch_id')) ? old('branch_id') : $sale->branch_id, 'selectName' => 'branch_id', 'tabindex' => 1])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'branch_id'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="sale_date" class="control-label"><b style="color: red;">* </b> Sale Date : </label>
                                                    <input type="text" class="form-control decimal_number_only datepicker" name="sale_date" id="sale_date" placeholder="Sale date" value="{{ !empty(old('sale_date')) ? old('sale_date') : $sale->date->format('d-m-Y') }}" tabindex="2">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'sale_date'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="transportation_location" class="control-label"><b style="color: red;">* </b> Transportation Location : </label>
                                                    <input type="text" class="form-control" name="transportation_location" id="transportation_location" placeholder="Transportation Location" value="{{ !empty(old('transportation_location')) ? old('transportation_location') : $sale->transportation->transportation_location }}" tabindex="3">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'transportation_location'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="transportation_charge" class="control-label"><b style="color: red;">* </b> Transportation Charge : </label>
                                                    <input type="text" class="form-control decimal_number_only" name="transportation_charge" id="transportation_charge" placeholder="Transportation Charge" value="{{ !empty(old('transportation_charge')) ? old('transportation_charge') : $sale->transportation->transportation_charge }}" tabindex="4" maxlength="5">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'transportation_charge'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="sale_type" class="control-label"><b style="color: red;">* </b> Sale Type : </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="sale_type" value="1" id="account_credit_radio" class="sale_type" {{ old('sale_type') != 2 ? "checked" : "" }}>
                                                        </span>
                                                        <label for="account_credit_radio" class="form-control">Cash/Account Credit</label>
                                                    </div>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'sale_type'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="sale_type" class="control-label"><b style="color: red;">* </b> Sale Type : </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="sale_type" value="2" id="with_out_account_credit_radio" class="sale_type" {{ old('sale_type') == 2 ? "checked" : "" }}>
                                                        </span>
                                                        <label for="with_out_account_credit_radio" class="form-control">Short Term Credit [With Out Account]</label>
                                                    </div>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'sale_type'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6" id="customer_with_account_div" style="display :{{ old('sale_type') != 2 ? "block" : "none" }};">
                                                    <label for="customer_account_id" class="control-label"><b style="color: red;">* </b> Customer : </label>
                                                    {{-- adding account select component --}}
                                                    @component('components.selects.accounts', ['selectedAccountId' => !empty(old('customer_account_id')) ? old('customer_account_id') : $sale->transaction->debit_account_id, 'cashAccountFlag' => true, 'selectName' => 'customer_account_id', 'activeFlag' => false, 'tabindex' => 5])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'customer_account_id'])
                                                    @endcomponent
                                                </div>
                                                <div id="customer_with_out_account_div" style="display :{{ old('sale_type') == 2 ? "block" : "none" }};">
                                                    <div class="col-md-6">
                                                        <label for="name" class="control-label"><b style="color: red;">* </b> Customer Name : </label>
                                                        <input type="text" name="name" class="form-control alpha_only" id="name" placeholder="Customer name" value="{{ old('name') }}" tabindex="6" maxlength="100">
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="phone" class="control-label"><b style="color: red;">* </b> Phone : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="phone" id="phone" placeholder="Phone" value="{{ old('phone') }}" tabindex="7">
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'phone'])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br><div class="box-header with-border"></div><br>
                                        <div class="form-group">
                                            <div class="row">
                                                <table>
                                                    <thead>
                                                        <th style="width: 5%;">#</th>
                                                        <th style="width: 35%;">Product</th>
                                                        <th style="width: 20%;">Quantity</th>
                                                        <th style="width: 20%;">Rate</th>
                                                        <th style="width: 20%;">Amount</th>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $loopKey = 0;
                                                        @endphp
                                                        @foreach($sale->products as $key => $product)
                                                            <tr id="product__row_{{ $key }}">
                                                                <td>
                                                                    @if(!empty($errors->first('product_id.'. $key)) || !empty($errors->first('sale_quantity.'. $key)) || !empty($errors->first('sale_rate.'. $key)) || !empty($errors->first('sub_bill.'. $key)))
                                                                        {{ $key + 1 }} &nbsp;
                                                                        <i class="fa fa-hand-o-right" style="color: red;" title="Invalid data in this row."></i>
                                                                    @else
                                                                        {{ $key + 1 }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @component('components.selects.products_custom', ['selectedProductId' => !empty(old('product_id.'. $key)) ? old('product_id.'. $key) : $product->id, 'selectName' => 'product_id[]', 'selectId' => 'product_id_'.$key, 'customClassName' => 'products_combo', 'indexNo' => $key, 'tabindex' => (8 + $key), 'disabledOption' => (!empty(old('product_id.'. ($key-1))) || $key == 0 || !empty($product->id) ? false : true )])
                                                                    @endcomponent
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control number_only sale_quantity" name="sale_quantity[]" id="sale_quantity_{{ $key }}" placeholder="Quantity" value="{{ !empty(old('sale_quantity.'. $key)) ? old('sale_quantity.'. $key) : $product->saleDetail->quantity }}" maxlength="4" {{ empty(old('product_id.'. $key )) && empty($product->saleDetail->quantity) ? 'disabled' : '' }} tabindex="{{ 8 + $key }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control decimal_number_only sale_rate" name="sale_rate[]" id="sale_rate_{{ $key }}" placeholder="Sale rate" value="{{ !empty(old('sale_rate.'. $key)) ? old('sale_rate.'. $key) : $product->saleDetail->rate }}" maxlength="6" {{ empty(old('product_id.'. $key )) && empty($product->saleDetail->rate) ? 'disabled' : '' }} tabindex="{{ 8 + $key }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control decimal_number_only" name="sub_bill[]" id="sub_bill_{{ $key }}" placeholder="Bill value" value="{{ !empty(old('sub_bill.'.$key)) ? old('sub_bill.'.$key) : ($product->saleDetail->quantity * $product->saleDetail->rate) }}" readonly>
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $loopKey++;
                                                            @endphp
                                                        @endforeach
                                                        @for($i = $loopKey; $i < 5; $i++)
                                                            <tr id="product__row_{{ $i }}">
                                                                <td>
                                                                    @if(!empty($errors->first('product_id.'. $i)) || !empty($errors->first('sale_quantity.'. $i)) || !empty($errors->first('sale_rate.'. $i)) || !empty($errors->first('sub_bill.'. $i)))
                                                                        {{ $i + 1 }} &nbsp;
                                                                        <i class="fa fa-hand-o-right" style="color: red;" title="Invalid data in this row."></i>
                                                                    @else
                                                                        {{ $i + 1 }}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @component('components.selects.products_custom', ['selectedProductId' => old('product_id.'. $i), 'selectName' => 'product_id[]', 'selectId' => 'product_id_'.$i, 'customClassName' => 'products_combo', 'indexNo' => $i, 'tabindex' => (8 + $i), 'disabledOption' => (empty(old('product_id.'. ($i-1))) && $i > $loopKey ? true : false )])
                                                                    @endcomponent
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control number_only sale_quantity" name="sale_quantity[]" id="sale_quantity_{{ $i }}" placeholder="Quantity" value="{{ old('sale_quantity.'. $i) }}" maxlength="4" {{ empty(old('product_id.'. $i )) ? 'disabled' : '' }} tabindex="{{ 8 + $i }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control decimal_number_only sale_rate" name="sale_rate[]" id="sale_rate_{{ $i }}" placeholder="Sale rate" value="{{ old('sale_rate.'. $i) }}" maxlength="6" {{ empty(old('product_id.'. $i )) ? 'disabled' : '' }} tabindex="{{ 8 + $i }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control decimal_number_only" name="sub_bill[]" id="sub_bill_{{ $i }}" placeholder="Bill value" value="{{ old('sub_bill.'.$i) }}" readonly>
                                                                </td>
                                                            </tr>
                                                        @endfor
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td>Total</td>
                                                            <td>
                                                                @if(!empty($errors->first('total_amount')))
                                                                    <i class="fa fa-hand-o-right" style="color: red;" title="Something went wrong. Please try again."></i>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control decimal_number_only" name="total_amount" id="total_amount" placeholder="Total Amount" value="{{ !empty(old('total_amount')) ? old('total_amount') : ($sale->total_amount - $sale->discount) }}" readonly>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td>Discount</td>
                                                            <td>
                                                                @if(!empty($errors->first('discount')))
                                                                    &nbsp;<i class="fa fa-hand-o-right" style="color: red;" title="{{ $errors->first('discount') }}"></i>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control decimal_number_only" name="discount" id="discount" placeholder="Discount" value="{{ !empty(old('discount')) ? old('discount') : (!empty($sale->discount) ? $sale->discount : 0) }}" maxlength="6" tabindex="13">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td>Total Bill Amount</td>
                                                            <td>
                                                                @if(!empty($errors->first('total_bill')))
                                                                    <i class="fa fa-hand-o-right" style="color: red;" title="Something went wrong. Please try again."></i>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control decimal_number_only" name="total_bill" id="total_bill" placeholder="Total Bill Amount" value="{{ !empty(old('total_bill')) ? old('total_bill') : $sale->total_amount }}" readonly>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="15">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-warning btn-block btn-flat update_button" tabindex="14">Update</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                                <div class="row text-center">
                                    {{-- adding error_message p tag component --}}
                                    @component('components.paragraph.error_message', ['fieldName' => 'calculations'])
                                    @endcomponent
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box primary -->
                </div>
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/registrations/saleRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection