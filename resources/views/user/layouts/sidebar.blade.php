<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">Main</li>
                <li class="">
                    <a href="{{ route('user.index') }}" class="waves-effect {{ request()->is('user') || request()->is('user/*') ? 'mm active' : '' }}">
                        <i class="ti-home"></i><span class="badge badge-primary badge-pill float-right">2</span> <span> Dashboard </span>
                    </a>
                </li>

                <li class="menu-title">Management</li>
                <li class="">
                    <a href="/attendance" class="waves-effect {{ request()->is('user/attendance') ? 'mm active' : '' }}">
                        <i class="dripicons-to-do"></i> <span> Attendance Sheet </span>
                    </a>
                </li>
                <li class="">
                    <a href="/attendancereport" class="waves-effect {{ request()->is('/user/attendancereport/status') || request()->is('/user/attendancereport/*') ? 'mm active' : '' }}">
                        <i class="dripicons-to-do"></i> <span> Sheet Report </span>
                    </a>
                </li>

                <li class="">
                    <a href="/leave" class="waves-effect {{ request()->is('user/leave') || request()->is('user/leave/*') ? 'mm active' : '' }}">
                        <i class="dripicons-backspace"></i> <span> Leave </span>
                    </a>
                </li>
            </ul>

        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
