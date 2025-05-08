@extends('layout.master')

<meta name="csrf-token" content="{{ csrf_token() }}">

@push('plugin-styles')
<link href="{{ asset('/assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<style type="text/css">
.buttons-pdf, .buttons-excel, .buttons-copy, .buttons-csv, .buttons-print {
  float: right;
}
</style>
@endpush


@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')

<!-- Before start Homework Student Fill necessary info  -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Before Start Homework - Confirm below Details:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white;">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                  <form id="exam-register-form" name="exam-register-form" class="form-horizontal" method="POST" action="{{ url('/homework/homeworksubmituser/') }}">
                  @csrf
                    <input type="hidden" name="exam_id" id="exam_id">

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Roll :</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="hw_roll_no" name="hw_roll_no" placeholder="Enter Roll No." value="{{$hw_roll_no}}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Full&nbsp;Name&nbsp;:</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="stud_name" name="stud_name" value="{{$fullname}}" maxlength="50" readonly required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Class :</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="class_id" name="class_id"  value="{{$class}}" maxlength="50" readonly required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Section :</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" readonly id="Section" name="Section" value="{{$sec}}" required="">
                            </div>
                        </div>

                        <!-- <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Term :</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="term" name="term" placeholder="Term Name ..." value="" maxlength="50" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Subject</label>
                            <div class="col-sm-12">
                                <textarea id="subject_id" name="subject_id" required="" placeholder="Enter Subject Name ....." class="form-control"></textarea>
                            </div>
                        </div> -->

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" value="create">Begin Home Work</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Ends Before start Homework Student Fill necessary info  -->




<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('projlab/studprojindex') }}">Home Work Activity Submit</a></li>
    <li class="breadcrumb-item active" aria-current="page">Home Work Activity Submit</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4>Student Home Work Activity Submit</h4>

        @if(Session::has('message'))
        </br>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>{{ Session::get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        @endif

        <div class="table-responsive">
        <table id="tracker_datatable" class="table">
            <thead>
                <!-- <tr>
                    <td></td>
                    <td><input type="text" class="form-control filter-input" placeholder="Find ..." data-column="2" /></td>
                    <td><input type="text" class="form-control filter-input" placeholder="Find  ..." data-column="3" /></td>
                </tr> -->
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Evaluator Comments</th>
                    <th>Marks Scored</th>
                    <th>Max. Marks</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
@endpush

@push('custom-scripts')
<script>
    function confirmation()
    {
            if(confirm('Are you sure to delete this record.....?'))
            {
                return true;
            } else {
                return false;
            }
    }



$(document).on('click', '#mediumButton4', function(e) {
    var alloc_id = $(this).attr("alt");
    // alert("AAAAA   " + alloc_id);
    // return false;
    $("#exam_id").val(alloc_id);
});

    // Submit button
// $(document).on('click', '#submitbtn', function(e) {
//     e.preventDefault();
//     var form6 = $('#exam-register-form').serializeArray();
//     $.each(form6, function(i, field){

//         if(field.name === "exam_id") {
//             // alert("ZZZZZZZZZZZZZZZZ   " + field.name + " AAAAAAA " + field.value);
//             // return false;
//             window.location= "{{ url('homework/')}}/"+field.value+"/homeworksubmituser";
//         }
//     });

// });

    $(document).ready(function() {
        // alert("Settings page was loaded");
        // return false;
        var table6 = $('#tracker_datatable').DataTable({
            language: {
               "processing" : "<img src={{ asset('/assets/images/loading-14.gif') }}>"
            },
            // dom: '<"top"<"left-col"B><"center-col"l><"right-col"f>>rtip',
            // dom : "Bflrtip",
            // dom: '<"top"i>flrtp<"clear">',
            dom: '<"top"f><"bottom"rtlp><"clear">',
            // dom: '<"title"<"filter"f>>ltip',
            buttons : [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            stateSave: true,
            processing: true,
            serverSide : false,
            responsive: true,
           ajax: "{{ url('homework2list') }}",
        //    pageLength: 5,
           lengthMenu: [ [7, 10, 25, 50, -1], [7, 10, 25, 50, 'All'] ],
           columns: [
                { data: 'id', name: 'id' },
                { data: 'title', name: 'title'},
                { data: 'class_id', name: 'class_id' },
                { data: 'sec_id', render: function(data, type, row, meta) {
                        if(row.sec_id == '0') {
                            return "ALL";
                        } else {
                            return row.sec_id;
                        }
                    }
                },
                { data: 'evaluator_status', name: 'evaluator_status' },
                { data: 'mark_scored', name: 'mark_scored' },
                { data: 'max_marks', name: 'max_marks' },
                { data: 'status', name: 'status'},
                { data: 'action', name : 'action', orderable : true, searchable: true}
            ]

        });
    });

</script>
@endpush
