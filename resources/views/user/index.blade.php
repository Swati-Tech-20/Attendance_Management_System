@extends('user.layouts.master')

@section('css')
<!--Chartist Chart CSS -->
<link rel="stylesheet" href="{{ URL::asset('plugins/chartist/css/chartist.min.css') }}">
@endsection

@section('breadcrumb')
<div class="col-sm-6 text-left">
     <h4 class="page-title">Dashboard</h4>
     <ol class="breadcrumb">
         <li class="breadcrumb-item active">Welcome to Attendance Management System {{ auth()->user()->name }}!</li>
     </ol>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Total Leaves -->
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-6">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-agenda" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-20 text-uppercase mt-0 text-white-50">Total Leaves</h5>
                    <h4 class="font-500">{{ $data['totalLeaves'] }}</h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'></span>
                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Previous Month Total Present -->
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-6">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-check-box" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Previous Month Present</h5>
                    <h4 class="font-500">{{ $data['previousMonthTotalPresent'] }}</h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'></span>
                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Previous Month Total Absent -->
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-6">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-alert" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Previous Month Absent</h5>
                    <h4 class="font-500">{{ $data['previousMonthTotalAbsent'] }}</h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'></span>
                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Month Leaves -->
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-6">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-calendar" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Current Month Leaves</h5>
                    <h4 class="font-500">{{ $data['currentMonthLeaves'] }}</h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'></span>
                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Month Total Present -->
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-6">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-check-box" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Current Month Present</h5>
                    <h4 class="font-500">{{ $data['currentMonthTotalPresent'] }}</h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'></span>
                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Month Total Absent -->
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-6">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-alert" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Current Month Absent</h5>
                    <h4 class="font-500">{{ $data['currentMonthTotalAbsent'] }}</h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }'></span>
                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Peity JS -->
<script src="{{ URL::asset('plugins/peity-chart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/pages/dashboard.js') }}"></script>
@endsection
