<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Attendance Management System</title>
        <meta content="Admin Dashboard" name="description" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- <meta content="Themesbrand" name="author" /> --}}
        @include('user.layouts.head')
    </head>
<body>
    <div id="wrapper">
         @include('user.layouts.header')
         @include('user.layouts.sidebar')
         <div class="content-page">  
            <div class="content">
                <div class="container-fluid">
                   @include('user.layouts.settings')
                   @yield('content')
                </div> 
            </div> 
        </div> 
        {{-- @include('user.layouts.footer')   --}}
        @include('user.layouts.footer-script')  
    </div> 
    @include('includes.flash')
    </body>
</html>