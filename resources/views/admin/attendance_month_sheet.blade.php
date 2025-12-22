@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
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
            <form action="{{ route('admin.check_store') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-success" style="display: flex; margin:10px">Submit</button>

                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-striped table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Employee Position</th>
                                @php
                                    $today = today();
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

                                    @foreach ($dates as $date_picker)
                                        @php
                                            // Check if attendance exists for this user on this date
                                            $check_attd = \App\Models\Attendance::query()
                                                ->where('user_id', $employee->user_id)
                                                ->whereDate('punch_in', $date_picker)
                                                ->exists();
                                            
                                            // Check if leave exists for this user on this date
                                            $check_leave = \App\Models\Leave::query()
                                                ->where('user_id', $employee->user_id)
                                                ->whereDate('leave_date', $date_picker)
                                                ->exists();
                                        @endphp
                                        <td>
                                            {{-- Attendance checkbox --}}
                                            <div class="form-check form-check-inline">
                                                <input type="hidden" name="attd[{{ $date_picker }}][{{ $employee->user_id }}]" value="0">
                                                <input class="form-check-input"
                                                       name="attd[{{ $date_picker }}][{{ $employee->user_id }}]"
                                                       type="checkbox"
                                                       @if ($check_attd) checked @endif
                                                       value="1">
                                                <label class="form-check-label">A</label>
                                            </div>
                                            
                                            {{-- Leave checkbox --}}
                                            <div class="form-check form-check-inline">
                                                <input type="hidden" name="leave[{{ $date_picker }}][{{ $employee->user_id }}]" value="0">
                                                <input class="form-check-input"
                                                       name="leave[{{ $date_picker }}][{{ $employee->user_id }}]"
                                                       type="checkbox"
                                                       @if ($check_leave) checked @endif
                                                       value="1">
                                                <label class="form-check-label" style="color:red">L</label>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
