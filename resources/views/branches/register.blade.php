@extends('layouts.app')
@section('title', 'Branch Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Branch
            <small>Registartion</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Branch Registration</li>
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
                            <h3 class="box-title" style="float: left;">Branch Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('branch.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="branch_name" class="col-md-3 control-label"><b style="color: red;">* </b> Branch Name : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('branch_name')) ? 'has-error' : '' }}">
                                                <input type="text" name="branch_name" class="form-control" id="branch_name" placeholder="Branch Name" value="{{ old('branch_name') }}" tabindex="1" maxlength="100">
                                                @if(!empty($errors->first('branch_name')))
                                                    <p style="color: red;" >{{$errors->first('branch_name')}}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="place" class="col-md-3 control-label">Place : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('place')) ? 'has-error' : '' }}">
                                                @if(!empty(old('place')))
                                                    <textarea class="form-control" name="place" id="place" rows="3" placeholder="Place" style="resize: none;" tabindex="2" maxlength="200">{{ old('place') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="place" id="place" rows="3" placeholder="Description" style="resize: none;" tabindex="2" maxlength="200"></textarea>
                                                @endif
                                                @if(!empty($errors->first('place')))
                                                    <p style="color: red;" >{{$errors->first('place')}}</p>
                                                @endif
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <label for="address" class="col-md-3 control-label">Address : </label>
                                            <div class="col-md-9 {{ !empty($errors->first('address')) ? 'has-error' : '' }}">
                                                @if(!empty(old('address')))
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="5" maxlength="200">{{ old('address') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="5" maxlength="200"></textarea>
                                                @endif
                                                @if(!empty($errors->first('address')))
                                                    <p style="color: red;" >{{$errors->first('address')}}</p>
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