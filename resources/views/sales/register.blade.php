@extends('layouts.app')
@section('title', 'Sale Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Register
            <small>Sale</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('sale.index') }}"> Sale</a></li>
            <li class="active"> Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                {{-- <div class="col-md-2"></div> --}}
                <div class="col-md-12">
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
                                                <div class="col-md-4">
                                                    <label for="branch_id" class="control-label"><b style="color: red;">* </b> Branch : </label>
                                                    {{-- adding branch select component --}}
                                                    @component('components.selects.branches', ['selectedBranchId' => old('branch_id'), 'selectName' => 'branch_id', 'tabindex' => 1])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'branch_id'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="sale_date" class="control-label"><b style="color: red;">* </b> Sale Date : </label>
                                                    <input type="text" class="form-control decimal_number_only datepicker_reg" name="sale_date" id="sale_date" placeholder="Sale date" value="{{ old('sale_date') }}" tabindex="2">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'sale_date'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="customer_account_id" class="control-label"><b style="color: red;">* </b> Sale To : </label>
                                                    {{-- adding account select component --}}
                                                    @component('components.selects.accounts', ['selectedAccountId' => old('customer_account_id'), 'cashAccountFlag' => true, 'selectName' => 'customer_account_id', 'activeFlag' => false, 'nonAccountFlag' => true, 'tabindex' => 5])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'customer_account_id'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="customer_name" class="control-label"><b style="color: red;">* </b> Billing Name : </label>
                                                            <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Billing name" value="{{ old('customer_name') }}" tabindex="3">
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'customer_name'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="customer_phone" class="control-label"><b style="color: red;">* </b> Billing Phone : </label>
                                                            <input type="text" class="form-control" name="customer_phone" id="customer_phone" placeholder="Billing phone" value="{{ old('customer_phone') }}" tabindex="3">
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'customer_phone'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="customer_address" class="control-label"><b style="color: red;">* </b> Billing Address : </label>
                                                    @if(!empty(old('customer_address')))
                                                        <textarea  class="form-control" name="customer_address" id="customer_address" placeholder="Billing Address" tabindex="3" rows="4" style="resize: none;">{{ old('customer_address') }}</textarea>
                                                    @else
                                                        <textarea  class="form-control" name="customer_address" id="customer_address" placeholder="Billing Address" tabindex="3" rows="4" style="resize: none;"></textarea>
                                                    @endif
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'customer_address'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="customer_gstin" class="control-label"><b style="color: red;">* </b> Billing GSTIN : </label>
                                                            <input type="text" class="form-control" name="customer_gstin" id="customer_gstin" placeholder="Billing GSTIN" value="{{ old('customer_gstin') }}" tabindex="4" maxlength="15" minlength="15">
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'customer_gstin'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="control-label">Add to Tax Invoice : </label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <input type="checkbox" name="tax_invoice_flag" id="tax_invoice_flag" value="1" tabindex="3" {{ !empty(old('tax_invoice_flag')) ? 'checked' : '' }}>
                                                                </span>
                                                                <label for="tax_invoice_flag" class="form-control">Tax Invoice</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="consignee_name" class="control-label"><b style="color: red;">* </b> Consignee Name : </label>
                                                            <input type="text" class="form-control" name="consignee_name" id="consignee_name" placeholder="Consignee name" value="{{ old('consignee_name') }}" tabindex="3">
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'consignee_name'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="consignee_gstin" class="control-label"><b style="color: red;">* </b> Consignee GSTIN : </label>
                                                            <input type="text" class="form-control" name="consignee_gstin" id="consignee_gstin" placeholder="Consignee GSTIN" value="{{ old('consignee_gstin') }}" tabindex="4" maxlength="15" minlength="15">
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'consignee_gstin'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="consignee_address" class="control-label"><b style="color: red;">* </b> Consignment Address : </label>
                                                            @if(!empty(old('consignee_address')))
                                                                <textarea  class="form-control" name="consignee_address" id="consignee_address" placeholder="Consignment Location" tabindex="3" rows="1" style="resize: none;">{{ old('consignee_address') }}</textarea>
                                                            @else
                                                                <textarea  class="form-control" name="consignee_address" id="consignee_address" placeholder="Consignment Location" tabindex="3" rows="1" style="resize: none;"></textarea>
                                                            @endif
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'consignee_address'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="consignment_vehicle_number" class="control-label"><b style="color: red;">* </b> Consignment Vehicle Number : </label>
                                                            <input type="text" class="form-control" name="consignment_vehicle_number" id="consignment_vehicle_number" placeholder="eg: KL-63 AA-1234" value="{{ old('consignment_vehicle_number') }}" tabindex="4" maxlength="15" minlength="5">
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'consignment_vehicle_number'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="consignment_charge" class="control-label"><b style="color: red;">* </b> Consignment Charge : </label>
                                                            <input type="text" class="form-control decimal_number_only" name="consignment_charge" id="consignment_charge" placeholder="Consignment Charge" value="{{ old('consignment_charge') }}" tabindex="4" maxlength="5">
                                                            @component('components.paragraph.error_message', ['fieldName' => 'consignment_charge'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="loading_employee_id" class="control-label"><b style="color: red;">* </b> Loading Employee : </label>
                                                            {{-- adding employee select component --}}
                                                            @component('components.selects.employees', ['selectedEmployeeId' => old('loading_employee_id'),  'selectName' => 'loading_employee_id', 'tabindex' => 4])
                                                            @endcomponent
                                                            {{-- adding error_message p tag component --}}
                                                            @component('components.paragraph.error_message', ['fieldName' => 'loading_employee_id'])
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br><div class="box-header with-border"></div><br>
                                        <div class="form-group">
                                            <div class="row">
                                                <table class="table table-bordered table-hover dataTable">
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
                                                                <td>
                                                                    @component('components.selects.products_custom', ['selectedProductId' => old('product_id.'. $i), 'selectName' => 'product_id[]', 'selectId' => 'product_id_'.$i, 'customClassName' => 'products_combo', 'indexNo' => $i, 'tabindex' => (8 + $i), 'disabledOption' => (empty(old('product_id.'. ($i-1))) && $i > 0 ? true : false )])
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
                                                                <input type="text" class="form-control decimal_number_only" name="discount" id="discount" placeholder="Discount" value="{{ !empty(old('discount')) ? old('discount') : 0 }}" maxlength="6" tabindex="13">
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
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="15">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" id="sale_submit_button" class="btn btn-primary btn-block btn-flat" tabindex="14">Submit</button>
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