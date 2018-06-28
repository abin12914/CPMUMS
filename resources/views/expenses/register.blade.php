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
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="branch_id" class="control-label"><b style="color: red;">* </b> Branch : </label>
                                                    {{-- adding branch select component --}}
                                                    @component('components.selects.branches', ['selectedBranchId' => old('branch_id'), 'selectName' => 'branch_id', 'tabindex' => 3])
                                                    @endcomponent
                                                    @if(!empty($errors->first('branch_id')))
                                                        <p style="color: red;" >{{$errors->first('branch_id')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="date" class="control-label"><b style="color: red;">* </b> Date : </label>
                                                    <input type="text" class="form-control decimal_number_only datepicker_reg" name="date" id="date" placeholder="Transaction date" value="{{ old('date') }}" tabindex="5">
                                                    @if(!empty($errors->first('date')))
                                                        <p style="color: red;" >{{$errors->first('date')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="service_id" class="control-label"><b style="color: red;">* </b> Service : </label>
                                                    {{-- adding services select component --}}
                                                    @component('components.selects.services', ['selectedServiceId' => old('service_id'), 'selectName' => 'service_id', 'tabindex' => 4])
                                                    @endcomponent
                                                    @if(!empty($errors->first('service_id')))
                                                        <p style="color: red;" >{{$errors->first('service_id')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="supplier_account_id" class="control-label"><b style="color: red;">* </b> Supplier : </label>
                                                    {{-- adding account select component --}}
                                                    @component('components.selects.accounts', ['selectedAccountId' => old('supplier_account_id'), 'cashAccountFlag' => true, 'selectName' => 'supplier_account_id', 'tabindex' => 5])
                                                    @endcomponent
                                                    @if(!empty($errors->first('supplier_account_id')))
                                                        <p style="color: red;" >{{$errors->first('supplier_account_id')}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="description" class="control-label"><b style="color: red;">* </b> Description: </label>
                                                    @if(!empty(old('description')))
                                                        <textarea class="form-control" name="description" id="description" rows="1" placeholder="Description" style="resize: none;" tabindex="7">{{ old('description') }}</textarea>
                                                    @else
                                                        <textarea class="form-control" name="description" id="description" rows="1" placeholder="Truck Description" style="resize: none;" tabindex="7"></textarea>
                                                    @endif
                                                    @if(!empty($errors->first('description')))
                                                        <p style="color: red;" >{{$errors->first('description')}}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="bill_amount" class="control-label"><b style="color: red;">* </b> Bill Amount: </label>
                                                    <input type="text" class="form-control decimal_number_only" name="bill_amount" id="bill_amount" placeholder="Bill amount" value="{{ old('bill_amount') }}" maxlength="8" tabindex="8">
                                                    @if(!empty($errors->first('bill_amount')))
                                                        <p style="color: red;" >{{$errors->first('bill_amount')}}</p>
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