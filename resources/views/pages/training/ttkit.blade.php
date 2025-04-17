@extends('layout.master')

<meta name="csrf-token" content="{{ csrf_token() }}">

@push('plugin-styles')
 <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
 <style type="text/css">
    .space-below {
      margin-bottom: 10;
    }
    .mb-1 {
        color: blue;
    }
    .bs-example{
    	margin: 20px;
    }
    .modal-dialog iframe{
        margin: 0 auto;
        display: block;
    }
    .nav {
        width: 100%;
        text-align: left;
    }
 </style>
@endpush

@section('content')

@php
    //echo "<pre>";
    //print_r($chapters);
    //print_r($subjects);
    //echo "</pre>";
    //exit;
@endphp

<div class="row">
  <div class="col-xl-16 main-content pl-xl-4 pr-xl-5">
    <h3 class="page-title">Teachers Training Kit</h3>
    <p class="lead space-below">List of Classes  <span style="margin-left:80;">Subjects</span>
    <span style="margin-left:130;">Chapters</span>
    </p>


<div class="row">
  <div class="col-5 col-md-2">
    <div class="nav nav-tabs nav-tabs-vertical" id="v-tab" role="tablist" aria-orientation="vertical">
    @foreach($classlist as $clist)
	@if ($loop->first)
        <a class="nav-link active gradetab" id="v-{{strtolower(str_replace(' ', '', $clist->class))}}-tab" data-toggle="pill" href="#v-{{strtolower(str_replace(' ', '', $clist->class))}}" role="tab" aria-controls="v-{{strtolower(str_replace(' ', '', $clist->class))}}" aria-selected="true">{{$clist->class}}</a>
    @else
        <a class="nav-link gradetab" id="v-{{strtolower(str_replace(' ', '', $clist->class))}}-tab" data-toggle="pill" href="#v-{{strtolower(str_replace(' ', '', $clist->class))}}" role="tab" aria-controls="v-{{strtolower(str_replace(' ', '', $clist->class))}}" aria-selected="false">{{$clist->class}}</a>
    @endif
	@endforeach
    </div>
  </div>

    <div class="col-5 col-md-2">
        <div class="tab-content tab-content-vertical border p-3" id="v-tabContent">

        @foreach($classlist as $clist)
            @if ($loop->first)
                <div class="tab-pane fade show active" id="v-{{strtolower(str_replace(' ', '', $clist->class))}}" role="tabpanel" aria-labelledby="v-{{strtolower(str_replace(' ', '', $clist->class))}}-tab">
                @foreach($subjects[$clist->id] as $sub)
                    <a class="nav-link active getSubjId" id="v-{{strtolower(str_replace(' ', '', $sub->id))}}-tab" data-toggle="pill" href="#v-{{strtolower(str_replace(' ', '', $sub->id))}}" role="tab" aria-controls="v-{{strtolower(str_replace(' ', '', $sub->id))}}" alt="{{$sub->id}}" aria-selected="true">{{$sub->title}}</a>
                @endforeach
                </div>
            @else
                <div class="tab-pane fade" id="v-{{strtolower(str_replace(' ', '', $clist->class))}}" role="tabpanel" aria-labelledby="v-{{strtolower(str_replace(' ', '', $clist->class))}}-tab">
                    @foreach($subjects[$clist->id] as $sub)
                       <a class="nav-link getSubjId" id="v-{{strtolower(str_replace(' ', '', $sub->id))}}-tab" data-toggle="pill" href="#v-{{strtolower(str_replace(' ', '', $sub->id))}}" role="tab" aria-controls="v-{{strtolower(str_replace(' ', '', $sub->id))}}" alt="{{$sub->id}}" aria-selected="true">{{$sub->title}}</a>
                    @endforeach
                </div>
            @endif
        @endforeach
        </div>
    </div>


    <div class="col-7 col-md-7">
        <div class="tab-content tab-content-vertical border p-3" id="v-tabContent">
            <div class="tab-pane show active" id="show-chapts" role="tabpanel"></div>
        </div>
    </div>

</div>


<div class="bs-example">
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" style="color:white;">&times;</button>
                    <!-- <h6 id="video-title">AAAAAAAAAAAAAAAAAAAA</h6> -->
                    <h6 id="video-title"></h6>
                </div>

                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <!-- <iframe src="http://localhost/eschool6/public/storage/videos/Class1_Type2_tamil-Thirukural.mp4" class="embed-responsive-item" width="560" height="315"  allowfullscreen></iframe> -->
                        <iframe id="cartoonVideo" class="embed-responsive-item" width="560" height="315"  allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/prismjs/prism.js') }}"></script>
  <script src="{{ asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>


    $(document).on('click','.geturl',function() {
            var altvalue = $(this).attr("alt");
            var a1_text = $(this).text();

            // alert(altvalue+" <<<===== "+a1_text);

            $("#video-title").append(a1_text);
            $("#cartoonVideo").attr('src', altvalue);

    });



    $(document).ready(function() {

        // $(".geturl").text('');
        $("#show-chapts").html('');

        $(".gradetab").click(function() {
            $("#show-chapts").html('');
        });

        $(".getSubjId").click(function() {

            $("#show-chapts").html('');

            var SubId = $(this).attr("alt");
            // alert("Settings page was loaded......" + SubId);
            // return false;

            $.ajax({
                type: 'GET',
                url: "{{ url('/getchapsubject') }}/" + SubId,
                // complete: function() {
                //     $('#psdatasourcSpinner').hide();
                // },
                success: function(data2) {
                    console.log(data2);
                    $("#show-chapts").append(data2)
                    // $('#chapter_id').append(data2);
                    // $(".table-responsive").html(data);
                }
            });

        });


    });

</script>
@endpush

