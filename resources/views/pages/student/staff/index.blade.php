@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <table>
            <tr>
                <td>
                    <h4>
                        Staff Master &nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary" href="{{ url('staff/create') }}" role="button">Create New Staff</a>
                    </h4>
                </td>
                <td width="5%"></td>
                <td>
                    <a class="btn btn-primary" id="bulk-import" role="button">Bulk Import staff</a>&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td><a targe="_blank" href="../bulk_staff_import_template.csv">Download Staff Template</a></td>
            </tr>
        </table>

        <br>

        @if(Session::has('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>{{ Session::get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        @endif

        <div id="show-bulk-import" style="display:none;" class="alert alert-warning alert-dismissible fade show" role="alert">
            <h3>Bulk Import</h3>
            <form id="bulk-import" action="{{ url('staff/bulkimport') }}" method="post" enctype="multipart/form-data">
            @csrf
                <div class="form-group">
                    <label for="bulk-import">Brwose File to Import</label>
                    <input type="file" name="csvfile" class="form-control" id="csvfile" accept=".csv">
                </div>
                <div class="form-group" style="text-align: center;">
                    <button type="submit" class="btn btn-primary">Start Import Process</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
        <table id="tracker_datatable" class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
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
    function confirmation()
    {
        if(confirm('Are you sure to delete this record.....?'))
        {
            return true;
        } else {
            return false;
        }
    }

    $(document).ready(function() {

        $("#bulk-import").click(function(){
            $("#show-bulk-import").toggle();
        });

        var table6 = $('#tracker_datatable').DataTable({
            language: {
               "processing" : "<img src={{ asset('/assets/images/loading-14.gif') }}>"
            },
            buttons : [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            stateSave: true,
            processing: true,
            serverSide : true,
            responsive: true,
           ajax: "{{ url('stafflist') }}",
           lengthMenu: [ [7, 10, 25, 50, -1], [7, 10, 25, 50, 'All'] ],
           columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email' },
                    { data: 'gender', name: 'gender', "render": function (data, type, row) {
                            if (row.gender == 1)
                                return "Male"
                            else
                                return "Female"
                        }},
                    { data: 'department', name: 'department', "render": function (data, type, row) {
                            if (row.department == 1)
                                return "Teaching"
                            else if (row.department == 2)
                                return "Lab Support"
                            else if (row.department == 3)
                                return "Accounts"
                            else if (row.department == 4)
                                return "Support Activity"
                            else if (row.department == 5)
                                return "Security"
                            else if (row.department == 6)
                                return "Office Staff"
                            else
                                return "Others"
                        }},
                    { data: 'role', name: 'role', "render": function (data, type, row) {
                            if (row.role == 0)
                                return "Admin"
                            else if (row.role == 1)
                                return "Student"
                            else if (row.role == 2)
                                return "Teacher"
                            else if (row.role == 3)
                                return "HeadMaster"
                            else if (row.role == 4)
                                return "Accountant"
                            else if (row.role == 5)
                                return "Clerk"
                            else if (row.role == 6)
                                return "Cleaner"
                            else if (row.role == 7)
                                return "Cashier"
                            else if (row.role == 8)
                                return "Office Assistant"
                            else
                                return "Others"
                        }},
                    { data: 'action', name : 'action', orderable : false, searchable: false}
                 ]

        });
    });

</script>
@endpush
