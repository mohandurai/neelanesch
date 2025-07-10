@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('class/index') }}">Class Record</a></li>
    <li class="breadcrumb-item active" aria-current="page">Class Master</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">Create New Class</h4>

        <div class="table-responsive">
        <form id="submitForm3" action="{{ url('class/store') }}" method="post">
            @csrf
            <div class="form-group">
            <label for="title">Class Name</label>
            <input type="text" class="form-control" id="class_title" name="class_title" required>
            </div>

            <div class="form-group">
            <label for="title">Remarks</label>
            <input type="text" class="form-control" id="remarks" name="remarks">
            </div>

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Active</label>
                <select class="form-control" id="is_deleted" name="is_deleted">
                <option value="0" selected>Yes</option>
                <option value="1">No</option>
                </select>
            </div>

       </div>
      </div>
      <button type="submit" class="btn btn-primary">Create Class</button>
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
@endpush


