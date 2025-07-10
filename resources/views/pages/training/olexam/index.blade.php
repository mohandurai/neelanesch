@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
@endpush

<meta name="csrf-token" content="{{ csrf_token() }}">

@push('plugin-styles')
<link href="{{ asset('/assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<style type="text/css">
.buttons-pdf, .buttons-excel, .buttons-copy, .buttons-csv, .buttons-print {
  float: right;
}
.below-margin {
    margin-bottom : 10px;
}
</style>
@endpush


@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')

@php
    //echo "<pre>";
    //echo $fullname;
    //echo "</pre>";
    //exit;
@endphp

<!-- Before start Exam Student Fill necessary info  -->
    <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Before Start Exam - Fill up below Details:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white;">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                  <form id="exam-register-form" name="exam-register-form" class="form-horizontal" method="POST" action="{{ url('olexam/attendexam') }}">
                  @csrf
                    <input type="hidden" name="exam_id" id="exam_id">

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Roll :</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="rollno" name="rollno" placeholder="Enter Roll No."  maxlength="20" value="" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Full&nbsp;Name&nbsp;:</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="stud_name" name="stud_name" value="" maxlength="100" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Class :</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="class_id" name="class_id" readonly required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Section : </label>
                            <div class="col-sm-12">
                                <select class="form-control" id="Section" name="Section">
                                    <option value="0" selected>ALL</option>
                                    <option value="A1">A1</option>
                                    <option value="A2">A2</option>
                                    <option value="A3">A3</option>
                                    <option value="A4">A4</option>
                                    <option value="A5">A5</option>
                                    <option value="A6">A6</option>
                                    <option value="A7">A7</option>
                                    <option value="A8">A8</option>
                                    <option value="A9">A9</option>
                                </select>
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
                            <button type="submit" class="btn btn-primary" value="create">Begin Exam</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Ends Before start Exam Student Fill necessary info  -->

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      <h4> Student Online Exam </h4>

    @if(Session::has('message'))
        </br>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>{{ Session::get('message') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><strong>&times;</strong></span>
        </button>
        </div>
    @endif

        <div class="table-responsive">
        <table id="tracker_datatable" class="table">
            <thead>
                <tr>
                    <td></td>
                    <td><input type="text" class="form-control filter-input" placeholder="Find ..." data-column="1" /></td>
                    <td><input type="text" class="form-control filter-input" placeholder="Find ..." data-column="2" /></td>
                    <td><input type="text" class="form-control filter-input" placeholder="Find ..." data-column="3" /></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><button type="button" id="clear-filter">Clear</td>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Exam Title</th>
                    <th>Qn.Master ID</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Duration Minutes</th>
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
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
<script>

$(document).on('click', '#mediumButton2', function(e) {
    var alloc_id = $(this).attr("alt");
    const myArray = alloc_id.split("~~~~~");
    $("#exam_id").val(myArray[0]);
    $("#class_id").val(myArray[2]);
    if(myArray[1] == 0) {
        $("#Section").val("");
    } else {
        $("#Section").val(myArray[1]);
    }
});

// Submit button
// $(document).on('click', '#submitbtn', function(e) {
//     e.preventDefault();
//     var form6 = $('#exam-register-form').serializeArray();
//     $.each(form6, function(i, field){

//         if(field.name === "exam_id") {
//             // alert("ZZZZZZZZZZZZZZZZ");
//             // return false;
//             window.location= "{{ url('olexam/')}}/"+field.value+"/attendexam";
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
        order: [[ 5, 'desc' ], [ 1, 'asc' ]],
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
        serverSide : true,
        responsive: true,
        ajax: "{{ url('examslist') }}",
    //    pageLength: 5,
        lengthMenu: [ [7, 10, 25, 50, -1], [7, 10, 25, 50, 'All'] ],
        columns: [
                { data: 'id', name: 'id' },
                { data: 'test_title', name: 'test_title'},
                { data: 'qn_master_templ_id', name: 'qn_master_templ_id' },
                { data: 'class_id', render: function(data, type, row, meta) {
                            return "Grade-" + row.class_id;
                    }
                },
                { data: 'sec_id', render: function(data, type, row, meta) {
                        if(row.sec_id == '0') {
                            return "ALL";
                        } else {
                            return row.sec_id;
                        }
                    }
                },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                { data: 'duration', name: 'duration' },
                { data: 'action', name : 'action', orderable : true, searchable: true}
                ]

    });

    $('#clear-filter').click(function() {
        table6.search('').columns().search('').draw();
        $('.filter-input-integer').val('');
        $('.filter-input').val('');
    });


    $('.filter-input').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            // alert($(this).val());
            //var svalue = $(this).val();
            table6.column( $(this).data('column') ).search( $(this).val() ).draw();
        }

    });

});

</script>
@endpush
