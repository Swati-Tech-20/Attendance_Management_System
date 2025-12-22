@extends('layouts.master')

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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <h2>Attendance Records</h2>
                    <form id="filterForm" method="GET" action="{{ route('admin.check') }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="punch_in">Start Date</label>
                                <input type="date" class="form-control" id="punch_in" name="punch_in" value="{{ request('punch_in') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="punch_out">End Date</label>
                                <input type="date" class="form-control" id="punch_out" name="punch_out" value="{{ request('punch_out') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="user_id">Employee</label>
                                <select id="user_id" name="user_id" class="form-control">
                                    <option value="">All Employees</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('user_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3 align-self-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
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
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ $attendance->punch_in }}</td>
                                        <td>
                                            @if(is_null($attendance->punch_out))
                                                <form method="POST" action="{{ route('admin.punchOut', $attendance->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm">Punch Out</button>
                                                </form>
                                            @else
                                            {{ $attendance->punch_out }}
                                            @endif
                                        </td>
                                        {{-- <td>{{ $attendance->punch_out }}</td> --}}
                                        <td><span class="workHours">- hours - minutes</span></td>
                                        <td><span class="status badge badge-secondary">Pending</span></td>
                                        <td>
                                            <p>{{ $attendance->total_break_hours }}
                                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#breakDetailsModal" data-attendance-id="{{ $attendance->id }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </p>
                                        </td>
                                        <td><span class="totalTime">- hours - minutes</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal for displaying break details -->
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
                                        <!-- Break details rows will be appended here -->
                                    </tbody>
                                </table>
                                <p>Total Break Time: <span id="breakTotalTime">- hours - minutes</span></p>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    const totalMinutes = (end - start) / (1000 * 60);

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
                                    }

                                    const statusElement = row.querySelector('.status');
                                    statusElement.textContent = status;
                                    statusElement.className = `status badge ${statusClass}`;
                                }
                            });
                        }

                        calculateWorkAndTotalTime('datatable-buttons', 1, 2, 5, 'workHours', 'totalTime');

                        $('#breakDetailsModal').on('show.bs.modal', function(event) {
                            const button = $(event.relatedTarget);
                            const attendanceId = button.data('attendance-id');

                            // Fetch break details for the attendance record
                            $.ajax({
                                url: `/break-details/${attendanceId}`,
                                method: 'GET',
                                success: function(data) {
                                    const breakDetailsTable = $('#breakDetailsTable tbody');
                                    breakDetailsTable.empty();

                                    let totalBreakMinutes = 0;

                                    data.breaks.forEach(breakItem => {
                                        const breakIn = new Date(breakItem.break_in);
                                        const breakOut = breakItem.break_out ? new Date(breakItem.break_out) : null;

                                        if (breakIn && breakOut) {
                                            // Calculate the difference in minutes
                                            const diffMinutes = Math.floor((breakOut - breakIn) / (1000 * 60));

                                            const breakHours = Math.floor(diffMinutes / 60);
                                            const breakMinutes = diffMinutes % 60;

                                            totalBreakMinutes += diffMinutes;

                                            breakDetailsTable.append(`
                                                <tr>
                                                    <td>${breakIn.toLocaleString()}</td>
                                                    <td>${breakOut.toLocaleString()}</td>
                                                    <td>${breakHours} hours ${breakMinutes} minutes</td>
                                                </tr>
                                            `);
                                        } else {
                                            // Handle cases where break_out is missing
                                            breakDetailsTable.append(`
                                                <tr>
                                                    <td>${breakIn.toLocaleString()}</td>
                                                   <td>Still On Break</td>
                                                    <td>N/A</td>
                                                </tr>
                                            `);
                                        }
                                    });

                                    // Display total break time
                                    const totalBreakHours = Math.floor(totalBreakMinutes / 60);
                                    const remainingMinutes = totalBreakMinutes % 60;
                                    $('#breakTotalTime').text(`${totalBreakHours} hours ${remainingMinutes} minutes`);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error fetching break details:', status, error);
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
