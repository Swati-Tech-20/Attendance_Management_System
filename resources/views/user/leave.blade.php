@extends('user.layouts.master')

@section('css')
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css"
    media="screen">
@endsection

@section('button')
<!-- Apply Leave Button -->
<button class="btn btn-primary mt-3" data-toggle="modal" data-target="#applyLeaveModal">Apply for Leave</button>
@endsection

@section('breadcrumb')
<div class="col-sm-6 text-left">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Welcome to Attendance Management System, {{ auth()->user()->name }}!</li>
    </ol>
</div>
@endsection

@section('content')
@include('includes.flash')
<div class="container">
    <h2>Leaves</h2>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-rep-plugin">
                            <div class="table-responsive mb-0" data-pattern="priority-columns">
                                <table id="datatable-buttons"
                                    class="table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Reason</th>
                                            <th>Type</th>
                                            <th>Options For Leave</th>
                                            <th>Status</th>
                                            <th>Rejection Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaves as $leave)
                                        <tr>
                                            <td>{{ $leave->start_date }}</td>
                                            <td>{{ $leave->end_date }}</td>
                                            <td>{{ $leave->reason }}</td>
                                            <td>{{ $leave->type ? 'Paid' : 'Unpaid' }}</td>
                                            <td>{{ $leave->option_for_leave }}</td>
                                            <td>
                                                @if ($leave->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @elseif ($leave->status == 'approved')
                                                <span class="badge badge-success">Approved</span>
                                                @elseif ($leave->status == 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                                @else
                                                <span class="badge badge-secondary">Unknown</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $leave->status == 'rejected' ? ($leave->rejection_reason ?? 'N/A') :
                                                'N/A' }}
                                            </td>
                                            <td>
                                                <!-- Edit button -->
                                                <a href="#editModal{{$leave->id}}" data-toggle="modal" class="btn btn-success btn-sm">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Leave Modal -->
    <div class="modal fade" id="applyLeaveModal" tabindex="-1" role="dialog" aria-labelledby="applyLeaveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyLeaveModalLabel">Apply for Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.leave.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="start_date" class="control-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    placeholder="MM/DD/YYYY" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    onfocus="(this.type='date')">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date" class="control-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" onfocus="(this.type='date')">
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea name="reason" id="reason" placeholder="Reason" class="form-control"
                                required></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="1">Paid</option>
                                    <option value="0">Unpaid</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="option_for_leave">Options For Leave</label>
                                <select name="option_for_leave" id="option_for_leave" class="form-control" required>
                                    <optgroup label="Full Day Leaves">
                                        <option value="PL">PL (Privilege Leave)</option>
                                        <option value="SL">SL (Sick Leave)</option>
                                        <option value="FD">FD (Full Day)</option>
                                        <option value="BL">BL (Bereavement Leave)</option>
                                        <option value="Comp-Off">Comp-Off (Compensatory Off)</option>
                                        <option value="RH">RH (Restricted Holiday)</option>
                                    </optgroup>
                                    <optgroup label="Half Day Leave">
                                        <option value="HD">HD (Half Day)</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
 <!-- Edit Modal -->
@foreach($leaves as $leave)
<div class="modal fade" id="editModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Leave</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('user.leave.update', $leave->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                    <div class="form-group col-md-6">
                        <label for="start_date" class="control-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" 
                               value="{{ $leave->start_date }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="end_date" class="control-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                               value="{{ $leave->end_date }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                    </div>

                    <div class="form-group">
                        <textarea name="reason" id="reason" placeholder="Reason" class="form-control" required>{{ $leave->reason }}</textarea>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="1" {{ $leave->type == 1 ? 'selected' : '' }}>Paid</option>
                                <option value="0" {{ $leave->type == 0 ? 'selected' : '' }}>Unpaid</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="option_for_leave">Options For Leave</label>
                            <select name="option_for_leave" id="option_for_leave" class="form-control" required>
                                <optgroup label="Full Day Leaves">
                                    <option value="PL" {{ $leave->option_for_leave == 'PL' ? 'selected' : '' }}>PL (Privilege Leave)</option>
                                    <option value="SL" {{ $leave->option_for_leave == 'SL' ? 'selected' : '' }}>SL (Sick Leave)</option>
                                    <option value="FD" {{ $leave->option_for_leave == 'FD' ? 'selected' : '' }}>FD (Full Day)</option>
                                    <option value="BL" {{ $leave->option_for_leave == 'BL' ? 'selected' : '' }}>BL (Bereavement Leave)</option>
                                    <option value="Comp-Off" {{ $leave->option_for_leave == 'Comp-Off' ? 'selected' : '' }}>Comp-Off (Compensatory Off)</option>
                                    <option value="RH" {{ $leave->option_for_leave == 'RH' ? 'selected' : '' }}>RH (Restricted Holiday)</option>
                                </optgroup>
                                <optgroup label="Half Day Leave">
                                    <option value="HD" {{ $leave->option_for_leave == 'HD' ? 'selected' : '' }}>HD (Half Day)</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
   