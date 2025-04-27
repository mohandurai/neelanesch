@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('student/index') }}">Staff Record</a></li>
    <li class="breadcrumb-item active" aria-current="page">Staff Master</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">Create New Staff</h4>

        <div class="table-responsive">
        <form id="submitForm3" action="{{ url('staff/store') }}" method="post">
            @csrf
            <div class="form-group">
            <label for="title">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>

            <div class="form-group">
            <label for="title">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name">
            </div>

            <div class="form-group">
            <label for="title">Email ID</label>
            <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
            <label for="title">Mobile/Contact Info.</label>
            <input type="number" class="form-control" id="mobile" name="mobile">
            </div>

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Select Geder</label>
                <select class="form-control" id="gender" name="gender">
                <option value="0" selected>Select Gender</option>
                <option value="1">Male</option>
                <option value="2">Female</option>
                <option value="3">TransGender</option>
                </select>
            </div>

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Select Department</label>
                <select class="form-control" id="department" name="department">
                    <option value="0" selected>Select Department</option>
                    <option value="1">Teaching</option>
                    <option value="2">Lab Support</option>
                    <option value="3">Accounts</option>
                    <option value="4">Support Activity</option>
                    <option value="5">Security</option>
                    <option value="6">Office Staff</option>
                </select>
            </div>

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Select Role</label>
                <select class="form-control" id="role" name="role">
                    <option value="0" selected>Select Role</option>
                    <option value="1">Teacher</option>
                    <option value="2">Head Master</option>
                    <option value="3">Accountant</option>
                    <option value="4">Clert</option>
                    <option value="5">Lab Assistant</option>
                    <option value="6">Cashier</option>
                    <option value="7">Office Assistant</option>
                </select>
            </div>


            <div class="form-group">
            <label for="title">Attach PP Size Photo</label>
            <input type="file" name="photo_image" class="form-control" accept=".jpeg,.jpg,.png,.tiff,.bmp">
            </div>

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Require Login</label>
                <select class="form-control" id="require_login" name="require_login">
                    <option value="0" selected>None</option>
                    <option value="1">Yes</option>
                    <option value="2" selected>No</option>
                </select>
            </div>

       </div>
      </div>
      <button type="submit" class="btn btn-primary">Create Staff</button>
      </form>
      <input style="margin-top:20px;" type="button" value="Back" onClick="javascript:history.go(-1);">
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
  <script>

    $('#submitForm3').submit(function() {
        if($('#gender').val() == 0) {
            alert("Select Gender .....");
            return false;
        }
        if($('#Section').val() == 0) {
            alert("Select Section .....");
            return false;
        }
        if($('#class_id').val() == 0) {
            alert("Select Class .....");
            return false;
        }
    });

  </script>
@endpush


