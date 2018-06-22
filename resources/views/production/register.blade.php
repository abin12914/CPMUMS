@extends('layouts.app')
@section('title', 'Production Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Production
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Production Registration</li>
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
                            <h3 class="box-title" style="float: left;">Production Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div><br>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('production.store')}}" method="post" class="form-horizontal" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group" id="supplier_div">
                                            <label for="branch_id" class="col-md-3 control-label"><b style="color: red;">* </b> Branch : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('branch_id')) ? 'has-error' : '' }}">
                                                {{-- adding branch select component --}}
                                                @component('components.selects.branches', ['selectedBranchId' => '', 'selectName' => 'branch_id', 'tabindex' => 3])
                                                @endcomponent
                                                @if(!empty($errors->first('branch_id')))
                                                    <p style="color: red;" >{{$errors->first('branch_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group" id="supplier_div">
                                            <label for="employee_id" class="col-md-3 control-label"><b style="color: red;">* </b> Employee : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('employee_id')) ? 'has-error' : '' }}">
                                                {{-- adding employee select component --}}
                                                @component('components.selects.employees', ['selectedEmployeeId' => '',  'selectName' => 'employee_id', 'tabindex' => 4])
                                                @endcomponent
                                                @if(!empty($errors->first('employee_id')))
                                                    <p style="color: red;" >{{$errors->first('employee_id')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_id" class="col-md-3 control-label"><b style="color: red;">* </b> Product & Date : </label>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        {{-- adding product select component --}}
                                                        @component('components.selects.products', ['selectedProductId' => '',  'selectName' => 'product_id', 'tabindex' => 5])
                                                        @endcomponent
                                                        @if(!empty($errors->first('product_id')))
                                                            <p style="color: red;" >{{$errors->first('product_id')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control decimal_number_only datepicker_reg" name="date" id="date" placeholder="Date" value="{{ old('date') }}" tabindex="5">
                                                        @if(!empty($errors->first('date')))
                                                            <p style="color: red;" >{{$errors->first('date')}}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="product_id" class="col-md-3 control-label"><b style="color: red;">* </b> No of Mould & Pieces : </label>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control decimal_number_only" name="mould_quantity" id="mould_quantity" placeholder="Bill amount" value="{{ old('mould_quantity') }}" maxlength="8" tabindex="8">
                                                        @if(!empty($errors->first('mould_quantity')))
                                                            <p style="color: red;" >{{$errors->first('mould_quantity')}}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control decimal_number_only" name="piece_quantity" id="piece_quantity" placeholder="Bill amount" value="{{ old('piece_quantity') }}" maxlength="8" tabindex="8">
                                                        @if(!empty($errors->first('piece_quantity')))
                                                            <p style="color: red;" >{{$errors->first('piece_quantity')}}</p>
                                                        @endif
                                                    </div>
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