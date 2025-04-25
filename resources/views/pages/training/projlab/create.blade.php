@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('projlab/index') }}">Project/Lab Activity</a></li>
    <li class="breadcrumb-item active" aria-current="page">Index</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">Create New Project/Lab Activity</h4>

        <div class="table-responsive">
        <form action="{{ url('projlab/store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="exampleFormControlSelect1">Class</label>
                <select class="form-control" id="class_id" name="class_id">
                    <option value="0" selected>Select Class</option>
                    @foreach($classlist as $clist)
                        <option value="{{$clist->id}}">{{$clist->class}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="title">Select Section</label>
                <select class="form-control" id="sec_id" name="sec_id">
                    <option value="Z" selected>ALL</option>
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
                <label` for="exampleFormControlSelect1">Activity Title</label>
                    <input type="text" class="form-control" name="title" required>
            </div>

          <div class="form-group">
            <label for="exampleFormControlTextarea1">Describe Activity</label>
            <textarea class="form-control" name="describe_activity" id="exampleFormControlTextarea1" rows="5"></textarea>
          </div>


            <div class="form-group">
                <label for="title">Attach Activity File</label>
                <input type="file" name="files" class="form-control" multiple>
            </div>

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Status</label>
                <select class="form-control" id="is_active" name="is_active">
                    <option value="1" selected>Active</option>
                    <option value="2">InActive</option>
                </select>
            </div>

            <div class="form-group">
                <label` for="exampleFormControlSelect1">Max. Marks</label>
                    <input type="number" class="form-control" name="max_marks" required>
            </div>

        </div>
      </div>
      <button type="submit" class="btn btn-primary">Create Project/Lab Activity</button>
      </form>
      <input style="margin-top:20px;" type="button" value="Back" onClick="javascript:history.go(-1);">
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
@endpush

@push('custom-scripts')
<script>

$(document).ready(function() {
    $("#class_id option[value=0]").prop('selected', true);
    $("#subject_id option[value=0]").prop('selected', true);
    $("#chapter_id option[value=0]").prop('selected', true);
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
