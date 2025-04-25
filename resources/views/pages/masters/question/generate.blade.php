@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<style type="text/css">
    .mgn10px {
        margin-left: 10px;
    }

    .note-editable {
        padding: 1px;
        border: solid 1px #5c8593;
        font-size: 10pt;
        color: #42484d;
        background-color: #FFFFFF !important;
    }
</style>
@endpush

@php
    //echo "<pre>";
    //print_r($qns);
    //print_r($ans);
    //echo "</pre>";
    //exit;
@endphp

@section('content')

@php($alpha = array(1=>"A",2=>"B",3=>"C",4=>"D",5=>"E",6=>"F",7=>"G",8=>"H",9=>"I",10=>"J",11=>"K",12=>"L",13=>"M",14=>"N",15=>"N"))

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('question/index') }}">Masters</a></li>
        <li class="breadcrumb-item active" aria-current="page">Generate Questions</li>
    </ol>
</nav>

<div class="row">

    <div class="col-md-12 grid-margin stretch-card">

        <div class="card">

            <h4>Fill up the Questions with Answer Key</h4>

            @php($count=0)

            @foreach($qhead as $kk => $qh)
            <div class="card-body">

                <!-- <form id="form-wrapper" name="form2" action="{{ url('question/storeqns') }}" method="post"> -->
                <form id="dynamicForm" name="form2">
                    @csrf

                    <div class="form-group">

                        <input type="hidden" id="qn_temp_id" name="qn_temp_id" alt="{{ $qntempl }}" value="{{ request('id') }}">


                        @php($count++)

                        <label style="color:yellow;">
                            <h5>{{$romlet[$count]}}. {{$qh}}</h5>
                        </label>

                        @for($qq=1; $qq <= $qncnt[$kk]; $qq++)

                            @if($kk==3)
                                <div class="form-group">
                                @if($qq == 1)
                                <table width="80%" cellspacing="5" cellpadding="5" border="1">
                                    <tr align="center" style="background-color:yellow;color:black;">
                                        <td width="10%"></td>
                                        <td width="40%">Column A</td>
                                        <td width="10%"></td>
                                        <td width="40%">Column B</td>
                                    </tr>
                                    <tr>
                                        <td width="2%"> {{ $qq }})</td>
                                        <td width="20%">
                                            <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}[0]" id="que_{{$kk}}_{{$qq}}A" value="" placeholder="Enter Matching Words">
                                        </td>
                                        <td width="2%"> {{ $alpha[$qq] }})</td>
                                        <td width="50%">
                                            <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}[1]" id="que_{{$kk}}_{{$qq}}B" value="" placeholder="Enter Matching Words">
                                        </td>
                                    </tr>
                                @else
                                <tr>
                                    <td width="2%"> {{ $qq }})</td>
                                    <td width="20%">
                                        <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}[0]" id="que_{{$kk}}_{{$qq}}A" value="" placeholder="Enter Matching Words">
                                    </td>
                                    <td width="2%"> {{ $alpha[$qq] }})</td>
                                    <td width="50%">
                                        <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}[1]" id="que_{{$kk}}_{{$qq}}B" value="" placeholder="Enter Matching Words">
                                    </td>
                                </tr>
                                @endif

                                @if($qncnt[$kk] == $qq)
                                    </table>
                                <br>
                                    <div class="form-text form-check-inline">Answer Key : <input type="text" name="3_0" style="background-color:#silver;" class="form-control" id="3_0" placeholder="Type Answer for Matching columns - Ex. 1-A, 2-B ..... etc">
                                        <br>
                                    </div>
                                @endif

                    </div>

                    @elseif($kk == 4)

                    <div class="form-group">
                        {{ $qq }}) &nbsp;&nbsp;&nbsp;
                        <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}" id="que_{{$kk}}_{{$qq}}" value="" placeholder="Enter Question No. {{$qq}}">
                    </div>
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" value="true" class="form-check-input" name="{{$kk}}_{{$qq}}" id="{{$kk}}_{{$qq}}">
                                True
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" value="false" class="form-check-input" name="{{$kk}}_{{$qq}}" id="{{$kk}}_{{$qq}}">
                                False
                            </label>
                        </div>
                    </div>

                    @elseif($kk == 5)

                    <div class="form-group">
                        <label> {{ $qq }}) &nbsp;&nbsp;&nbsp; </label>
                        <textarea id="summernote5{{$qq}}" value="" name="que_{{$kk}}_{{$qq}}"><br></textarea>
                        <input type="text" name="{{$kk}}_{{$qq}}" class="form-control" id="{{$kk}}_{{$qq}}" value="" placeholder="Type Answer for Qn. {{$qq}}">
                    </div>


                    @elseif($kk == 6)

                    <div class="form-group">
                        {{ $qq }}) &nbsp;&nbsp;&nbsp;
                        <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}" id="que_{{$kk}}_{{$qq}}" value="" placeholder="Enter Question No. {{$qq}}"><br>
                        <div id="row" class="form-check">
                        </div>

                        <div id="newinput-{{$qq}}">
                        </div>

                        <button alt="{{$qq}}" type="button" class="btn btn-dark rowAdder">
                            <span class="bi bi-plus-square-dotted">
                            </span>ADD
                        </button>
                        <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="button" alt="{{$qq}}" class="apply-rows" value="Apply Order"> -->

                        <br><br>

                        <div class="form-text form-check-inline">Answer Key :
                            <input type="text" placeholder="Answer by 1, 2, 3 " value="" class="form-control" name="{{$kk}}_{{$qq}}" id="{{$kk}}_{{$qq}}">
                        </div>

                    </div>


                    @elseif($kk == 7)

                    <br>{{ $qq }}) &nbsp;&nbsp;&nbsp;

                    <textarea class="form-control" id="summernote7{{$qq}}" name="que_{{$kk}}_{{$qq}}"><br></textarea>

                    <input type="file" name="mcqimg7_{{$qq}}[0]" placeholder="Attach Image if need...." class="form-control" accept=".gif,.jpg,.png,.ppt,.pptx,.jpeg,.bmp,.tiff">
                    <div class="form-group">
                        <div class="form-text form-check-inline">
                            @if(isset($optns[1]))
                            <input type="text" placeholder="Type Choice 1" class="form-control" name="ops_{{$kk}}_{{$qq}}a" id="{{$kk}}_{{$qq}}_a" value="{{ $optns[1] }}">
                            @else
                            <input type="text" placeholder="Type Choice 1" class="form-control" name="ops_{{$kk}}_{{$qq}}a" id="{{$kk}}_{{$qq}}_a" value=" ">
                            @endif
                            <input type="file" name="mcqimg7_{{$qq}}[1]" placeholder="Attach Image if need...." class="form-control" accept=".gif,.jpg,.png,.ppt,.pptx,.jpeg,.bmp,.tiff">
                        </div>

                        <div class="form-text form-check-inline">
                            @if(isset($optns[2]))
                            <input type="text" placeholder="Type Choice 2" class="form-control" name="ops_{{$kk}}_{{$qq}}b" id="{{$kk}}_{{$qq}}_b" value="{{ $optns[2] }}">
                            @else
                            <input type="text" placeholder="Type Choice 2" class="form-control" name="ops_{{$kk}}_{{$qq}}b" id="{{$kk}}_{{$qq}}_b" value="">
                            @endif
                            <input type="file" name="mcqimg7_{{$qq}}[2]" placeholder="Attach Image if need...." class="form-control" accept=".gif,.jpg,.png,.ppt,.pptx,.jpeg,.bmp,.tiff">
                        </div>
                        <div class="form-text form-check-inline">
                            @if(isset($optns[3]))
                            <input type="text" placeholder="Type Choice 3" class="form-control" name="ops_{{$kk}}_{{$qq}}c" id="{{$kk}}_{{$qq}}_c" value="{{ $optns[3] }}">
                            @else
                            <input type="text" placeholder="Type Choice 3" class="form-control" name="ops_{{$kk}}_{{$qq}}c" id="{{$kk}}_{{$qq}}_c" value=" ">
                            @endif
                            <input type="file" name="mcqimg7_{{$qq}}[3]" placeholder="Attach Image if need...." class="form-control" accept=".gif,.jpg,.png,.ppt,.pptx,.jpeg,.bmp,.tiff">
                        </div>
                        <div class="form-text form-check-inline">
                            @if(isset($optns[4]))
                            <input type="text" placeholder="Type Choice 4" class="form-control" name="ops_{{$kk}}_{{$qq}}d" id="{{$kk}}_{{$qq}}_d" value="{{ $optns[4] }}">
                            @else
                            <input type="text" placeholder="Type Choice 4" class="form-control" name="ops_{{$kk}}_{{$qq}}d" id="{{$kk}}_{{$qq}}_d" value=" ">
                            @endif
                            <input type="file" name="mcqimg7_{{$qq}}[4]" placeholder="Attach Image if need...." class="form-control" accept=".gif,.jpg,.png,.ppt,.pptx,.jpeg,.bmp,.tiff">
                        </div>
                        <div class="form-text form-check-inline">
                            @if(isset($optns[5]))
                            <input type="text" placeholder="Type Choice 5" class="form-control" name="ops_{{$kk}}_{{$qq}}e" id="{{$kk}}_{{$qq}}_e" value="{{ $optns[5] }}">
                            @else
                            <input type="text" placeholder="Type Choice 5" class="form-control" name="ops_{{$kk}}_{{$qq}}e" id="{{$kk}}_{{$qq}}_e" value=" ">
                            @endif
                            <input type="file" name="mcqimg7_{{$qq}}[5]" placeholder="Attach Image if need...." class="form-control" accept=".gif,.jpg,.png,.ppt,.pptx,.jpeg,.bmp,.tiff">
                        </div>
                        <div class="form-text form-check-inline">
                            @if(isset($optns[6]))
                            <input type="text" placeholder="Type Choice 6" class="form-control" name="ops_{{$kk}}_{{$qq}}f" id="{{$kk}}_{{$qq}}_f" value="{{ $optns[6] }}">
                            @else
                            <input type="text" placeholder="Type Choice 6" class="form-control" name="ops_{{$kk}}_{{$qq}}f" id="{{$kk}}_{{$qq}}_f" value=" ">
                            @endif
                            <input type="file" name="mcqimg7_{{$qq}}[6]" placeholder="Attach Image if need...." class="form-control" accept=".gif,.jpg,.png,.ppt,.pptx,.jpeg,.bmp,.tiff">
                        </div> <br><br>
                        <div class="form-text form-check-inline">Answer Key :
                            <input type="text" placeholder="Type Answer key by 1,2,3... ?" class="form-control" name="{{$kk}}_{{$qq}}" id="{{$kk}}_{{$qq}}" value=" ">
                        </div>
                    </div>

                    @elseif($kk == 8)

                    <div class="form-group">
                        <br>{{ $qq }}) &nbsp;&nbsp;&nbsp;

                        <div class="form-textarea form-check-inline" style="white-space: nowrap;">
                            <label for="exampleFormControlTextarea1">Add Jumbled Word</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-control" value=" " name="que_{{$kk}}_{{$qq}}-left" id="que_{{$kk}}_{{$qq}}-left"></input>

                        </div>

                        <div class="form-text form-check-inline" style="margin-left:30px; white-space: nowrap;">
                            <label class="form-check-label">Add Answer Key Word</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-control" name="{{$kk}}_{{$qq}}" id="{{$kk}}_{{$qq}}"></input>
                            </label>
                        </div>

                    </div>

                    @elseif($kk == 10)

                    <div class="form-group">
                        {{ $qq }} &nbsp;&nbsp;&nbsp;

                        <div class="form-textarea form-check-inline">
                            <label for="exampleFormControlTextarea1">Add LHS Words</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}-left" id="que_{{$kk}}_{{$qq}}-left" value="" placeholder="Enter Question of LHS">
                            </label>
                        </div>

                        <div class="form-textarea form-check-inline">
                            <label for="exampleFormControlTextarea1">Add RHS Words</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}-right" id="que_{{$kk}}_{{$qq}}-right" value="" placeholder="Enter Question of RHS">
                        </div>

                        <div class="form-text form-check-inline" style="margin-left:30px;">
                            <label class="form-check-label">Answer for LHS Words</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <textarea class="form-control" name="{{$kk}}_{{$qq}}-left" id="{{$kk}}_{{$qq}}-left" rows="5" cols="30"></textarea>
                            </label>
                        </div>

                        <div class="form-text form-check-inline" style="margin-left:30px;">
                            <label class="form-check-label">Answer for RHS Words</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <textarea class="form-control" name="{{$kk}}_{{$qq}}-right" id="{{$kk}}_{{$qq}}-right" rows="5" cols="30"></textarea>
                            </label>
                        </div>

                    </div>


                    @else
                    <div class="form-group">
                        <label> {{ $qq }}) &nbsp;&nbsp;&nbsp; </label>
                        <input type="text" class="form-control" name="que_{{$kk}}_{{$qq}}" id="que_{{$kk}}_{{$qq}}" value="" placeholder="Enter Question No. {{$qq}}">
                        <br>
                        <input type="text" name="{{$kk}}_{{$qq}}" class="form-control" id="{{$kk}}_{{$qq}}" value="" placeholder="Type Answer for Qn. {{$qq}}">
                    </div>
                    @endif

                    @endfor

            </div>

        </div>

        @endforeach

        <input style="margin-bottom:20px;" type=button value="Back" onClick="javascript:history.go(-1);">
        <br>
        <!-- <button type="submit" class="btn btn-primary">Save All Questions</button> -->
        <button type="submit" class="btn btn-primary id="submitForm">Submit</button>

    </div>

    </form>

</div>

</div>

@endsection

@push('plugin-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
@endpush

@push('custom-scripts')

<script>
    $(document).ready(function() {

        // $("#table-1").tableDnD();

        $('#summernote51').summernote();
        $('#summernote52').summernote();
        $('#summernote53').summernote();
        $('#summernote54').summernote();
        $('#summernote55').summernote();
        $('#summernote56').summernote();
        $('#summernote57').summernote();
        $('#summernote58').summernote();
        $('#summernote59').summernote();
        $('#summernote510').summernote();

        $('#summernote71').summernote();
        $('#summernote72').summernote();
        $('#summernote73').summernote();
        $('#summernote74').summernote();
        $('#summernote75').summernote();
        $('#summernote76').summernote();
        $('#summernote77').summernote();
        $('#summernote78').summernote();
        $('#summernote79').summernote();
        $('#summernote710').summernote();


        $('#match-apply').click(function() {
            // alert("UUUUUUUUUUUUUU");
            var ans3 = "";
            $("#table2 tr").each(function(i) {
                // find the first td in the row
                var value = $(this).find("td:first").text();
                // display the value in console
                ans3 = ans3 + ',' + value;
            });
            alert(ans3.substring(1));
            $('#ans-3qns').val(ans3.substring(1));
            return false;
        });

        $('.up').click(function() {
            var row = $(this).closest('tr');
            row.insertBefore(row.prev());
            return false;
        });

        $('.down').click(function() {
            var row = $(this).closest('tr');
            row.insertAfter(row.next());
            return false;
        });

        $("#class_id option[value=0]").prop('selected', true);
        $("#subject_id option[value=0]").prop('selected', true);
        $("#chapter_id option[value=0]").prop('selected', true);
        $("#mode_test option[value=0]").prop('selected', true);



        var jj = 0;
        var aa = 1;
        $(document).on("click", ".rowAdder", function() {
            var altValue = $(this).attr('alt');
            if(aa == altValue) {
                jj++;
            } else {
                jj=1;
            }
            // alert(altValue+"ZZZZZZZZZZZZZZZ");
            // return false;
            newRowAdd = '<div id="row" class="form-check"><span class="form-check-inline"><input type="text" id="ReOrd6_'+ altValue + '_' + jj + '" size="800" class="form-control" placeholder="Enter Sentence ..."><a class="btn btn-danger" id="DeleteRow"><i class="fas fa-trash"></i></a></span></div>';

            // $("#zzzzz").append(newRowAdd);
            $('#newinput-'+altValue).append(newRowAdd);
            // form2.appendChild(newRowAdd);
            // $('#form-wrapper').append(appendHTML);
            aa = altValue;

        });


        // Form submission
        $('#dynamicForm').submit(function(e) {
            e.preventDefault();

            var qnid = $("#qn_temp_id").attr('alt');

            key6_1text = ""; key61 = "";
            key6_2text = ""; key62 = "";
            key6_3text = ""; key63 = "";
            key6_4text = ""; key64 = "";
            key6_5text = ""; key65 = "";

            $('*[id*=ReOrd6_1]:visible').each(function() {
                key6_1text = $(this).val();
                key61 += key6_1text + "~~~~~";
            });
            // alert(key61);
            $('*[id*=ReOrd6_2]:visible').each(function() {
                key6_2text = $(this).val();
                key62 += key6_2text + "~~~~~";
            });
            // alert(key62);
            $('*[id*=ReOrd6_3]:visible').each(function() {
                key6_3text = $(this).val();
                key63 += key6_3text + "~~~~~";
            });
            // alert(key63);
            $('*[id*=ReOrd6_4]:visible').each(function() {
                key6_4text = $(this).val();
                key64 += key6_4text + "~~~~~";
            });
            // alert(key64);
            $('*[id*=ReOrd6_5]:visible').each(function() {
                key6_5text = $(this).val();
                key65 += key6_5text + "~~~~~";
            });
            // alert(key65);

            data6 = "&key6_1=" + key61 + "&key6_2=" + key62 + "&key6_3=" + key63 + "&key6_4=" + key64 + "&key6_5=" + key65;

            var datastring = $("#dynamicForm").serialize()+data6;
            // console.log(datastring);
            // alert(datastring);
            // return false;

                $.ajax({
                    type:"POST",
                    url: "{{ url('question/storeqns') }}",
                    async: false,
                    data: datastring,
                    success: function(response){
                        // console.log(response);
                        // return false;
                    }
                });

                // return false;

                window.location.href = "{{ url('question/index') }}";

        });

    });


    // var jj = 0;
    // var aa = 1;
    // $(document).on("click", ".rowAdder", function() {
    //     var altValue = $(this).attr('alt');
    //     if(aa == altValue) {
    //         jj++;
    //     } else {
    //         jj=1;
    //     }
    //     // alert(altValue+"ZZZZZZZZZZZZZZZ");
    //     // return false;
    //     newRowAdd = '<div id="row" class="form-check"><span class="form-check-inline"><input type="text" name="ReOrd_6_'+ altValue + '_' + jj + '" size="800" class="form-control" value="" placeholder="Enter Sentence ..."><a class="btn btn-danger" id="DeleteRow"><i class="fas fa-trash"></i></a></span></div>';

    //     // $("#zzzzz").append(newRowAdd);
    //     $('#newinput-'+altValue).append(newRowAdd);
    //     // form2.appendChild(newRowAdd);
    //     $('#form-wrapper').append(appendHTML);
    //     aa = altValue;

    // })

    $(document).on("click", "#DeleteRow", function() {
        $(this).parents("#row").remove();
    })

    $(document).on("click", ".apply-rows", function() {
        var altValue = $(this).attr('alt');
        var key6text = "";
        var key7text = "";

        alert(altValue);

        $('*[id*=ReOrd_6_'+altValue+']:visible').each(function() {
            key6text = $(this).val();
            key7text += key6text + "~~~~~";
        });
        alert(key7text);
        str6 = '<input type="text" id="key6_'+altValue+'" name="ReOrd6_'+altValue+'">';
        $("#zzzzz").append(str6);
        // return false;
    })


    $("input[type='radio']#mcqtype9a").change(function() {
        var mcqtype = $("#mcqtype9a:checked").val();
        // alert(mcqtype);
        if (mcqtype == 1) {
            $('.mcatype9text').show();
            $('.mcatype9img').hide();
        } else if (mcqtype == 2) {
            $('.mcatype9img').show();
            $('.mcatype9text').hide();
        }
    });


    $("input:checkbox.qntemplate").click(function() {
        var seltemp1 = "#qntemplate" + $(this).val() + "order";
        var seltemp2 = "#qntemplate" + $(this).val() + "noqns";
        var seltemp3 = "#qntemplate" + $(this).val() + "markque";
        // return false;
        if (!$(this).is(":checked")) {
            $(seltemp1).prop("disabled", true);
            $(seltemp1).val('0');
            $(seltemp2).prop("disabled", true);
            $(seltemp2).val('0');
            $(seltemp3).prop("disabled", true);
            $(seltemp3).val('0');
        } else {
            // alert('you are checked ... ' + $(this).val());
            $(seltemp1).removeAttr('disabled');
            $(seltemp2).removeAttr('disabled');
            $(seltemp3).removeAttr('disabled');
        }

    });

    $('#class_id').change(function() {
        // alert("UUUUUUUUUUU"); return false;
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
        $('#chapter_id').html('');
        $("#chapter_id option[value=0]").prop('selected', true);

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
