@extends('layouts.app')
@section('title', 'Credit List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Credit
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('user-dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Credit List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
            <div class="alert {{ Session::get('alert-class', 'alert-info') }}" id="alert-message">
                <h4>
                  {!! Session::get('message') !!}
                  <?php session()->forget('message'); ?>
                </h4>
            </div>
        @endif
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter result</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('credit-list') }}" method="get" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-12 {{ !empty($errors->first('relation')) ? 'has-error' : '' }}">
                                            <label for="relation" class="control-label">Relation : </label>
                                            <select class="form-control" name="relation" id="relation" tabindex="3" style="width: 100%">
                                                <option value="" {{ (empty($relation) || (empty(old('relation')) && $relation == 0)) ? 'selected' : '' }}>Select transaction type</option>
                                                <option value="employee" {{ (!empty($relation) && ((old('relation') == 'employee' ) || $relation == 'employee')) ? 'selected' : '' }}>Employee</option>
                                                <option value="supplier" {{ (!empty($relation) && (old('relation') == 'supplier' || $relation == 'supplier')) ? 'selected' : '' }}>Supplier</option>
                                                <option value="customer" {{ (!empty($relation) && (old('relation') == 'customer' || $relation == 'customer')) ? 'selected' : '' }}>Customer</option>
                                                <option value="contractor" {{ (!empty($relation) && (old('relation') == 'contractor' || $relation == 'contractor')) ? 'selected' : '' }}>Contractor</option>
                                                <option value="owner" {{ (!empty($relation) && (old('relation') == 'owner' || $relation == 'owner')) ? 'selected' : '' }}>Owner</option>
                                                <option value="general" {{ (!empty($relation) && (old('relation') == 'general' || $relation == 'general')) ? 'selected' : '' }}>General</option>
                                                <option value="operator" {{ (!empty($relation) && (old('relation') == 'operator' || $relation == 'operator')) ? 'selected' : '' }}>Operator</option>
                                            </select>
                                            @if(!empty($errors->first('relation')))
                                                <p style="color: red;" >{{$errors->first('relation')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row no-print">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="10">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div><br>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 2%;">#</th>
                                            <th style="width: 28%;">Account Name</th>
                                            <th style="width: 30%;">Account Holder/Head</th>
                                            <th style="width: 20%;">Debit</th>
                                            <th style="width: 20%;">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($accounts))
                                            @foreach($accounts as $index => $account)
                                                <?php
                                                if(empty($creditAmount[$account->id])) {
                                                    $creditAmount[$account->id] = 0;
                                                }
                                                if(empty($debitAmount[$account->id])) {
                                                    $debitAmount[$account->id] = 0;
                                                }
                                                ?>
                                                <tr>
                                                    <td>{{ ($index+1) }}</td>
                                                    <td>{{ $account->account_name }}</td>
                                                    <td>{{ $account->accountDetail->name }}</td>
                                                    @if($creditAmount[$account->id] > $debitAmount[$account->id])
                                                        <td></td>
                                                        <td>{{ round(($creditAmount[$account->id] - $debitAmount[$account->id]), 2) }}</td>
                                                    @elseif($debitAmount[$account->id] > $creditAmount[$account->id])
                                                        <td>{{ round(($debitAmount[$account->id] - $creditAmount[$account->id]), 2) }}</td>
                                                        <td></td>
                                                    @else
                                                        <td>-</td>
                                                        <td>-</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>#</td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ !empty($totalDebitAmount) ? round($totalDebitAmount, 2) : 0 }}</td>
                                            <td>{{ !empty($totalCreditAmount) ? round($totalCreditAmount, 2) : 0 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row no-print">
                            <div class="col-md-12">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="pull-right">
                                        {{-- @if(!empty($accounts))
                                            {{ $accounts->appends(Request::all())->links() }}
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.boxy -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
