@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@php
//echo "<pre>";
//print_r($terminfo);
//echo "</pre>";
//exit;
@endphp

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('term/index') }}">Term Master</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="#">Term Record</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card"`>
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">
            Edit Term Record
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
        </h4>

        <div class="table-responsive">
        <form action="{{ url('term/update') }}" method="post">
        @csrf
        @if(!empty($terminfo))
        <table id="dataTableExample" class="table">
                <tr>
                    <td>ID</td><td>:</td>
                    <td>
                    <input type="text" class="form-control" name="id" value="{{$terminfo->id}}" readonly>
                    </td>
                </tr>
                <tr>
                    <td>Title</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="title" value="{{$terminfo->title}}">
                    </td>
                </tr>
                <tr>
                    <td>Details</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="details" value="{{$terminfo->details}}">
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
