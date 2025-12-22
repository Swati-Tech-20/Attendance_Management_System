@extends('layouts.master')
@section('css')
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css"
    media="screen">
@endsection
@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Total Leaves Report</h4>
</div>
@endsection
@section('content')
@include('includes.flash')

<!--Show Validation Errors here-->
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.userstatus') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="user_id">Select Employee:</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">All Employees</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $selectedUser == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
            
                        <div class="col-md-4">
                            <label for="month">Select Month:</label>
                            <input type="month" name="month" id="month" class="form-control" value="{{ $selectedMonth }}">
                        </div>
            
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary" style="margin-top: 30px;">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Total Working Days</th>
                                    <th>Total Leave Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    @php
                                        $leave = $leaveData->firstWhere('user_id', $user->id);
                                        $attendance = $attendanceData->firstWhere('user_id', $user->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $attendance->total_working_days ?? 0 }}</td>
                                        <td>{{ $leave->total_leaves ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($users->isEmpty())
                        <p>No leaves found for the selected filters.</p>
                        @endif
                    </div>
                    @endsection