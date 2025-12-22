@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Leave</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Leave</a></li>
    </ol>
</div>
@endsection

@section('content')
@include('includes.flash')

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Reason</th>
                                    <th>Type</th>
                                    <th>Rejection Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $leave)
                                <tr>
                                    {{-- <td>{{ $leave->id }}</td> --}}
                                    <td>  
                                        @if($leave->user)
                                        {{ $leave->user->name }}
                                    @else
                                        <em>No User Found</em>
                                    @endif
                                </td>
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td>{{ $leave->reason }}</td>
                                    <td> @if($leave->option_for_leave == 'HD')
                                        Half Day
                                    @elseif($leave->option_for_leave == 'SL')
                                        Sick Leave
                                    @elseif($leave->option_for_leave == 'PL')
                                        Privilege Leave
                                    @elseif($leave->option_for_leave == 'FD')
                                        Full Day
                                    @elseif($leave->option_for_leave == 'BL')
                                        Bereavement Leave
                                    @elseif($leave->option_for_leave == 'Comp-Off')
                                        Compensatory Off
                                    @elseif($leave->option_for_leave == 'RH')
                                        Restricted Holiday
                                    @else
                                        Unknown
                                    @endif</td>
                                    <td>{{ $leave->rejection_reason  ?? 'N/A' }}</td>
                                    {{-- add code for online --}}
                                    <td>
                                        <select name="status" id="status-{{ $leave->id }}" 
                                            class="form-control {{ $leave->status == 'pending' ? 'btn-warning' : '' }} 
                                                                {{ $leave->status == 'approved' ? 'btn-success' : '' }} 
                                                                {{ $leave->status == 'rejected' ? 'btn-danger' : '' }}" 
                                            onchange="handleStatusChange(this, '{{ $leave->id }}')"
                                            {{ ($leave->status == 'pending' && \Carbon\Carbon::parse($leave->leave_date)->lt(now()->startOfDay())) ? 'disabled' : '' }}>
                                            
                                            <option value="pending" class="btn-warning" {{ $leave->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" class="btn-success" {{ $leave->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" class="btn-danger" {{ $leave->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
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

<!-- Bootstrap Modal for Rejection Reason -->
<div class="modal fade" id="rejectionModal" tabindex="-1" role="dialog" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalLabel">Enter Rejection Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rejectionForm">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="leave_id" id="leaveIdInput">
                    <textarea name="rejection_reason" id="rejectionReason" rows="4" class="form-control" placeholder="Enter rejection reason..."></textarea>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitRejectionReason()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function handleStatusChange(select, leaveId) {
    var selectedStatus = select.value;

    if (selectedStatus === 'rejected') {
        document.getElementById('leaveIdInput').value = leaveId;
        document.getElementById('rejectionForm').action = `/admin/leave/${leaveId}/status`;
        $('#rejectionModal').modal('show');
    } else {
        updateLeaveStatus(leaveId, selectedStatus, '');
    }
}

function submitRejectionReason() {
    var leaveId = document.getElementById('leaveIdInput').value;
    var rejectionReason = document.getElementById('rejectionReason').value;

    if (rejectionReason.trim() === '') {
        alert('Please provide a rejection reason.');
    } else {
        $.ajax({
            url: `/admin/leave/${leaveId}/status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: 'rejected',
                rejection_reason: rejectionReason
            },
            success: function(response) {
                $('#rejectionModal').modal('hide');
                alert('Leave status updated successfully.');
                location.reload(); // Reload the page to reflect the updated status
            },
            error: function(xhr) {
                alert('Failed to update leave status.');
            }
        });
    }
}

function updateLeaveStatus(leaveId, status, rejectionReason) {
    $.ajax({
        url: `/admin/leave/${leaveId}/status`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: status,
            rejection_reason: rejectionReason
        },
        success: function(response) {
            alert('Leave status updated successfully.');
            location.reload(); // Reload the page to reflect the updated status
        },
        error: function(xhr) {
            alert('Failed to update leave status.');
        }
    });
}
</script>
@endsection
