@extends('layouts.app')
@section('title', 'Purchase Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Register
            <small>Purchase</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('purchase.index') }}"> Purchase</a></li>
            <li class="active"> Registration</li>
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
                            <h3 class="box-title" style="float: left;">Purchase Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div><br>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('purchase.store')}}" method="post" class="form-horizontal" autocomplete="off">
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
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'branch_id'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="purchase_date" class="control-label"><b style="color: red;">* </b> Purchase Date : </label>
                                                    <input type="text" class="form-control decimal_number_only datepicker_reg" name="purchase_date" id="purchase_date" placeholder="Purchase date" value="{{ old('purchase_date') }}" tabindex="2">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'purchase_date'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="supplier_account_id" class="control-label"><b style="color: red;">* </b> Supplier : </label>
                                                    {{-- adding account select component --}}
                                                    @component('components.selects.accounts', ['selectedAccountId' => old('supplier_account_id'), 'cashAccountFlag' => true, 'selectName' => 'supplier_account_id', 'activeFlag' => false, 'tabindex' => 3])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'supplier_account_id'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="material_id" class="control-label"><b style="color: red;">* </b> Material : </label>
                                                    {{-- adding material select component --}}
                                                    @component('components.selects.materials', ['selectedMaterialId' => old('material_id'), 'selectName' => 'material_id', 'tabindex' => 4])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'material_id'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="purchase_quantity" class="control-label"><b style="color: red;">* </b> Quantity : </label>
                                                    <input type="text" class="form-control decimal_number_only" name="purchase_quantity" id="purchase_quantity" placeholder="Quantity" value="{{ old('purchase_quantity') }}" tabindex="5">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'purchase_quantity'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="purchase_rate" class="control-label"><b style="color: red;">* </b> Unit Rate : </label>
                                                    <input type="text" class="form-control decimal_number_only" name="purchase_rate" id="purchase_rate" placeholder="Purchase rate" value="{{ old('purchase_rate') }}" tabindex="6">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'purchase_rate'])
                                                    @endcomponent
                                                </div>
                                                <input type="hidden" class="form-control decimal_number_only" name="purchase_bill" id="purchase_bill"  value="{{ old('purchase_bill') }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="purchase_discount" class="control-label"><b style="color: red;">* </b> Discount : </label>
                                                    <input type="text" class="form-control decimal_number_only" name="purchase_discount" id="purchase_discount" placeholder="Purchase discount" value="{{ !empty(old('purchase_discount')) ? old('purchase_discount') : 0 }}" tabindex="7">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'purchase_discount'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="purchase_total_bill" class="control-label"><b style="color: red;">* </b> Total Bill : </label>
                                                    <input type="text" class="form-control decimal_number_only" name="purchase_total_bill" id="purchase_total_bill" placeholder="Total purchase amount" value="{{ old('purchase_total_bill') }}" readonly tabindex="-1">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'purchase_total_bill'])
                                                    @endcomponent
                                                </div>
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
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="8">Submit</button>
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
    <script src="/js/registrations/purchaseRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection