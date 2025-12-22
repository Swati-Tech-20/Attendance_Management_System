@extends('layouts.master')

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
    <div class="col-xl-4 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <span class="ti-id-badge" style="font-size: 20px"></span>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Total <br> Employees</h5>
                    <h4 class="font-500">{{$data[0]}} </h4>
                    <span class="ti-user" style="font-size: 71px"></span>

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
    <div class="col-xl-4 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-alarm-clock" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Full Day <br> Today</h5>
                    <h4 class="font-500">{{$data[1]}}</h4>
                    <span class="peity-donut"
                        data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' 
                        data-width="72" data-height="72">
                        {{$data[1]}}/{{$data[0]}} <!-- Use total employees instead of count($data) -->
                    </span>
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
    
    <div class="col-xl-4 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-check-box" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Half Day <br> Today</h5>
                    <h4 class="font-500">{{$data[2]}} <i class="text-success ml-2"></i></h4>
                    <span class="peity-donut"
                        data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' 
                        data-width="72" data-height="72">
                        {{$data[2]}}/{{$data[0]}} <!-- Use total employees instead of count($data) -->
                    </span>
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
            <!-- end row -->
            </div>
        </div>
        <!-- end card -->
    </div>

<!-- end row -->
@endsection

@section('script')
<!--Chartist Chart-->
<script src="{{ URL::asset('plugins/chartist/js/chartist.min.js') }}"></script>
<script src="{{ URL::asset('plugins/chartist/js/chartist-plugin-tooltip.min.js') }}"></script>
<!-- peity JS -->
<script src="{{ URL::asset('plugins/peity-chart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/pages/dashboard.js') }}"></script>
@endsection