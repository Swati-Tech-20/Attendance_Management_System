      <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="slimscroll-menu" id="remove-scroll">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        
                        <!-- Left Menu Start -->
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title">Main</li>
                            <li class="">
                                <a href="/admin/home" class="waves-effect {{ request()->is("admin") || request()->is("admin/*") ? "mm active" : "" }}">
                                    <i class="ti-home"></i><span class="badge badge-primary badge-pill float-right">2</span> <span> Dashboard </span>
                                </a>
                            </li>
                            

                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span> Employees <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                                <ul class="submenu">
                                    <li>
                                        <a href="{{route('admin.employee')}}" class="waves-effect {{ request()->is("employee") || request()->is("/employees/*") ? "mm active" : "" }}"><i class="dripicons-view-apps"></i><span>Employees List</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.register') }}" class="waves-effect {{ request()->routeIs('admin.register') ? 'mm active' : '' }}">
                                            <i class="dripicons-to-do"></i><span class="badge badge-primary badge-pill float-right"></span> <span> Register </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.userlist') }}" class="waves-effect {{ request()->routeIs('admin.userlist') ? 'mm active' : '' }}">
                                            <i class="dripicons-to-do"></i><span class="badge badge-primary badge-pill float-right"></span> <span> UserList </span>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </li>

                            <li class="menu-title">Management</li>

                            
                            <li class="">
                                <a href="/schedule" class="waves-effect {{ request()->is("schedule") || request()->is("schedule/*") ? "mm active" : "" }}">
                                    <i class="ti-time"></i> <span> Schedule </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/check" class="waves-effect {{ request()->is("check") || request()->is("check/*") ? "mm active" : "" }}">
                                    <i class="ti-calendar"></i> <span> Attendance logs </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/attendance_month_sheet" class="waves-effect {{ request()->is("attendance_month_sheet") || request()->is("attendance_month_sheet/*") ? "mm active" : "" }}">
                                    <i class="dripicons-to-do"></i> <span>Attendance Sheet</span>
                                </a>
                            </li>

                            <li class="">
                                <a href="/attendance_month_sheet_report" class="waves-effect {{ request()->is("attendance_month_sheet_report") || request()->is("attendance_month_sheet_report/*") ? "mm active" : "" }}">
                                    <i class="dripicons-to-do"></i> <span> Attendance Sheet View </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/admin/User-status" class="waves-effect {{ request()->is("User-status") || request()->is("User-status/*") ? "mm active" : "" }}">
                                    <i class="dripicons-to-do"></i> <span> User Status </span>
                                </a>
                            </li>
                           
                            <li class="">
                                <a href="/admin/leave" class="waves-effect {{ request()->is("leave") || request()->is("leave/*") ? "mm active" : "" }}">
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
