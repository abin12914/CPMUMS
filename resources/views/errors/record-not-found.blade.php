@extends('layouts.app')
@section('title', 'Record Not Found')
@section('content')
<div class="content-wrapper no-print">
    <section class="content-header">
        <h1>
            Record Not Found
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Record Not Found</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"><i class="fa fa-database"></i></h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Record not found.</h3>

                <p>
                    We could not find the<b>{{ str_replace(config('settings.model_namespace') , ' ', $exception->getModel()) }}</b> record you were looking for. It might be deleted or you don't have the permission to access it.
                    <br><br>Meanwhile, you may <a href="{{ route('dashboard') }}">return to dashboard</a> or use options from the left side menu.
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
</div>
@endsection