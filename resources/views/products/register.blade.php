@extends('layouts.app')
@section('title', 'Product Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Register
            <small>Product</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('product.index') }}"> Product</a></li>
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
                            <h3 class="box-title" style="float: left;">Product Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('product.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="product_name" class="col-md-3 control-label"><b style="color: red;">* </b> Product Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="product_name" class="form-control" id="product_name" placeholder="Product Name" value="{{ old('product_name') }}" tabindex="1" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'product_name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="hsn_code" class="col-md-3 control-label"><b style="color: red;">* </b> HSN Code : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="hsn_code" class="form-control" id="hsn_code" placeholder="HSN Code" value="{{ old('hsn_code') }}" tabindex="2" maxlength="10">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'hsn_code'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="uom_code" class="col-md-3 control-label"><b style="color: red;">* </b> UOM Code : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="uom_code" class="form-control" id="uom_code" placeholder="Unique Quantity Code" value="{{ old('uom_code') }}" tabindex="3" maxlength="3">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'uom_code'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="col-md-3 control-label"><b style="color: red;">* </b>Description : </label>
                                            <div class="col-md-9">
                                                @if(!empty(old('description')))
                                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="4" maxlength="199">{{ old('description') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="4" maxlength="199"></textarea>
                                                @endif
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="rate" class="col-md-3 control-label"><b style="color: red;">* </b> Rate : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="rate" class="form-control decimal_number_only" id="rate" placeholder="Unit Rate" value="{{ old('rate') }}" tabindex="5" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'rate'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="loading_charge_per_piece" class="col-md-3 control-label"><b style="color: red;">* </b> Loading Charge/Piece : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="loading_charge_per_piece" class="form-control" id="loading_charge_per_piece" placeholder="Loading Charge Per Piece" value="{{ old('loading_charge_per_piece') }}" tabindex="6" maxlength="4">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'loading_charge_per_piece'])
                                                @endcomponent
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="8">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="7">Submit</button>
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