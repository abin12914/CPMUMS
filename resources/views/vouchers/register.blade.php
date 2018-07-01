@extends('layouts.app')
@section('title', 'Voucher Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Voucher
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Voucher Registration</li>
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
                        <form action="{{route('voucher.store')}}" method="post" class="form-horizontal" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{ !empty($errors->first('voucher_type')) ? 'has-error' : '' }}">
                                                    <label for="voucher_type_debit" class="control-label"><b style="color: red;">* </b> Receipt : </label>
                                                    <div class="input-group" title="Debit">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="voucher_type" class="voucher_type" id="voucher_type_debit" value="1" {{ empty(old('voucher_type')) || old('voucher_type') == '1' ? 'checked' : ''}} tabindex="1">
                                                        </span>
                                                        <label for="voucher_type_debit" class="form-control" tabindex="9">Receipt [Cash Received]</label>
                                                    </div>
                                                    @if(!empty($errors->first('voucher_type')))
                                                        <p style="color: red;" >{{$errors->first('voucher_type')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 {{ !empty($errors->first('voucher_type')) ? 'has-error' : '' }}">
                                                    <label for="voucher_type_credit" class="control-label"><b style="color: red;">* </b> Payment : </label>
                                                    <div class="input-group" title="Credit">
                                                        <span class="input-group-addon">
                                                            <input type="radio" name="voucher_type" class="voucher_type" id="voucher_type_credit" value="2" {{ old('voucher_type') == '2' ? 'checked' : ''}} tabindex="2">
                                                        </span>
                                                        <label for="voucher_type_credit" class="form-control">Payment [Cash Paid]</label>
                                                    </div>
                                                    @if(!empty($errors->first('voucher_type')))
                                                        <p style="color: red;" >{{$errors->first('voucher_type')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{ !empty($errors->first('voucher_type')) ? 'has-error' : '' }}">
                                                    <label for="voucher_type_debit" class="control-label"><b style="color: red;">* </b> <b id="account_label">{{ (empty(old('voucher_type')) || old('voucher_type') == 1) ? "Giver " : "Reciever " }}</b>- Account : </label>
                                                    {{-- adding account select component --}}
                                                    @component('components.selects.accounts', ['selectedAccountId' => old('voucher_account_id'), 'cashAccountFlag' => false, 'selectName' => 'voucher_account_id', 'tabindex' => 5])
                                                    @endcomponent
                                                    @if(!empty($errors->first('voucher_account_id')))
                                                        <p style="color: red;" >{{$errors->first('voucher_account_id')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                    <label for="date" class="control-label"><b style="color: red;">* </b> Date : </label>
                                                    <input type="text" class="form-control decimal_number_only datepicker_reg" name="date" id="date" placeholder="Transaction date" value="{{ old('date') }}" tabindex="4">
                                                    @if(!empty($errors->first('date')))
                                                        <p style="color: red;" >{{$errors->first('date')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                                    <label for="description" class="control-label"><b style="color: red;">* </b>Description : </label>
                                                    @if(!empty(old('description')))
                                                        <textarea class="form-control" name="description" id="description" rows="1" placeholder="Truck Description" style="resize: none;" tabindex="5">{{ old('description') }}</textarea>
                                                    @else
                                                        <textarea class="form-control" name="description" id="description" rows="1" placeholder="Truck Description" style="resize: none;" tabindex="5"></textarea>
                                                    @endif
                                                    @if(!empty($errors->first('description')))
                                                        <p style="color: red;" >{{$errors->first('description')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 {{ !empty($errors->first('date')) ? 'has-error' : '' }}">
                                                    <label for="amount" class="control-label"><b style="color: red;">* </b> Amount : </label>
                                                    <input type="text" class="form-control decimal_number_only" name="amount" id="amount" placeholder="Transaction amount" value="{{ old('amount') }}" maxlength="6" tabindex="6">
                                                    @if(!empty($errors->first('amount')))
                                                        <p style="color: red;" >{{$errors->first('amount')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="11">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="10">Submit</button>
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