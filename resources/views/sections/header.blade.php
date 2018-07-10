<header class="main-header">

    <!-- Logo -->
    <a href="/" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b><i class="fa fa-home"></i></b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b title="{{ env('APP_NAME', 'CPMUMS') }}"><i class="fa fa-home"></i></b></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @if (Session::has('message'))
                    <li class="messages-menu" title="See last message">
                        <a href="#" id="show_last_message">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-{{ Session::get('alert-class', 'info') }}">1</span>
                        </a>
                    </li>
                @endif
                <li>
                    @if(!empty($loggedUser))
                        <a href="{{ url('/logout') }}" class="fa fa-sign-out" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @endif
                </li>
            </ul>
        </div>
    </nav>
</header>