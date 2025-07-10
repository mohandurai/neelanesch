@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<style type="text/css">
    .form-control {
        background-color: white;
    }
.table2 {
    cellspacing: 10px;
    cellpadding: 10px;
    color: yellow;
    font-size: 18px;
    align: center;
}
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

        <div class="card-body">

                <h3 class="page-title">Student Report Card</h3><br>

                <form action="{{ url('olexam/printreport') }}" method="post">
                @csrf

                <div class="table-responsive">

                    <div class="form-group">
                        <label for="title">Select Class</label>
                        <select class="form-control" id="class_id" name="class_id">
                            <option value="0" selected>Select Class</option>
                            @foreach($classlist as $kk => $clist)
                                <option value="{{$kk}}">{{$clist}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Select Section</label>
                        <select class="form-control" id="sec_id" name="sec_id">
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

                    <div class="form-group">
                        <label for="title">Select Exam Title</label>
                        <select class="form-control" id="que_templ_id" name="que_templ_id">
                            <option value="0" selected>Select Exam</option>
                        </select>
                    </div>

                     <div class="form-group">
                        <label for="exampleFormControlSelect1">Select Report Type</label>
                        <select class="form-control" id="report_type" name="report_type">
                            <option value="0">Select Type</option>
                            <option value="2">Consolidated</option>
                            <option value="1">Individual</option>
                        </select>
                    </div>

                    <div class="form-group" id="type1" style="display: none;">
                        <label for="title">Select Student Roll ID</label>
                        <select class="form-control" id="studroll_id" name="studroll_id">
                            <option value="0" selected>Select Student</option>
                        </select>
                    </div>

                </div>

                <table>
                    <tr>
                        <td width="50%" align="center">
                            <input type="button" value="Back" onClick="javascript:history.go(-1);">
                        </td>
                        <td width="50%" align="center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </td>
                    </tr>
                </table>

            </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/prismjs/prism.js') }}"></script>
  <script src="{{ asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>

  <script>

$(document).ready(function() {
    $("#class_id option[value=0]").prop('selected', true);
    $("#sec_id option[value=0]").prop('selected', true);
    $("#studroll_id option[value=0]").prop('selected', true);
    $("#report_type option[value=0]").prop('selected', true);
});

$('#report_type').change(function() {

        var typid = $(this).val();
        var projid = $('#que_templ_id').val();
        // alert("UUUUUUUUUUU "+typid+" XXXXXXXXXXX "+projid);
        // return false;

        if(typid == 1) {

            $('div#type1').show();
            $('#studroll_id').html("<option value='0' selected>Select Student</option>");

            $.ajax({
                type: 'GET',
                url: "{{ url('getallstudents2') }}/"+projid,
                // complete: function() {
                //     $('#psdatasourcSpinner').hide();
                // },
                success: function(data3) {
                    console.log(data3);
                    $('#studroll_id').append(data3);
                    // $(".table-responsive").html(data);
                }
            });

        } else {

            $('#type1').hide();

        }

});


// $('#report_type').change(function() {

//     $('#student_id').empty();

//     var reptype = $(this).val();
//     // alert(reptype);
//     if(reptype == 1){
//         clsid = $('#class_id').val();
//         secid = $('#sec_id').val();
//         // alert("Class & Sec "+clsid + " ==== " + secid);
//         if(clsid == "0" || secid == "0" ) {
//             alert("Select Both Class & Section ...");
//             $("#class_id option[value=0]").prop('selected', true);
//             $("#sec_id option[value=0]").prop('selected', true);
//             return false;
//         } else {
//             $.ajax({
//                 type: 'GET',
//                 url: "{{ url('getStudents') }}/"+clsid+"~~~~~"+secid,
//                 // complete: function() {
//                 //     $('#psdatasourcSpinner').hide();
//                 // },
//                 success: function(data2) {
//                     console.log(data2);
//                     $('#student_id').append(data2);
//                     // $(".table-responsive").html(data);
//                 }
//             });
//         }

//     } else if(reptype == 2){
//         $("#student_id").val(0);
//         $("#student_id").attr("readonly", "readonly");
//     } else {

//     }
//     return false;
// });

$('#class_id').change(function() {

        var clsid = $(this).val();
        // alert("UUUUUUUUUUU "+clsid);
        // return false;
        $.ajax({
            type: 'GET',
            url: "{{ url('getStudents') }}/"+clsid,
            // complete: function() {
            //     $('#psdatasourcSpinner').hide();
            // },
            success: function(data2) {
                // console.log(data2);
                $('#student_id').append(data2);
                // $(".table-responsive").html(data);
            }
        });

        $.ajax({
            type: 'GET',
            url: "{{ url('getAllocExams') }}/"+clsid,
            // complete: function() {
            //     $('#psdatasourcSpinner').hide();
            // },
            success: function(data3) {
                // console.log(data2);
                $('#que_templ_id').append(data3);
                // $(".table-responsive").html(data);
            }
        });

});

</script>

@endpush
