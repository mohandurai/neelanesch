@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@php
	//echo "<pre>";
	//print_r($studinfo);
	//echo "</pre>";
    //exit;
@endphp

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('student/index') }}">Students Master</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="#">Students Record</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card"`>
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">
            Edit Staff Record
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
        </h4>

        <div class="table-responsive">
        <form action="{{ url('staff/update') }}" method="post">
        @csrf
        <table id="dataTableExample" class="table">
            @if(!empty($studinfo))
                <tr>
                    <td>ID</td><td>:</td>
                    <td>
                    <input type="text" class="form-control" name="id" value="{{$studinfo->id}}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>First Name</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="name" value="{{$studinfo->name}}" required>
                    </td>
                </tr>

                <tr>
                    <td>Email ID</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="email" value="{{$studinfo->email}}">
                    </td>
                </tr>

                <tr>
                    <td>Contact Info</td><td>:</td>
                    <td>
                        <input type="number" class="form-control" name="mobile" value="{{$studinfo->contact_info}}">
                    </td>
                </tr>

                <tr>
                    <td>Gender</td><td>:</td>
                    <td>
                        <select class="form-control" name="gender">
                            <option value="0">Select Gender</option>
                            <option value="1" {{ $studinfo->gender == 1 ? 'selected' : '' }}>Male</option>
                            <option value="2" {{ $studinfo->gender == 2 ? 'selected' : '' }}>FeMale</option>
                            <option value="3" {{ $studinfo->gender == 3 ? 'selected' : '' }}>TransGender</option>
                        </select>
                    </td>
                </tr>


                <tr>
                    <td>Department</td><td>:</td>
                    <td>
                        <select class="form-control" name="department">
                            <option value="0">Select Department</option>
                            <option value="1" {{ $studinfo->department == 1 ? 'selected' : '' }}>Teaching</option>
                            <option value="2" {{ $studinfo->department == 2 ? 'selected' : '' }}>Lab Support</option>
                            <option value="3" {{ $studinfo->department == 3 ? 'selected' : '' }}>Accounts</option>
                            <option value="4" {{ $studinfo->department == 4 ? 'selected' : '' }}>Support Activity</option>
                            <option value="5" {{ $studinfo->department == 5 ? 'selected' : '' }}>Security</option>
                            <option value="6" {{ $studinfo->department == 6 ? 'selected' : '' }}>Office Staff</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Role</td><td>:</td>
                    <td>
                        <select class="form-control" name="role">
                            <option value="0">Select Role</option>
                            <option value="1" {{ $studinfo->role == 1 ? 'selected' : '' }}>Teacher</option>
                            <option value="2" {{ $studinfo->role == 2 ? 'selected' : '' }}>Head Master</option>
                            <option value="3" {{ $studinfo->role == 3 ? 'selected' : '' }}>Accountant</option>
                            <option value="4" {{ $studinfo->role == 4 ? 'selected' : '' }}>Clert</option>
                            <option value="5" {{ $studinfo->role == 5 ? 'selected' : '' }}>Lab Assistant</option>
                            <option value="6" {{ $studinfo->role == 6 ? 'selected' : '' }}>Cashier</option>
                            <option value="7" {{ $studinfo->role == 7 ? 'selected' : '' }}>Office Assistant</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Status</td><td>:</td>
                    <td>
                        <select class="form-control" name="status">
                            <option value="0">Select Status</option>
                            <option value="1" {{ $studinfo->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $studinfo->status == 2 ? 'selected' : '' }}>InActive</option>
                        </select>
                    </td>
                </tr>

          </table>
        </div>
        </div>
            <button type="submit" class="btn btn-primary">Update Record</button>
            <input style="margin-top:20px;" type="button" value="Back" onClick="javascript:history.go(-1);">

            @endif
            </form>

    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
