@extends('layouts.app')
@section('title', '404-Page Not Found')
@section('content')
<div class="content-wrapper no-print">
    <section class="content-header">
        <h1>
            404-Page Not Found
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">404-Page Not Found</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"> 404</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>

                <p>
                    {{-- "You don't have the power to upset me. Stay calm... We got it covered." --}}
                    We could not find the page you were looking for. Meanwhile, you may 
                    @if(!empty($loggedUser))
                        <a href="{{ route('dashboard') }}">return to dashboard</a> or use options from the left side menu.
                    @else
                        <a href="{{ route('login') }}">login from here</a>
                    @endif
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
</div>
@endsection