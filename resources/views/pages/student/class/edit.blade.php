@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@php
    //echo "<pre>";
    //print_r($clsinfo);
    //echo "</pre>";
    //exit;
@endphp

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('student/index') }}">Class Master</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="#">Class Record</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card"`>
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">
            Edit Class Record
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
        </h4>

        <div class="table-responsive">
        <form action="{{ url('class/update') }}" method="post">
        @csrf
        <table id="dataTableExample" class="table">
            @if(!empty($clsinfo))
                <tr>
                    <td>ID</td><td>:</td>
                    <td>
                    <input type="text" class="form-control" name="id" value="{{$clsinfo->id}}" readonly>
                    </td>
                </tr>

                <tr>
                    <td>Class</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="title" value="{{$clsinfo->class}}">
                    </td>
                </tr>

                <tr>
                    <td>Remarks</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="remarks" value="{{$clsinfo->remarks}}">
                    </td>
                </tr>

                <tr>
                    <td>Status</td><td>:</td>
                    <td>
                        <select class="form-control" name="is_deleted">
                            <option value="0" {{ $clsinfo->is_deleted == 0 ? 'selected' : '' }}>Yes</option>
                            <option value="1" {{ $clsinfo->is_deleted == 1 ? 'selected' : '' }}>No</option>
                        </select>
                    </td>
                </tr>


          </table>
        @endif
        </div>
        </div>
            <button type="submit" class="btn btn-primary">Update Record</button>
            <input style="margin-top:20px;" type="button" value="Back" onClick="javascript:history.go(-1);">
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
