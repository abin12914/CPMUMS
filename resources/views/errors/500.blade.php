@extends('layouts.app')
@section('title', '500-Internal Server Error')
@section('content')
<div class="content-wrapper no-print">
    <section class="content-header">
        <h1>
            500 Error Page
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">500 error</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"> 500</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Houston, we have a problem.</h3>

                <p>
                    {{-- "You don't have the power to upset me" --}}<br>
                    We will work on fixing that right away. Meanwhile,
                    You may return to <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> dashboard</a> or use options from the left side menu.
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
</div>
@endsection