@extends('layouts.app')
@section('title', 'Account Edit')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Edit
            <small>Account</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('account.index') }}"> Accounts</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(!empty($account) && !empty($account->id))
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
                            <form action="{{route('account.update', $account->id)}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                {{ method_field('PUT') }}
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                <label for="account_name" class="col-md-3 control-label"><b style="color: red;">* </b> Account Name : </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Account Name" value="{{ old('account_name', $account->account_name) }}" tabindex="1" maxlength="100">
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
                                                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Description" style="resize: none;" tabindex="2" maxlength="200">{{ $account->description }}</textarea>
                                                    @endif
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="box-header with-border">
                                                <h3 class="box-title" style="float: left;">Personal Details</h3>
                                            </div>
                                            <div class="form-group">
                                                <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="name" class="form-control alpha_only" id="name" placeholder="Account holder name" value="{{ old('name', $account->name) }}" tabindex="3" maxlength="100">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone" class="col-md-3 control-label"><b style="color: red;">* </b> Phone : </label>
                                                <div class="col-md-9">
                                                    <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone', $account->phone) }}" tabindex="4" maxlength="13">
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
                                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="5" maxlength="200">{{ $account->address }}</textarea>
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
                                                                <option value="{{ $key }}" {{ (old('relation_type', $account->type) == $key) ? 'selected' : '' }}>
                                                                    {{ $relationType }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'relation_type'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="box-header with-border">
                                                <h3 class="box-title" style="float: left;">Financial Details</h3>
                                                    <p>&nbsp&nbsp&nbsp</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="financial_status" class="col-md-3 control-label"><b style="color: red;">* </b> Financial Status : </label>
                                                <div class="col-md-9">
                                                    <select class="form-control select2" name="financial_status" id="financial_status" tabindex="8" style="width: 100%;">
                                                        <option value="0" {{ (old('financial_status', $account->financial_status) == '0') ? 'selected' : '' }}>None (No pending transactions)</option>
                                                        <option value="2" {{ (old('financial_status', $account->financial_status) == '2') ? 'selected' : '' }}>Debitor (Account holder owe to the company)</option>
                                                        <option value="1" {{ (old('financial_status', $account->financial_status) == '1') ? 'selected' : '' }}>Creditor (Company owe to the account holder)</option>
                                                    </select>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'financial_status'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="opening_balance" class="col-md-3 control-label"><b style="color: red;">* </b> Opening Balance : </label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control decimal_number_only" name="opening_balance" id="opening_balance" placeholder="Opening balance" value="{{ old('opening_balance', $account->opening_balance) }}" {{ old('financial_status', $account->opening_balance) == '0' ? 'readonly' : '' }} tabindex="9" maxlength="8">
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
                                            <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="10">Clear</button>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-warning btn-block btn-flat update_button" tabindex="11">Update</button>
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
        @else
            <h1 class="text-red">Selected Account Not Found! <i class="fa fa-question text-blue" title="Selected account may be deleted or not available to you."></i></h1>
        @endif
    </section>
    <!-- /.content -->
</div>
@endsection