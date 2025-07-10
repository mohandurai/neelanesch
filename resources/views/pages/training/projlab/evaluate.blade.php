@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@php
    //echo "<pre>";
    //print_r($studSubProjval);
    //echo "</pre>";
    //exit;
@endphp

@section('content')

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Project/Lab Activity Evaluation</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="#">Project/Lab Activity Evaluation</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card"`>
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;"> Project/Lab Activity Evaluation</h4>

            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif

        <div class="form-group">
          <table id="dataTableExample" class="table">
            @if(!empty($studSubProjval))

                <tr>
                    <td>Project/Lab Title</td><td>:</td><td>{{$title}}</td>
                </tr>
                <tr>
                    <td>Proj/Act ID</td><td>:</td><td>{{$studSubProjval->proj_activity_id}}</td>
                </tr>
                <tr>
                    <td>Student Name</td><td>:</td><td>{{$studSubProjval->student_name}}</td>
                </tr>
                <tr>
                    <td>Roll ID</td><td>:</td><td>{{$studSubProjval->proj_roll_no}}</td>
                </tr>
                <tr>
                    <td>Activity Image</td><td>:</td>
                    <td>
                        @if($attach_file != "")
                            @php($activimg = "storage/project_activity/class_".$class_id."/".$attach_file)
                            @php($infoPath = pathinfo($activimg))
                            @php($ftype = $infoPath['extension'])
                        @else
                            @php($attach_file="")
                            @php($ftype="")
                            @php($activimg="")
                        @endif
                        @if($ftype == "pdf")
                            <iframe src="{{ url($activimg) }}" class="embed-responsive-item" width="500" height="200"  allowfullscreen></iframe>
                        @else
                            <img src="{{ url($activimg) }}" style="border-radius:0%; width:300px;height:150px;"/>
                        @endif
                        &nbsp;&nbsp;&nbsp;<a href="{{ url($activimg) }}" target="_blank">Open in New Tab</a>
                    </td>
                </tr>
                <tr>
                    <td>Upload Finished Activity Image</td><td>:</td>
                    <td>
                        @php($showimg2 = "storage/project_activity/class_".$class_id."/".$studSubProjval->student_submit_attach)
                        @php($infoPath = pathinfo($showimg2))
                        @php($ftype = $infoPath['extension'])
                        @if($ftype == "pdf")
                            <iframe src="{{ url($showimg2) }}" class="embed-responsive-item" width="500" height="200"  allowfullscreen></iframe>
                        @else
                            <img src="{{ url($showimg2) }}" style="border-radius:0%; width:300px;height:150px;"/>
                        @endif
                        &nbsp;&nbsp;&nbsp;<a href="{{ url($showimg2) }}" target="_blank">Open in New Tab</a>
                    </td>
                </tr>
                <tr>
                    <td>Comments & Remarks</td><td>:</td>
                    <td>
                        {{ $studSubProjval->student_remarks }}
                    </td>
                </tr>
                <tr>
                    <td>Status</td><td>:</td>
                    <td>
                        {{ $studSubProjval->student_status }}
                    </td>
                </tr>
          </table>

          <form action="{{ url('projlab/evaluatefinish') }}" method="post">
                @csrf

            <input type="hidden" name="id" value="{{$studSubProjval->id}}">
            <input type="hidden" name="max_marks" value="{{$max_marks}}">

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Marks Scored</label>
                <input type="number" min="0" max="{{ $max_marks }}" class="form-control" name="mark_scored" value="{{ $studSubProjval->mark_scored }}" required>
            </div>

            <div class="form-group">
                <label for="exampleFormControlTextarea1">Evaluator Comments & Remarks</label>
                <textarea class="form-control" name="evaluator_comments" id="evaluator_comments" rows="5">
                    {{ $studSubProjval->evaluator_comments }}
                </textarea>
            </div>

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Evaluator Status</label>
                <select class="form-control" id="evaluator_status" name="evaluator_status">
                    <option value="Under-Progress" {{ ( $studSubProjval->evaluator_status == "Under-Progress") ? 'selected' : '' }}>Under-Progress</option>
                    <option value="Pending" {{ ( $studSubProjval->evaluator_status == "Pending") ? 'selected' : '' }}>Pending</option>
                    <option value="On-Hold" {{ ( $studSubProjval->evaluator_status == "On-Hold") ? 'selected' : '' }}>On-Hold</option>
                    <option value="Finished" {{ ( $studSubProjval->evaluator_status == "Finished") ? 'selected' : '' }}>Finished</option>
                </select>
            </div>

        </div>
        @endif

       </div>
       <br>
                <button type="submit" class="btn btn-primary">Submit Lab Project/Activity Evaluation</button>
           </form>

           <br><br>
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

@push('custom-scripts')
<script>

$(document).ready(function() {
    $('textarea').html($('textarea').html().trim());
});

    function confirmation()
    {
        if(confirm('Are you sure to delete this record.....?'))
        {
            return true;
        } else {
            return false;
        }
    }

</script>
@endpush
