@extends('layouts.app')
@section('title', 'Internal Error')
@section('content')
<div class="content-wrapper no-print">
    <section class="content-header">
        <h1>
            Internal Error
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Record Not Found</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-yellow"><i class="fa fa-bug"></i></h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Internal Error</h3>

                <p>
                    <b>Stay calm.. We got it covered.</b>
                    <br>For data security last request is canceled. Try again please.
                    <br><br>If happen again please report to us.
                    <br><b class="text-gray" id="exception_code">Error Reference Code : {{ $exception->getCode() }}</b>
                    <br><b class="text-gray" style="display: none;" id="exception_message">Error Message : {{ $exception->getMessage() }}</b>
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('.content').on("mouseenter", "#exception_code", function() {
                $('#exception_message').show();
            });
            $('.content').on("mouseleave", "#exception_code", function() {
                $('#exception_message').hide();
            });
        });
    </script>
@endsection