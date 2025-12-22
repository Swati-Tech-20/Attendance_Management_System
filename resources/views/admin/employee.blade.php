@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Employees</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Employees</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Employees List</a></li>

    </ol>
</div>
@endsection
@section('button')
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add</a>


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
<!--End showing Validation Errors here-->


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                    <thead>
                        <tr>
                            {{-- <th data-priority="1">Employee ID</th> --}}
                            <th data-priority="2">Employee Name</th>
                            <th data-priority="3">position</th>
                            <th data-priority="4">Address</th>
                            <th data-priority="5">State</th>
                            <th data-priority="7">City</th>
                            <th data-priority="6">Pincode</th>
                            <th data-priority="9">Schedule</th>
                            <th data-priority="10">Member Since</th>
                            <th data-priority="11">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $employees as $employee)
                        @php
                        // Check if profile_details is already an array, if not, decode it
                        $profileDetails = is_array($employee->profile_details) 
                                          ? $employee->profile_details 
                                          : json_decode($employee->profile_details, true);
                    @endphp
                        <tr>
                            {{-- <td>{{$employee->id}}</td> --}}
                            <td>{{ $employee->user ? $employee->user->name : 'N/A' }}</td>
                            <td>{{$employee->position}}</td>
                            <td>{{ $profileDetails['address'] ?? 'N/A' }}</td>
                            <td>{{ $profileDetails['state'] ?? 'N/A' }}</td>
                            <td>{{ $profileDetails['city'] ?? 'N/A' }}</td>
                            <td>{{ $profileDetails['pincode'] ?? 'N/A' }}</td>
                            <td>
                                @if(isset($employee->schedules->first()->slug))
                                {{$employee->schedules->first()->slug}}
                                @endif
                            </td>
                            <td>{{$employee->created_at}}</td>
                            <td>

                                <a href="#edit{{$employee->id}}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat">
                                    <i class='fa fa-edit'></i> Edit
                                </a>
                                <a href="#delete{{$employee->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat">
                                    <i class='fa fa-trash'></i> Delete
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
</div> <!-- end col -->
</div> <!-- end row -->


@foreach( $employees as $employee)
@include('includes.edit_delete_employee')
@endforeach

@include('includes.add_employee')

@endsection


@section('script')
<!-- Responsive-table-->

@endsection