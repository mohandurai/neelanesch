@extends('layout.master')

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

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active" aria-current="page">Teachers Exam Evaluation</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

    <h4> Exam Paper Evaluation </h4>

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
                    <th>Class</th>
                    <th>Exam Title</th>
                    <th>Role No</th>
                    <th>Student Name</th>
                    <th>Validated</th>
                    <th>Marks</th>
                    <th>Total Marks</th>
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
           ajax: "{{ url('examcorrect') }}",
        //    pageLength: 5,
           lengthMenu: [ [7, 10, 25, 50, -1], [7, 10, 25, 50, 'All'] ],
           columns: [
                    { data: 'id', name: 'id' },
                    { data: 'class_id', render: function(data, type, row, meta) {
                                return "Grade-" + row.class_id;
                        }
                    },
                    { data: 'title', name: 'title'},
                    { data: 'examroll', name: 'examroll'},
                    { data: 'stname', name: 'stname'},
                    { data: 'is_validated', name: 'is_validated'},
                    { data: 'mark', name: 'mark' },
                    { data: 'total_marks', name: 'total_marks' },
                    { data: 'action', name : 'action', orderable : true, "render": function (data, type, row) {
                               if (row.is_validated == "Yes") //Check column value "Yes"
                                    return ("<a class='btn btn-success' href='#'>Paper Evaluated</a>")
                               else
                                   return data
                           }
                        }
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
