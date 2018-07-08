@extends('layouts.app')
@section('title', 'Voucher Edit')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Edit
            <small>Voucher</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('voucher.index') }}"> Voucher</a></li>
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
                            <h3 class="box-title" style="float: left;">Voucher Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div><br>
                        <!-- form start -->
                        <form action="{{route('voucher.update', $voucher->id)}}" method="post" class="form-horizontal" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                {{ method_field('PUT') }}
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="voucher_type_debit" class="control-label"><b style="color: red;">* </b> Receipt : </label>
                                                    <div class="input-group" title="Debit">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="voucher_type" class="voucher_type" id="voucher_type_debit" value="1" {{ (empty(old('voucher_type')) && $voucher->voucher_type == 1) || old('voucher_type') == '1'  ? 'checked' : ''}} tabindex="1">
                                                        </span>
                                                        <label for="voucher_type_debit" class="form-control">Receipt [Cash Received]</label>
                                                    </div>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'voucher_type'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="voucher_type_credit" class="control-label"><b style="color: red;">* </b> Payment : </label>
                                                    <div class="input-group" title="Credit">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="voucher_type" class="voucher_type" id="voucher_type_credit" value="2" {{ (empty(old('voucher_type')) && $voucher->voucher_type == 2) || old('voucher_type') == '2'  ? 'checked' : ''}} tabindex="2">
                                                        </span>
                                                        <label for="voucher_type_credit" class="form-control">Payment [Cash Paid]</label>
                                                    </div>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'voucher_type'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="voucher_type_debit" class="control-label"><b style="color: red;">* </b> <b id="account_label">{{ ((empty(old('voucher_type')) && $voucher->voucher_type == 1) || old('voucher_type') == 1) ? "Giver " : "Reciever " }}</b>- Account : </label>
                                                    {{-- adding account select component --}}
                                                    @component('components.selects.accounts', ['selectedAccountId' => !empty(old('voucher_account_id')) ? old('voucher_account_id') : ($voucher->voucher_type == 1 ? $voucher->transaction->credit_account_id : $voucher->transaction->debit_account_id), 'cashAccountFlag' => false, 'selectName' => 'voucher_account_id', 'activeFlag' => false, 'tabindex' => 3])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'voucher_account_id'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="date" class="control-label"><b style="color: red;">* </b> Date : </label>
                                                    <input type="text" class="form-control decimal_number_only datepicker" name="date" id="date" placeholder="Transaction date" value="{{ !empty(old('date')) ? old('date') : $voucher->date->format('d-m-Y') }}" tabindex="4">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'date'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="description" class="control-label"><b style="color: red;">* </b>Description : </label>
                                                    <textarea class="form-control" name="description" id="description" rows="1" placeholder="Truck Description" style="resize: none;" tabindex="5">{{ !empty(old('description')) ? old('description') : (preg_replace('#\s*\[.+\]\s*#U', '', $voucher->transaction->particulars)) }}</textarea>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="amount" class="control-label"><b style="color: red;">* </b> Amount : </label>
                                                    <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Transaction amount" value="{{ !empty(old('amount')) ? old('amount') : $voucher->amount }}" maxlength="6" tabindex="6">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'amount'])
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
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-warning btn-block btn-flat update_button" tabindex="7">Submit</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/registrations/voucherRecieptRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection