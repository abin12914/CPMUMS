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
            <li><a> Accounts</a></li>
            <li class="active"> List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
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
                                        <div class="col-md-3">
                                            <label for="relation_type" class="control-label">Relation : </label>
                                            <select class="form-control select2" name="relation_type" id="relation_type" style="width: 100%" tabindex="1">
                                                <option value="">Select relation type</option>
                                                @if(!empty($relationTypes) && (count($relationTypes) > 0))
                                                    @foreach($relationTypes as $key => $relationType)
                                                        <option value="{{ $key }}" {{ (old('relation_type') == $key || $params['relation'] == $key) ? 'selected' : '' }}>{{ $relationType }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'relation_type'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-3">
                                            <label for="account_id" class="control-label">Account : </label>
                                            {{-- adding account select component --}}
                                            @component('components.selects.accounts', ['selectedAccountId' => $params['id'], 'cashAccountFlag' => false, 'selectName' => 'account_id', 'activeFlag' => !$activeFlag, 'tabindex' => 2])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'account_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label"><b style="color: red;">* </b> Account Type : </label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="checkbox" name="status_flag" id="status_flag" value="true" {{ $activeFlag ? 'checked' : ''}} tabindex="3">
                                                </span>
                                                <label for="status_flag" class="form-control">Include Short Term Credit Customers</label>
                                            </div>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'status_flag'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-3">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            {{-- adding no of records text component --}}
                                            @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 4])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'no_of_records'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="6">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="5"><i class="fa fa-search"></i> Search</button>
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
                            <div class="col-md-12" style="overflow-x: scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 20%;">Account Name</th>
                                            <th style="width: 15%;">Relation</th>
                                            <th style="width: 20%;">Account Holder</th>
                                            <th style="width: 15%;">Phone</th>
                                            <th style="width: 10%;">Opening Credit</th>
                                            <th style="width: 10%;">Opening Debit</th>
                                            <th style="width: 5%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($accounts))
                                            @foreach($accounts as $index => $account)
                                                <tr>
                                                    <td>{{ $index + $accounts->firstItem() }}</td>
                                                    <td>
                                                        {{ $account->account_name }}
                                                        @if($account->status != 1)
                                                            &emsp;<i class="fa fa-clock-o text-orange no-print" title="Short term credit customer"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ (!empty($relationTypes) && !empty($relationTypes[$account->relation])) ? $relationTypes[$account->relation] : "Error!" }}
                                                    </td>
                                                    <td>{{ $account->name }}</td>
                                                    <td>{{ $account->phone }}</td>
                                                    <td>{{ $account->financial_status == 1 ? $account->opening_balance : "-" }}</td>
                                                    <td>{{ $account->financial_status == 2 ? $account->opening_balance : "-" }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('account.show', $account->id) }}">
                                                            <button type="button" class="btn btn-info">Details</button>
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