@extends('layouts.app')
@section('title', 'Employee Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Employee
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Employee Registration</li>
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
                            <h3 class="box-title" style="float: left;">Employee Details</h3>
                            <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('employee.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                        <div class="col-md-9 {{ !empty($errors->first('name')) ? 'has-error' : '' }}">
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" tabindex="1" maxlength="100">
                                            @if(!empty($errors->first('name')))
                                                <p style="color: red;" >{{$errors->first('name')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="col-md-3 control-label"><b style="color: red;">* </b> Phone : </label>
                                        <div class="col-md-9 {{ !empty($errors->first('phone')) ? 'has-error' : '' }}">
                                            <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="2" maxlength="13">
                                            @if(!empty($errors->first('phone')))
                                                <p style="color: red;" >{{$errors->first('phone')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class="col-md-3 control-label">Address : </label>
                                        <div class="col-md-9 {{ !empty($errors->first('address')) ? 'has-error' : '' }}">
                                            @if(!empty(old('address')))
                                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3" maxlength="200">{{ old('address') }}</textarea>
                                            @else
                                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="3" maxlength="200"></textarea>
                                            @endif
                                            @if(!empty($errors->first('address')))
                                                <p style="color: red;" >{{$errors->first('address')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image_file" class="col-md-3 control-label">Image : </label>
                                        <div class="col-md-9 {{ !empty($errors->first('image_file')) ? 'has-error' : '' }}">
                                            <input type="file" name="image_file" class="form-control" id="image_file" accept="image/*" tabindex="4" value="{{ old('image_file') }}">
                                            @if(!empty($errors->first('image_file')))
                                                <p style="color: red;" >{{$errors->first('image_file')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><b style="color: red;">* </b> Salary Type : </label>
                                        <div class="col-md-9 {{ !empty($errors->first('wage_type')) ? 'has-error' : '' }}">
                                            <select class="form-control select2" name="wage_type" id="wage_type" tabindex="5">
                                                <option value="" {{ empty(old('wage_type')) ? 'selected' : '' }}>Select wage/salary type</option>
                                                @if(!empty($wageTypes))
                                                    @foreach($wageTypes as $key => $wageType)
                                                        <option value="{{ $key }}" {{ old('wage_type') == $key ? 'selected' : '' }}>{{ $wageType }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('wage_type')))
                                                <p style="color: red;" >{{$errors->first('wage_type')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="wage" class="col-md-3 control-label"><b style="color: red;">* </b> Salary/Wage/Bata : </label>
                                        <div class="col-md-9 {{ !empty($errors->first('wage')) ? 'has-error' : '' }}">
                                            <input type="text" name="wage" class="form-control decimal_number_only" id="wage" placeholder="Monthly salary / Daily wage / Trip bata percentage" value="{{ old('wage') }}" tabindex="6">
                                            @if(!empty($errors->first('wage')))
                                                <p style="color: red;" >{{$errors->first('wage')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="account_name" class="col-md-3 control-label"><b style="color: red;">* </b> Account Name : </label>
                                        <div class="col-md-9 {{ !empty($errors->first('account_name')) ? 'has-error' : '' }}">
                                            <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name') }}"  tabindex="7" maxlength="100">
                                            @if(!empty($errors->first('account_name')))
                                                <p style="color: red;" >{{$errors->first('account_name')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><b style="color: red;">* </b> Financial Status: </label>
                                        <div class="col-md-9 {{ !empty($errors->first('financial_status')) ? 'has-error' : '' }}">
                                            <select class="form-control select2" name="financial_status" id="financial_status" tabindex="8">
                                                <option value="" {{ empty(old('financial_status')) ? 'selected' : '' }}>Select Status</option>
                                                <option value="0" {{ old('financial_status') == '0' ? 'selected' : '' }}>None (No pending transactions)</option>
                                                <option value="2" {{ old('financial_status') == '2' ? 'selected' : '' }}>Debitor (Account Holder Owe Company)</option>
                                                <option value="1" {{ old('financial_status') == '1' ? 'selected' : '' }}>Creditor (Company Owe Account Holder)</option>
                                            </select>
                                            @if(!empty($errors->first('financial_status')))
                                                <p style="color: red;" >{{$errors->first('financial_status')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"><b style="color: red;">* </b> Opening Balance: </label>
                                        <div class="col-md-9 {{ !empty($errors->first('opening_balance')) ? 'has-error' : '' }}">
                                            <input type="text" class="form-control decimal_number_only" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ old('opening_balance') }}" tabindex="9" {{ old('financial_status') == '0' ? 'readonly' : '' }} maxlength="8">
                                            @if(!empty($errors->first('opening_balance')))
                                                <p style="color: red;" >{{$errors->first('opening_balance')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="10">Clear</button>
                                    </div>
                                    {{-- <div class="col-md-1"></div> --}}
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="11">Submit</button>
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