@extends('layouts.app')
@section('title', 'Branch List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Branch
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Branch</a></li>
            <li class="active"> List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    {{-- page header for printers --}}
                    @include('sections.print-head')
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 15%;">Branch Name</th>
                                            <th style="width: 10%;">Place</th>
                                            <th style="width: 10%;">Level</th>
                                            <th style="width: 25%;">Address</th>
                                            <th style="width: 10%;">Phone/s</th>
                                            <th style="width: 15%;">GSTIN</th>
                                            <th style="width: 10%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($branches))
                                            @foreach($branches as $index => $branch)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $branch->name }}</td>
                                                    <td>{{ $branch->place }}</td>
                                                    <td>{{ $branch->level == 0 ? 'H.O.' : 'Branch' }}</td>
                                                    <td>{{ $branch->address }}</td>
                                                    <td>{{ $branch->primary_phone. ($branch->secondary_phone ? ', '.$branch->secondary_phone : '') }}</td>
                                                    <td>{{ $branch->gstin }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('branch.edit', $branch->id) }}">
                                                            <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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