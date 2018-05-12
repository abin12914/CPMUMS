<!DOCTYPE html>
<html>
    <head>
        <!-- sections/head.main.blade -->
        @include('sections.head')

        {{-- additional stylesheet includes --}}
        @section('stylesheets')
        @show
    </head>
    <body class="hold-transition fixed skin-red sidebar-mini">
        <div class="wrapper">

            <!-- sections/header.main.blade -->
            @include('sections.header')

            <!-- sections/leftsidebar.main.blade -->
            @include('sections.leftsidebar')

            <!-- Content Wrapper. Contains page content -->
            @section('content')
            @show

            <!-- sections/footer.main.blade -->
            @include('sections.footer')

            <!-- sections/rightsidebar.main.blade -->
            @include('sections.rightsidebar')
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED JS SCRIPTS -->
        @include('sections.scripts')

        {{-- additional js scripts includes --}}
        @section('scripts')
        @show
    </body>
</html>
