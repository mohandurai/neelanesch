@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('homework/homeworkindex') }}">Home Work Activity</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="#">Home Work Submit</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card"`>
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">Home Work Submit</h4>

            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif

        <form action="{{ url('homework/homeworkfinish') }}" method="post" enctype="multipart/form-data">
            @csrf

        <div class="form-group">
          <table id="dataTableExample" class="table">
            @if(!empty($homework))

                <tr>
                    <td>ID</td><td>:</td><td>{{$homework->id}}</td>
                </tr>
                <tr>
                    <td>Title</td><td>:</td><td>{{$homework->title}}</td>
                </tr>
                <tr>
                    <td>Activity Description</td><td>:</td>
                    <td>
                        {{$homework->describe_activity}}
                    </td>
                </tr>

                <tr>
                    <td>Class</td><td>:</td><td>{{$homework->class_id}}</td>
                </tr>
                <tr>
                    <td>Subject</td><td>:</td><td>{{$homework->subject_id}}</td>
                </tr>
                <tr>
                    <td>Chapter</td><td>:</td><td>{{$homework->chapter_id}}</td>
                </tr>
                <tr>
                    <td>Attachment</td><td>:</td>
                    <td>
                        @php($showimg = "storage/homework/class_".$homework->class_id."/".$homework->attachment)
                        @if ($filetype == 'docx' || $filetype == 'doc' || $filetype == 'ods')
                            <a target="_blank" href="{{ url($showimg) }}">{{ $homework->attachment }}</a>
                        @elseif ($filetype == 'pdf')
                        <iframe src="{{ url($showimg) }}" width="50%" height="200">
                                This browser does not support PDFs. Please download the PDF to view it: <a href="{{ url($showimg) }}">Download PDF</a>
                        </iframe>
                        @elseif ($filetype == 'png' || $filetype == 'gif' || $filetype == 'jpg' || $filetype == 'jpeg' || $filetype == 'bmp')
                            <img src="{{ url($showimg) }}" style="border-radius:0%; width:300px;height:150px;"/>
                        @elseif ($filetype == '')
                            <p>No Image Attached....</p>
                        @else
                            <a target="_blank" href="{{ url($showimg) }}">{{ $projLabAct->attachment }}</a>
                        @endif
                    </td>
                </tr>
            @endif

          <tr>
            <td>
                <input type="hidden" name="proj_id" value="{{$homework->id}}">
                <input type="hidden" name="class_id" value="{{$homework->class_id}}">
                <input type="hidden" name="hw_roll_no" value="{{$roleid}}">
                <input type="hidden" name="student_id" value="{{$studid}}">

            <label for="title">Upload Finished Activity File : </label>
            </td>
            <td>:</td>
            <td>
                    @php($showimg2 = "storage/homework/class_".$homework->class_id."/".$homework->student_submit_attach)
                    <img src="{{ url($showimg2) }}" style="border-radius:0%; width:300px;height:150px;"/>
                    <input type="file" name="image_projlab_finish" class="form-control" accept=".mp4,.mp3,.jpeg,.ppt,.pptx,.jpg,.png,.bmp..mkv,.mov,.mpeg-2">

            </td>
        </tr>
        <tr>
            <td>
                <label for="exampleFormControlTextarea1">Comments & Remarks</label>
            </td>
            <td>:</td>
            <td>
                <textarea class="form-control" name="student_remarks" id="exampleFormControlTextarea1" rows="5"> {{ $homework->student_remarks }} </textarea>
            </td>
        </tr>

        <tr>
            <td>
                <label for="exampleFormControlTextarea1">Status</label>
            </td>
            <td>:</td>
            <td>
                <label` for="exampleFormControlSelect1"></label>
                <select class="form-control" id="status" name="status">
                    <option value="Not Started" {{ ( $homework->status == "Not Started") ? 'selected' : '' }}> Not Started </option>
                    <option value="In Progress" {{ ( $homework->status == "In Progress") ? 'selected' : '' }}> In Progress</option>
                    <option value="Pending" {{ ( $homework->status == "Pending") ? 'selected' : '' }}> Pending</option>
                    <option value="On Hold" {{ ( $homework->status == "On Hold") ? 'selected' : '' }}> On Hold</option>
                    <option value="Finished" {{ ( $homework->status == "Finished") ? 'selected' : '' }}> Finished</option>
                    <option value="Others" {{ ( $homework->status == "Others") ? 'selected' : '' }}> Others</option>
                </select>
            </td>
        </tr>

    </table>

       </div>
    </div>

            <button type="submit" class="btn btn-primary">Submit Home Work</button>

            <div style="margin-top:20px;">
                  <a class="btn btn-primary" href="{{ url('homework/homeworkindex')}}" role="button">Back</a>
            </div>

           </form>
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
