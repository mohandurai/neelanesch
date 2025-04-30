<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
    </style>
</head>
<body>


@php($logourl = 'storage/images/' . $configs[0]->logo_url)

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">

        <table class="table2">
            <tr>
                <td align="center">
                    <img src="{{ public_path($logourl) }}" height="150em" width="200em" alt="" title="" />
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

        <table class="table2" cellspacing="3" cellpadding="3">
            <tr>
            <td style="font-size:12;">Homework Activity &nbsp;&nbsp;</td>
            <td>Home Work Title : &nbsp;&nbsp;</td>
                <td style="text-align: left;">{{$examtitle}}</td>
            </tr>
            <tr>
                <td> Class & Sec. : &nbsp;&nbsp;</td>
                <td>
                @if(isset($stud_data[0][4]) || isset($stud_data[0][5]))
                    {{$stud_data[0][4]}} & {{ $stud_data[0][5] }}
                @else
                    <p>Info. not available !!!</p>
                @endif
                </td>
            </tr>
        </table>

            <table style="margin-top: 20px;" border="1" cellspacing="10" cellpadding="10">
                <tr align="center">
                    <th align="center">S-No.</th>
                    <th align="center">Roll No.</th>
                    <th align="center">Student Name</th>
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

        </div>

    </div>
</div>


