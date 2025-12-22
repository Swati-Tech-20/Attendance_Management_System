@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css"
    media="screen">
@endsection
@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Attendance Month Sheet Report</h4>
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
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee Position</th>
                            <th>Employee ID</th>
                            @php
                                $today = \Carbon\Carbon::today();
                                $dates = [];
                                for ($i = 1; $i <= $today->daysInMonth; ++$i) {
                                    $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
                                }
                            @endphp
                            @foreach ($dates as $date)
                                <th>{{ $date }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->user->name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->id }}</td>
                                @for ($i = 1; $i <= $today->daysInMonth; ++$i)
                                    @php
                                        $date_picker = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
                                        $check_attd = \App\Models\Attendance::query()
                                            ->where('user_id', $employee->user_id)  // Use user_id since attendance is linked to the user
                                            ->whereDate('punch_in', $date_picker)
                                            ->first();
                                        $check_leave = \App\Models\Leave::query()
                                            ->where('user_id', $employee->user_id)
                                            ->whereDate('leave_date', $date_picker)
                                            ->first();
                                    @endphp
                                    <td>
                                        {{-- Check Attendance --}}
                                        @if ($check_attd)
                                            <i class="fa fa-check text-primary"></i> A <!-- Blue checkmark for attendance -->
                                        @else
                                            <i class="fas fa-times text-danger"></i> A <!-- Red cross for no attendance -->
                                        @endif
                                        <br>
                                        {{-- Check Leave --}}
                                        @if ($check_leave)
                                            <i class="fa fa-check text-success"></i> L <!-- Green checkmark for approved leave -->
                                        @else
                                            <i class="fas fa-times text-danger"></i> L <!-- Red cross for no leave -->
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
