@extends('layouts.app')
@section('title', 'Account List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Account
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Account List</li>
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
        <div class="row  no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('account.index') }}" method="get" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-4 {{ !empty($errors->first('relation_type')) ? 'has-error' : '' }}">
                                            <label for="relation_type" class="control-label">Relation : </label>
                                            <select class="form-control select2" name="relation_type" id="relation_type" style="width: 100%" tabindex="1">
                                                <option value="">Select relation type</option>
                                                @if(!empty($relationTypes) && (count($relationTypes) > 0))
                                                    @foreach($relationTypes as $key => $relationType)
                                                        <option value="{{ $key }}" {{ (old('relation_type') == $key || $params['relation'] == $key) ? 'selected' : '' }}>{{ $relationType }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if(!empty($errors->first('relation_type')))
                                                <p style="color: red;" >{{$errors->first('relation_type')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('account_id')) ? 'has-error' : '' }}">
                                            <label for="account_id" class="control-label">Account : </label>
                                            {{-- adding account select component --}}
                                            @component('components.selects.accounts', ['selectedAccountId' => $params['id'], 'cashAccountFlag' => false, 'selectName' => 'account_id', 'tabindex' => 2])
                                            @endcomponent

                                            @if(!empty($errors->first('account_id')))
                                                <p style="color: red;" >{{$errors->first('account_id')}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 {{ !empty($errors->first('no_of_records')) ? 'has-error' : '' }}">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            {{-- adding no of records text component --}}
                                            @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 3])
                                            @endcomponent
                                            @if(!empty($errors->first('no_of_records')))
                                                <p style="color: red;" >{{$errors->first('no_of_records')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="5">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    {{-- page header for printers --}}
                    @include('sections.print-head')
                    <div class="box-header no-print">
                        @if(!empty($params['relation']) || !empty($params['id']))
                            <b>Filters applied!</b>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12" style="overflow:scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 20%;">Account Name</th>
                                            <th style="width: 15%;">Relation</th>
                                            <th style="width: 20%;">Account Holder</th>
                                            <th style="width: 15%;">Opening Credit</th>
                                            <th style="width: 15%;">Opening Debit</th>
                                            <th style="width: 10%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($accounts))
                                            @foreach($accounts as $index => $account)
                                                <tr>
                                                    <td>{{ $index + $accounts->firstItem() }}</td>
                                                    <td>{{ $account->account_name }}</td>
                                                    @if($account->relation == 0)
                                                        <td>Real/Nominal</td>
                                                    @elseif(!empty($relationTypes))
                                                        <td>
                                                            {{ !empty($relationTypes[$account->relation]) ? $relationTypes[$account->relation] : "Error!" }}
                                                        </td>
                                                    @else
                                                        <td>Error</td>
                                                    @endif
                                                    <td>{{ $account->name }}</td>
                                                    @if($account->financial_status == 1)
                                                        <td>{{ $account->opening_balance }}</td>
                                                        <td></td>
                                                    @elseif($account->financial_status == 2)
                                                        <td></td>
                                                        <td>{{ $account->opening_balance }}</td>
                                                    @else
                                                        <td>-</td>
                                                        <td>-</td>
                                                    @endif
                                                    <td class="no-print">
                                                        <a href="{{ route('account.show', $account->id) }}">
                                                            <button type="button" class="btn btn-default">Details</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($accounts))
                                    <div>
                                        Showing {{ $accounts->firstItem(). " - ". $accounts->lastItem(). " of ". $accounts->total() }}
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $accounts->appends(Request::all())->links() }}
                                    </div>
                                @endif
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