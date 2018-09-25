@extends('layouts.app')
@section('title', 'Product List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Product
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Product</a></li>
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
                            <div class="col-md-12" style="overflow-x: scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 20%;">Product Name</th>
                                            <th style="width: 10%;">HSN Code</th>
                                            <th style="width: 10%;">UOM Code</th>
                                            <th style="width: 20%;">Description</th>
                                            <th style="width: 10%;">Rate</th>
                                            <th style="width: 15%;">Loading Charge/Piece</th>
                                            <th style="width: 10%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($products))
                                            @foreach($products as $index => $product)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->hsn_code }}</td>
                                                    <td>{{ $product->uom_code }}</td>
                                                    <td>{{ $product->description }}</td>
                                                    <td>{{ $product->rate }}</td>
                                                    <td>{{ $product->loading_charge_per_piece }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('product.edit', $product->id) }}">
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