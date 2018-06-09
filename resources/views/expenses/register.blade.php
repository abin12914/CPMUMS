@extends('layouts.app')
@section('title', 'Expense Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Expense
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Expense Registration</li>
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
                            <h3 class="box-title" style="float: left;">Expense Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div><br>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('expense.store')}}" method="post" class="form-horizontal" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="transaction_type_credit" class="col-md-3 control-label"><b style="color: red;">* </b> Transaction Type : </label>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6 {{ !empty($errors->first('transaction_type')) ? 'has-error' : '' }}">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_credit" value="1" {{ empty(old('transaction_type')) || old('transaction_type') == '1' ? 'checked' : ''}} tabindex="1">
                                                            </span>
                                                            <label for="transaction_type_credit" class="form-control">Credit</label>
                                                        </div>
                                                        @if(!empty($errors->first('transaction_type')))
                                                            <p style="color: red;" >{{$errors->first('transaction_type')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 {{ !empty($errors->first('transaction_type')) ? 'has-error' : '' }}">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <input type="radio" name="transaction_type" class="transaction_type" id="transaction_type_cash" value="2" {{ old('transaction_type') == '2' ? 'checked' : ''}} tabindex="2">
                                                            </span>
                                                            <label for="transaction_type_cash" class="form-control">Cash</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" id="supplier_div">
                                            <label for="supplier_account_id" class="col-md-3 control-label"><b style="color: red;">* </b> Supplier : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('supplier_account_id')) ? 'has-error' : '' }}">
                                                <select class="form-control select2" name="supplier_account_id" id="supplier_account_id" style="width: 100%;" tabindex="3">
                                                    <option value="" {{ empty(old('supplier_account_id')) ? 'selected' : '' }}>Select account</option>
                                                    @if(!empty($accounts))
                                                        @foreach($accounts as $account)
                                                            <option value="{{ $account->id }}" {{ (old('supplier_account_id') == $account->id) ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if(!empty($errors->first('supplier_account_id')))
                                                    <p style="color: red;" >{{$errors->first('supplier_account_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="truck_id" class="col-md-3 control-label"><b style="color: red;">* </b> Truck & Date : </label>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <select class="form-control select2" name="truck_id" id="truck_id" style="width: 100%;" tabindex="4">
                                                            <option value="" {{ empty(old('truck_id')) ? 'selected' : '' }}>Select truck</option>
                                                            @if(!empty($trucks))
                                                                @foreach($trucks as $truck)
                                                                    <option value="{{ $truck->id }}" {{ (old('truck_id') == $truck->id) ? 'selected' : '' }}>{{ $truck->reg_number }} - {{ $truck->truckType->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if(!empty($errors->first('truck_id')))
                                                            <p style="color: red;" >{{$errors->first('truck_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control decimal_number_only datepicker_reg" name="date" id="date" placeholder="Transaction date" value="{{ old('date') }}" tabindex="5">
                                                        @if(!empty($errors->first('date')))
                                                            <p style="color: red;" >{{$errors->first('date')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="service_id" class="col-md-3 control-label"><b style="color: red;">* </b> Service : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('service_id')) ? 'has-error' : '' }}">
                                                <select class="form-control select2" name="service_id" id="service_id" style="width: 100%;" tabindex="6">
                                                    <option value="" {{ empty(old('service_id')) ? 'selected' : '' }}>Select service</option>
                                                    @if(!empty($services))
                                                        @foreach($services as $service)
                                                            <option value="{{ $service->id }}" {{ (old('service_id') == $service->id) ? 'selected' : '' }}>{{ $service->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @if(!empty($errors->first('service_id')))
                                                    <p style="color: red;" >{{$errors->first('service_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="col-md-3 control-label">Description : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('description')) ? 'has-error' : '' }}">
                                                @if(!empty(old('description')))
                                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Truck Description" style="resize: none;" tabindex="7">{{ old('description') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Truck Description" style="resize: none;" tabindex="7"></textarea>
                                                @endif
                                                @if(!empty($errors->first('description')))
                                                    <p style="color: red;" >{{$errors->first('description')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label"><b style="color: red;">* </b> Bill Amount : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('bill_amount')) ? 'has-error' : '' }}">
                                                <input type="text" class="form-control decimal_number_only" name="bill_amount" id="bill_amount" placeholder="Bill amount" value="{{ old('bill_amount') }}" maxlength="8" tabindex="8">
                                                @if(!empty($errors->first('bill_amount')))
                                                    <p style="color: red;" >{{$errors->first('bill_amount')}}</p>
                                                @endif
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
    <script src="/js/registrations/expenseRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection