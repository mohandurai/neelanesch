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

                <h3 class="page-title">Project / Lab Activity Report</h3><br>

                <form action="{{ url('projlab/printreport') }}" method="post">
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
                            <option value="0" selected>Select Section</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                            <option value="G">G</option>
                            <option value="H">H</option>
                            <option value="I">I</option>
                            <option value="J">J</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select class="form-control" id="subject_id" name="subject_id">
                            <option value="0" selected>Select Subject</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="chapter_id">Chapter Title</label>
                        <select class="form-control" id="chapter_id" name="chapter_id">
                            <option value="0" selected>Select Chapter/Multiple</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Select Project/Lab Activity</label>
                        <select class="form-control" id="projlab_id" name="projlab_id">
                            <option value="0" selected>Select Activity</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Select Report Type</label>
                        <select class="form-control" id="report_type" name="report_type">
                            <option value="0" selected>Select Type</option>
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
    $("#student_id option[value=0]").prop('selected', true);
    $("#report_type option[value=0]").prop('selected', true);
});


$('#report_type').change(function() {

        var typid = $(this).val();
        var projid = $('#projlab_id').val();
        // alert("UUUUUUUUUUU "+typid+" XXXXXXXXXXX "+projid);
        // return false;

        if(typid == 1) {

            $('div#type1').show();
            $('#studroll_id').html("<option value='0' selected>Select Student</option>");

            $.ajax({
                type: 'GET',
                url: "{{ url('getallstudents') }}/"+projid,
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


$('#class_id').change(function() {

        var clsid = $(this).val();
        var secid = $('#sec_id').val();
        // alert("UUUUUUUUUUU "+clsid);
        // return false;

        $.ajax({
            type: 'GET',
            url: "{{ url('getAllprojLab') }}/"+clsid,
            // complete: function() {
            //     $('#psdatasourcSpinner').hide();
            // },
            success: function(data3) {
                // console.log(data3);
                $('#projlab_id').append(data3);
                // $(".table-responsive").html(data);
            }
        });

});

$('#sec_id').change(function() {
    clsid = $('#class_id').val();
    secid = $('#sec_id').val();
    // alert("Class & Sec "+clsid + " ==== " + secid);
    // return false;
    $("#student_id option[value=0]").prop('selected', true);

    $.ajax({
            type: 'GET',
            url: "{{ url('getStudents') }}/"+clsid+"~~~~~"+secid,
            // complete: function() {
            //     $('#psdatasourcSpinner').hide();
            // },
            success: function(data2) {
                // console.log(data2);
                $('#student_id').append(data2);
                // $(".table-responsive").html(data);
            }
        });
});

$('#class_id').change(function() {

$('#subject_id').html('');
$("#subject_id option[value=0]").prop('selected', true);

    var clsid = $(this).val();

    $.ajax({
        type: 'GET',
        url: "{{ url('/getcontentsubject') }}/" + clsid,
        // complete: function() {
        //     $('#psdatasourcSpinner').hide();
        // },
        success: function(data2) {
            console.log(data2);
            $('#subject_id').append(data2);
            // $(".table-responsive").html(data);
        }
    });

});

$('#subject_id').change(function() {
    // alert("TTTTTTTTTTTT"); return false;
    // $('#chapter_id').html('');
    // $("#chapter_id option[value=0]").prop('selected', true);

    var subid = $(this).val();
    var clsid = $("#class_id").val();

    $.ajax({
        type: 'GET',
        url: "{{ url('/getcontentchapt') }}/" + subid + "~~~" + clsid,
        // complete: function() {
        //     $('#psdatasourcSpinner').hide();
        // },
        success: function(data2) {
            console.log(data2);
            $('#chapter_id').append(data2);
            // $(".table-responsive").html(data);
        }
    });

});


</script>

@endpush
