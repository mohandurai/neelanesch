@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@php
    //echo "<pre>";
    //print_r($allocte);
    //echo "</pre>";
    //exit;
@endphp

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Masters</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('alloctest/index') }}">Allocate Test</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card"`>
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">
            Edit Test Allocation
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif
        </h4>

        <div class="table-responsive">
        <form action="{{ url('alloctest') }}/update" method="post">
        @csrf
        <table id="dataTableExample" class="table">
            @if(!empty($allocte))
                <tr>
                    <td>ID</td><td>:</td>
                    <td>
                    <input type="hidden" class="form-control" name="id" value="{{$allocte->id}}">
                         {{$allocte->id}}
                    </td>
                </tr>
                <tr>
                    <td>Class ID</td><td>:</td>
                    <td>
                        {{ $allocte->class_id }}
                    </td>
                </tr>
                <tr>
                    <td>Subject</td><td>:</td>
                    <td>
                        {{ $allocte->subject }}
                    </td>
                </tr>

                <tr>
                    <td>Chapter</td><td>:</td>
                    <td>
                        {{ $allocte->chapter }}
                    </td>
                </tr>

                <tr>
                    <td>Title</td><td>:</td>
                    <td>
                        {{$allocte->test_title}}
                    </td>
                </tr>

                <tr>
                    <td>Question Master ID</td><td>:</td>
                    <td>
                         <input type="text" class="form-control" id="qn_master_templ_id" name="qn_master_templ_id" value="{{$allocte->qn_master_templ_id}}" readonly required>
                    </td>
                </tr>

                <tr>
                    <td>Duration in Minutes</td><td>:</td>
                    <td>
                         <input type="text" class="form-control" id="duration" name="duration" value="{{$allocte->duration}}" readonly required>
                    </td>
                </tr>

                <tr>
                    <td>Year</td><td>:</td>
                    <td>
                         <input type="text" class="form-control" id="year" name="year" value="{{$allocte->year}}" readonly required>
                    </td>
                </tr>

                <tr>
                    <td>Term</td><td>:</td>
                    <td>
                         <input type="text" class="form-control" id="term" name="term" value="{{$allocte->terms}}" readonly required>
                    </td>
                </tr>


                <tr>
                    <td>Start Date</td><td>:</td>
                    <td>
                         <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{$allocte->start_date}}" required>
                    </td>
                </tr>


                <tr>
                    <td>End Date</td><td>:</td>
                    <td>
                         <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{$allocte->end_date}}" required>
                    </td>
                </tr>


                <tr>
                    <td>Exam Status</td><td>:</td>
                    <td>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="0" {{ $allocte->is_active == 0 ? 'selected' : '' }}>Select Exam Mode</option>
                            <option value="1" {{ $allocte->is_active == 1 ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $allocte->is_active == 2 ? 'selected' : '' }}>InActive</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan=3><button type="submit" class="btn btn-primary">Update Record</button>
                    </td>
                </tr>
            @endif
            </form>
          </table>
        </div>
      </div>
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
