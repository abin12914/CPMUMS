<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/images/users/default_user.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                @if(!empty($loggedUser))
                    <p>{{ $loggedUser->name }}</p>
                    <a href="{{ route('user.profile') }}"><i class="fa  fa-hand-o-right"></i> View Profile</a>
                @else
                    <p>Login</p>
                    <a href="{{ route('user.profile') }}"><i class="fa  fa-hand-o-right"></i> To continue</a>
                @endif
            </div>
        </div>
        @if(!empty($loggedUser))
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="{{ Request::is('dashboard')? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                @if($loggedUser->isSuperAdmin() || $loggedUser->isAdmin())
                    <li class="treeview {{ (Request::is('branch/*') || Request::is('branch'))? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-industry"></i>
                            <span>Branch</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('branch/create')? 'active' : '' }}">
                                <a href="{{ route('branch.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('branch')? 'active' : '' }}">
                                <a href="{{ route('branch.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ (Request::is('product/*') || Request::is('product'))? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-industry"></i>
                            <span>Product</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('product/create')? 'active' : '' }}">
                                <a href="{{ route('product.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('product')? 'active' : '' }}">
                                <a href="{{ route('product.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if($loggedUser->isSuperAdmin() || $loggedUser->isAdmin() || $loggedUser->isUser())
                    <li class="treeview {{ Request::is('reports/*')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-briefcase"></i>
                            <span>Reports</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('reports/account-statement')? 'active' : '' }}">
                                <a href="{{ route('report.account-statement') }}">
                                    <i class="fa fa-circle-o text-green"></i> Account Statement
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ ( Request::is('purchase/*') || Request::is('purchase') )? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-line-chart"></i>
                            <span>Purchase</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('purchase/create')? 'active' : '' }}">
                                <a href="{{ route('purchase.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('purchase')? 'active' : '' }}">
                                <a href="{{ route('purchase.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ ( Request::is('production/*') || Request::is('production') )? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-gear"></i>
                            <span>Production</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('production/create')? 'active' : '' }}">
                                <a href="{{ route('production.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('production')? 'active' : '' }}">
                                <a href="{{ route('production.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ ( Request::is('sale/*') || Request::is('sale') )? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-cart-arrow-down"></i>
                            <span>Sale</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('sale/create')? 'active' : '' }}">
                                <a href="{{ route('sale.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('sale')? 'active' : '' }}">
                                <a href="{{ route('sale.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('expense/*') || Request::is('expense')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-wrench"></i>
                            <span>Services & Expences</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('expense/create')? 'active' : '' }}">
                                <a href="{{route('expense.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('expense')? 'active' : '' }}">
                                <a href="{{ route('expense.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('voucher/*') || Request::is('voucher')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-envelope-o"></i>
                            <span>Vouchers & Reciepts</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('voucher/create')? 'active' : '' }}">
                                <a href="{{route('voucher.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('voucher')? 'active' : '' }}">
                                <a href="{{route('voucher.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('account/*') || Request::is('account') ? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span>Accounts</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('account/create')? 'active' : '' }}">
                                <a href="{{route('account.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('account')? 'active' : '' }}">
                                <a href="{{route('account.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ Request::is('employee/*') || Request::is('employee')? 'active' : '' }}">
                        <a href="#">
                            <i class="fa fa-male"></i>
                            <span>Employees</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ Request::is('employee/create')? 'active' : '' }}">
                                <a href="{{route('employee.create') }}">
                                    <i class="fa fa-circle-o text-yellow"></i> Register
                                </a>
                            </li>
                            <li class="{{ Request::is('employee')? 'active' : '' }}">
                                <a href="{{route('employee.index') }}">
                                    <i class="fa fa-circle-o text-aqua"></i> List
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        @endif
    </section>
    <!-- /.sidebar -->
</aside>