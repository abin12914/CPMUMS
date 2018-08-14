@extends('layouts.app')
@section('title', 'Account Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Register
            <small>Account</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('account.index') }}"> Accounts</a></li>
            <li class="active">Registration</li>
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
                            <h3 class="box-title" style="float: left;">Account Details</h3>
                                <p>&nbsp&nbsp&nbsp(Fields marked with <b style="color: red;">* </b>are mandatory.)</p>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('account.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="account_name" class="col-md-3 control-label"><b style="color: red;">* </b> Account Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name') }}" tabindex="1" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'account_name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="col-md-3 control-label">Description : </label>
                                            <div class="col-md-9">
                                                @if(!empty(old('description')))
                                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2" maxlength="200">{{ old('description') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2" maxlength="200"></textarea>
                                                @endif
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                                @endcomponent
                                            </div>
                                        </div><br>
                                        <div class="box-header with-border">
                                            <h3 class="box-title" style="float: left;">Personal Details</h3>
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="name" class="form-control alpha_only" id="name" placeholder="Account holder name" value="{{ old('name') }}" tabindex="3" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone" class="col-md-3 control-label"><b style="color: red;">* </b> Phone : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="4" minlength="10" maxlength="13">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'phone'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-md-3 control-label">Address : </label>
                                            <div class="col-md-9">
                                                @if(!empty(old('address')))
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="5" maxlength="200">{{ old('address') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="5" maxlength="200"></textarea>
                                                @endif
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'address'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="image_file" class="col-md-3 control-label">Image : </label>
                                            <div class="col-md-9">
                                                <input type="file" name="image_file" class="form-control" id="image_file" value="{{ old('image_file') }}" tabindex="6" accept="image/*">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'image_file'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="relation_type" class="col-md-3 control-label"><b style="color: red;">* </b> Primary Relation : </label>
                                            <div class="col-md-9">
                                                <select class="form-control select2" name="relation_type" id="relation_type" tabindex="7" style="width: 100%;">
                                                    <option value="" {{ empty(old('relation_type')) ? 'selected' : '' }}>Select primary relation type</option>
                                                    @if(!empty($relationTypes))
                                                        @foreach($relationTypes as $key => $relationType)
                                                            <option value="{{ $key }}" {{ (old('relation_type') == $key) ? 'selected' : '' }}>
                                                                {{ $relationType }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'relation_type'])
                                                @endcomponent
                                            </div>
                                        </div><br>
                                        <div class="box-header with-border">
                                            <h3 class="box-title" style="float: left;">Financial Details</h3>
                                                <p>&nbsp&nbsp&nbsp</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="gstin" class="col-md-3 control-label">GSTIN : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="gstin" class="form-control" id="gstin" placeholder="GSTIN [Not mandatory but prefered]" value="{{ old('gstin') }}" tabindex="8" minlength="15" maxlength="15">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'gstin'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="financial_status" class="col-md-3 control-label"><b style="color: red;">* </b> Financial Status : </label>
                                            <div class="col-md-9">
                                                {{-- adding financial_status select component --}}
                                                @component('components.selects.financial_status', ['tabindex' => 9])
                                                @endcomponent
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'financial_status'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="opening_balance" class="col-md-3 control-label"><b style="color: red;">* </b> Opening Balance : </label>
                                            <div class="col-md-9">
                                                {{-- adding opening_balance text component --}}
                                                @component('components.texts.opening_balance', ['tabindex' => 10])
                                                @endcomponent
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'opening_balance'])
                                                @endcomponent
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="12">Clear</button>
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
