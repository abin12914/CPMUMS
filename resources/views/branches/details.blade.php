@extends('layouts.app')
@section('title', 'Branch Details')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Branch
            <small>Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('branch.index') }}"> Branch</a></li>
            <li class="active"> Details</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        @if(!empty($branch))
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow">
                                <div class="widget-user-image">
                                    <i class="fa fa-5x fa-building-o" style="float: left;"></i>
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">{{ $branch->name }}</h3>
                                <h5 class="widget-user-desc">
                                    {{ $branch->place }}
                                </h5>
                            </div>
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-paperclip margin-r-5"></i> Reference Number
                                            </strong>
                                            <p class="text-muted multi-line">
                                                #{{ $branch->id }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-building-o margin-r-5"></i> Branch Name
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $branch->name }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-map-marker margin-r-5"></i> Place
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $branch->place }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-file-text-o margin-r-5"></i> Address
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $branch->address or "-" }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="clearfix"> </div>
                                    <div class="row no-print">
                                        <div class="col-md-5"></div>
                                        <div class="col-md-2">
                                            <form action="{{ route('branch.edit', $branch->id) }}" method="get" class="form-horizontal">
                                                <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection