@extends('user.layouts.master')

@section('css')
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('content')
@include('includes.flash')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Month Filter Form -->
<form method="GET" action="{{ route('user.attendancereport') }}" class="form-inline mb-3">
    <label for="month" class="mr-2">Select Month:</label>
    <input type="month" id="month" name="month" class="form-control mr-2" value="{{ $month }}">
    <button type="submit" class="btn btn-primary">Filter</button>
</form>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <h2>Attendance Records</h2>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Punch-In</th>
                                        <th>Punch-Out</th>
                                        <th>Work Hours</th>
                                        <th>Status</th>
                                        <th>Break Hours</th>
                                        <th>Total Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->punch_in }}</td>
                                        <td>{{ $attendance->punch_out }}</td>
                                        <td><span class="totalWorkHours">0</span></td>
                                        <td><span class="status badge badge-secondary">Pending</span></td>
                                        <td>
                                            <p>{{ $attendance->total_break_hours }}
                                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#breakDetailsModal" data-attendance-id="{{ $attendance->id }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </p>
                                        </td>
                                        <td><span class="totalTime">0</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Break Details Modal -->
                <div class="modal fade" id="breakDetailsModal" tabindex="-1" role="dialog" aria-labelledby="breakDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="breakDetailsModalLabel">Break Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered" id="breakDetailsTable">
                                    <thead>
                                        <tr>
                                            <th>Break-In</th>
                                            <th>Break-Out</th>
                                            <th>Time Difference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                     
            </div>
        </div>
    </div>
</div>
                </div>
                <script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
                <script>
         document.addEventListener('DOMContentLoaded', function() {
    function calculateWorkAndTotalTime(tableId, startColumn, endColumn, breakColumn, workClass, totalTimeClass) {
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        const requiredWorkHours = 9; // Required work hours

        rows.forEach(row => {
            const startTime = row.cells[startColumn].textContent.trim();
            const endTime = row.cells[endColumn].textContent.trim();
            const breakTimeStr = row.cells[breakColumn].textContent.trim();
            
            // Convert break time string to minutes
            const breakTimeParts = breakTimeStr.match(/(\d+)\s*hours?\s*(\d+)?\s*minutes?/);
            let breakMinutes = 0;
            if (breakTimeParts) {
                const breakHours = parseInt(breakTimeParts[1], 10) || 0;
                const breakMins = parseInt(breakTimeParts[2], 10) || 0;
                breakMinutes = breakHours * 60 + breakMins;
            }

            if (startTime && endTime) {
                // Convert time strings to Date objects
                const start = new Date(startTime);
                const end = new Date(endTime);

                // Calculate the difference in minutes
                const totalMinutes = Math.max(0, (end - start) / (1000 * 60)); // Ensure non-negative

                // Calculate work time
                const workHours = Math.floor(totalMinutes / 60);
                const workMinutes = Math.floor(totalMinutes % 60);
                row.querySelector(`.${workClass}`).textContent = `${workHours} hours ${workMinutes} minutes`;

                // Calculate total time (net work minutes)
                const netWorkMinutes = totalMinutes - breakMinutes;
                const totalTimeHours = Math.floor(netWorkMinutes / 60);
                const totalTimeMinutes = Math.floor(netWorkMinutes % 60);
                row.querySelector(`.${totalTimeClass}`).textContent = `${totalTimeHours} hours ${totalTimeMinutes} minutes`;

                // Determine status
                let status = 'Pending';
                let statusClass = 'badge-secondary';
                const totalWorkHours = workHours + (workMinutes / 60);

                if (totalWorkHours >= requiredWorkHours) {
                    status = 'On Time';
                    statusClass = 'badge-success';
                    if (totalWorkHours > requiredWorkHours) {
                        status = 'Extra Time';
                        statusClass = 'badge-primary';
                    }
                } else {
                    status = 'Late';
                    statusClass = 'badge-danger';

                    // // Check if the punch-in time is after 9:15 AM
                    // const nineTwenty = new Date(startTime);
                    // nine.setHours(9, 0, 0, 0);
                    // const sixPM = new Date(endTime);
                    // sixPM.setHours(18, 0, 0, 0);
                    // if (start > nineTwenty && end < sixPM) {
                    //     status = 'Half Day';
                    //     statusClass = 'badge-warning';
                    // }
                }

                const statusElement = row.querySelector('.status');
                statusElement.textContent = status;
                statusElement.className = `status badge ${statusClass}`;
            }
        });
    }

    calculateWorkAndTotalTime('datatable-buttons', 0, 1, 4, 'totalWorkHours', 'totalTime');

    $('#breakDetailsModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const attendanceId = button.data('attendance-id');

        $.ajax({
            url: '/user/attendancereport/' + attendanceId,
            method: 'GET',
            success: function(data) {
                const breakDetailsTable = $('#breakDetailsTable tbody');
                breakDetailsTable.empty();

                let totalBreakMinutes = 0; // Initialize total break time

                data.breaks.forEach(breakItem => {
                    const breakIn = new Date(breakItem.break_in);
                    const breakOut = new Date(breakItem.break_out);

                    const diffMs = breakOut - breakIn;
                    const diffSecs = Math.floor(diffMs / 1000);

                    const breakHours = Math.floor(diffSecs / 3600);
                    const breakMinutes = Math.floor((diffSecs % 3600) / 60);
                    const breakSeconds = Math.floor(diffSecs % 60);

                    totalBreakMinutes += breakMinutes;

                    breakDetailsTable.append(`
                        <tr>
                            <td>${breakItem.break_in}</td>
                            <td>${breakItem.break_out}</td>
                            <td>${breakHours} h ${breakMinutes} m ${breakSeconds} s</td>
                        </tr>
                    `);
                });

                const totalBreakHours = Math.floor(totalBreakMinutes / 60);
                const remainingMinutes = totalBreakMinutes % 60;
                $('#totalBreakTime').text(`${totalBreakHours} h ${remainingMinutes} m`);
            }
        });
    });
});

 </script>
    </div>
 </div>
 </div>
</div>
@endsection
