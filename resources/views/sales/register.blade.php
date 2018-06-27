@extends('layouts.app')
@section('title', 'Sale Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Sale
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Sale Registration</li>
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
        @if (count($errors) > 0)
            <div class="alert alert-danger" id="alert-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                        <form action="{{route('sale.store')}}" method="post" class="form-horizontal" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="branch_id" class="control-label"><b style="color: red;">* </b> Branch : </label>
                                                    {{-- adding branch select component --}}
                                                    @component('components.selects.branches', ['selectedBranchId' => old('branch_id'), 'selectName' => 'branch_id', 'tabindex' => 1])
                                                    @endcomponent
                                                    @if(!empty($errors->first('branch_id')))
                                                        <p style="color: red;" >{{$errors->first('branch_id')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="sale_date" class="control-label"><b style="color: red;">* </b> Sale Date : </label>
                                                    <input type="text" class="form-control decimal_number_only datepicker_reg" name="sale_date" id="sale_date" placeholder="Sale date" value="{{ old('sale_date') }}" tabindex="2">
                                                    @if(!empty($errors->first('sale_date')))
                                                        <p style="color: red;" >{{$errors->first('sale_date')}}</p>
                                                    @endif
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
                                                    @if(!empty($errors->first('sale_type')))
                                                        <p style="color: red;" >{{$errors->first('sale_type')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="sale_type" class="control-label"><b style="color: red;">* </b> Sale Type : </label>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="sale_type" value="2" id="with_out_account_credit_radio" class="sale_type" {{ old('sale_type') == 2 ? "checked" : "" }}>
                                                        </span>
                                                        <label for="with_out_account_credit_radio" class="form-control">Short Term Credit [With Out Account]</label>
                                                    </div>
                                                    @if(!empty($errors->first('sale_type')))
                                                        <p style="color: red;" >{{$errors->first('sale_type')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6" id="customer_with_account_div" style="display :{{ old('sale_type') != 2 ? "block" : "none" }};">
                                                    <label for="customer_account_id" class="control-label"><b style="color: red;">* </b> Customer : </label>
                                                    {{-- adding account select component --}}
                                                    @component('components.selects.accounts', ['selectedAccountId' => old('customer_account_id'), 'cashAccountFlag' => true, 'selectName' => 'customer_account_id', 'tabindex' => 3])
                                                    @endcomponent
                                                    @if(!empty($errors->first('customer_account_id')))
                                                        <p style="color: red;" >{{$errors->first('customer_account_id')}}</p>
                                                    @endif
                                                </div>
                                                <div id="customer_with_out_account_div" style="display :{{ old('sale_type') == 2 ? "block" : "none" }};">
                                                    <div class="col-md-6">
                                                        <label for="name" class="control-label"><b style="color: red;">* </b> Customer Name : </label>
                                                        <input type="text" name="name" class="form-control alpha_only" id="name" placeholder="Customer name" value="{{ old('name') }}" tabindex="3" maxlength="100">
                                                        @if(!empty($errors->first('name')))
                                                            <p style="color: red;" >{{$errors->first('name')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="phone" class="control-label"><b style="color: red;">* </b> Phone : </label>
                                                        <input type="text" class="form-control decimal_number_only" name="phone" id="phone" placeholder="Phone" value="{{ old('phone') }}">
                                                        @if(!empty($errors->first('phone')))
                                                            <p style="color: red;" >{{$errors->first('phone')}}</p>
                                                        @endif
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
                                                        @for($i = 0; $i < 5; $i++)
                                                            <tr id="product__row_{{ $i }}">
                                                                <td>
                                                                    @if(!empty($errors->first('product_id.'. $i)) || !empty($errors->first('sale_quantity.'. $i)) || !empty($errors->first('sale_rate.'. $i)) || !empty($errors->first('sub_bill.'. $i)))
                                                                        {{ $i + 1 }} &nbsp;
                                                                        <i class="fa fa-hand-o-right" style="color: red;" title="Invalid data in this row."></i>
                                                                    @else
                                                                        {{ $i + 1 }}
                                                                    @endif
                                                                </td>
                                                                <td class="{{ !empty($errors->first('product_id'.$i)) ? 'has-error' : '' }}">
                                                                    @component('components.selects.products_custom', ['selectedProductId' => old('product_id.'. $i), 'selectName' => 'product_id[]', 'selectId' => 'product_id_'.$i, 'customClassName' => 'products_combo', 'indexNo' => $i, 'tabindex' => '', 'disabledOption' => (empty(old('product_id.'. ($i-1))) && $i > 0 ? true : false )])
                                                                    @endcomponent
                                                                </td>
                                                                <td class="{{ !empty($errors->first('product_id.'. $i)) ? 'has-error' : '' }}">
                                                                    <input type="text" class="form-control number_only sale_quantity" name="sale_quantity[]" id="sale_quantity_{{ $i }}" placeholder="Quantity" value="{{ old('sale_quantity.'. $i) }}" maxlength="4" {{ empty(old('product_id.'. $i )) ? 'disabled' : '' }}>
                                                                </td>
                                                                <td class="{{ !empty($errors->first('product_id.'. $i)) ? 'has-error' : '' }}">
                                                                    <input type="text" class="form-control decimal_number_only sale_rate" name="sale_rate[]" id="sale_rate_{{ $i }}" placeholder="Sale rate" value="{{ old('sale_rate.'. $i) }}" maxlength="6" {{ empty(old('product_id.'. $i )) ? 'disabled' : '' }}>
                                                                </td>
                                                                <td class="{{ !empty($errors->first('sub_bill.'.$i)) ? 'has-error' : '' }}">
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
                                                                    <i class="fa fa-hand-o-right" style="color: red;" title="Something went wrong. Please retry again."></i>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control decimal_number_only" name="total_amount" id="total_amount" placeholder="Total Amount" value="{{ old('total_amount') }}" readonly>
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
                                                                <input type="text" class="form-control decimal_number_only" name="discount" id="discount" placeholder="Discount" value="{{ !empty(old('discount')) ? old('discount') : 0 }}" maxlength="5">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td>Total Bill Amount</td>
                                                            <td>
                                                                @if(!empty($errors->first('total_bill')))
                                                                    <i class="fa fa-hand-o-right" style="color: red;" title="Something went wrong. Please retry again."></i>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control decimal_number_only" name="total_bill" id="total_bill" placeholder="Total Bill Amount" value="{{ old('total_bill') }}" readonly>
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
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="9">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="10">Submit</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
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