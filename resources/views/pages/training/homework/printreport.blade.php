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

@php
    //echo "<pre>";
    //print_r($configs);
    //echo "</pre>";
    //exit;
@endphp

@php($sections = array(1=>"A",2=>"B",3=>"C",4=>"D",5=>"E",6=>"F",7=>"G",8=>"H",9=>"I",10=>"J"))

@php($logourl = 'storage/images/' . $configs[0]->logo_url)

@section('content')


<div class="row" id="pdf_content">

    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

        <table class="table2">
                <tr>
                    <td align="center">
                        <img src="{{ asset($logourl) }}" height="150em" width="200em" alt="" title="" />
                    <br><br>
                    </td>
                    <td>
                        <h3>{{$configs[0]->organization_name}}</h3>
                        {{$configs[0]->address1}}  {{$configs[0]->address2}}
                        <br>Contact Details : {{$configs[0]->contact_phone1}} / {{$configs[0]->contact_phone2}}
                        <br>Website  : {{$configs[0]->website_url}}
                        <br>email : {{$configs[0]->email_id}}
                    </td>
                </tr>
        </table>

        <table width="90%" class="table2" cellspacing="10" cellpadding="10">
            <tr>
            <td style="color:white; font-size:20; margin-left: 50;"> HOME WORK ACTIVITY &nbsp;&nbsp;</td>
            <td style="text-align: right;"> Class & Sec. : &nbsp;&nbsp;</td>
                <td>
                @if(isset($stud_data[0][4]) || isset($stud_data[0][5]))
                    {{$stud_data[0][4]}} - {{ $stud_data[0][5] }}
                @else
                    <p>Info. not available !!!</p>
                @endif
                </td>
            <td style="text-align: right;">Home Work Title : &nbsp;&nbsp;</td>
                <td style="text-align: left;">{{$examtitle}}</td>
            </tr>
        </table>

            <table style="margin-top: 20px;" border="1" cellspacing="10" cellpadding="10">
                <tr align="center">
                    <th align="center">S-No.</th>
                    <th align="center">Roll No.</th>
                    <th align="center">Name</th>
                    <th align="center">Marks Scored</th>
                    <th align="center">Total Marks</th>
                    <th align="center">Percentage (%)</th>
                </tr>
                @php($mm=1)
                @foreach($stud_data as $kk => $stinfo)
                    @php($kk++)
                    <tr>
                        <td>{{$kk}}</td>
                        <td>{{$stinfo[0]}}</td>
                        <td>{{$stinfo[1]}}</td>
                        <td>{{$stinfo[2]}}</td>
                        <td>{{$stinfo[3]}}</td>
                        <td>
                            @php($percen = $stinfo[2]/$stinfo[3] * 100)
                            {{ str_replace(".00", "", number_format($percen, 2)) }}
                        </td>
                    </tr>
                @endforeach
            </table>


            <table border="0" cellspacing="30" cellpadding="30">
                <tr>
                    <td width="30%" align="center">
                        <input type="button" value="Back" onClick="javascript:history.go(-1);">
                    </td>
                    <td width="30%" align="center">
                        <input type="hidden" name="qntemplateid" value="{{ $tmplid }}">
                        <input type="hidden" name="class_id" value="{{ $stud_data[0][4] }}">
                        <!-- <button type="submit" class="btn btn-primary">Export PDF</button> -->
                        <button class="btn btn-primary" onclick="downloadAsPDF();" ><i class="fas fa-download" ></i> PDF</button>
                    </td>
                    <td width="30%" align="center">
                        <button class="btn btn-primary pull-right mr-10" onclick="downloadAsIMG();" ><i class="fas fa-download" ></i> IMAGE</button>
                    </td>
                </tr>
            </table>

        </div>

    </div>
</div>
</form>

@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/js/html2canvas/html2canvas.min.js') }}"></script>
<script src="{{ asset('assets/js/html2pdf/html2pdf.bundle.js') }}"></script>
@endpush


@push('custom-scripts')
<script type="text/javascript">

function downloadAsPDF()
{
//   alert("PDF Download AAAAA");
//   return false;
    // showLoader();
  var element = document.getElementById('pdf_content');
  var opt = {
    margin:       1,
    filename:     'consolidated_report <?=date("Y-m-d")?>.pdf',
    image:        { type: 'jpeg', quality: 0.98 },
    html2canvas:  { scale: 2 },
    jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
  };

// New Promise-based usage:
html2pdf().set(opt).from(element).save().then(function(){
  hideLoader();
});

}

function downloadAsIMG()
{
  // showLoader();

	html2canvas(document.getElementById("pdf_content")).then(function (canvas) {
	    var anchorTag = document.createElement("a");
			anchorTag.download = 'consolidated_report <?=date("Y-m-d")?>.jpg';
			anchorTag.href = canvas.toDataURL();
			anchorTag.target = '_blank';
			anchorTag.click();
		});

		 hideLoader();


}
</script>
@endpush
