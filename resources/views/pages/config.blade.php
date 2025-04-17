@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')

@php
    //echo "<pre>";
    //print_r($configs);
    //echo "</pre>";
    //exit;
@endphp

@php($logourl = "storage/images/" . $configs->logo_url)

<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/config') }}">School Setup Configuration</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('/config') }}">School Setup Configuration</a></li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card"`>
    <div class="card">
      <div class="card-body">
        <h4 style="margin-bottom:10px;">School Setup Configuration</h4>

            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
            @endif

        <form action="{{ url('config/update') }}" method="post" enctype="multipart/form-data">
            @csrf

        <div class="form-group">
          <table id="dataTableExample" class="table">

                <tr>
                    <td>School Name : </td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="organization_name" value="{{$configs->organization_name}}" required>
                    </td>
                </tr>
                <tr>
                    <td>Address 1</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="address1" value="{{$configs->address1}}" required>
                    </td>
                </tr>
                <tr>
                    <td>Address 2</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="address2" value="{{$configs->address2}}" required>
                    </td>
                </tr>
                <tr>
                    <td>Website</td><td>:</td><td>
                        <input type="text" class="form-control" name="website_url" value="{{$configs->website_url}}" required>
                    </td>
                </tr>
                <tr>
                    <td>EMail - ID</td><td>:</td><td>
                        <input type="text" class="form-control" name="email_id" value="{{$configs->email_id}}" required>
                    </td>
                </tr>
                <tr>
                    <td>Contact Phone 1</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="contact_phone1" value="{{$configs->contact_phone1}}" required>
                    </td>
                </tr>
                <tr>
                    <td>Contact Phone 2</td><td>:</td>
                    <td>
                        <input type="text" class="form-control" name="contact_phone2" value="{{$configs->contact_phone2}}" required>
                    </td>
                </tr>
                <tr>
                    <td>School Logo Image</td><td>:</td>
                    <td>
                        <img src="{{ asset($logourl) }}" style="border-radius:0%; width:300px;height:150px;"/>
                        <input type="file" name="logo_url" class="form-control" accept=".png,.jpg,.jpeg,.gif,.bmp">

                    </td>
                </tr>

                <tr>
                <td>Status</td><td>:</td>
                <select class="form-control" id="status" name="status">
                    <option value="1" selected>Active</option>
                    <option value="0">InActive</option>
                </select>
            </div>

          </table>

        </div>

       </div>
       <br>
                <button type="submit" class="btn btn-primary">Save Configuration</button>
           </form>

           <br><br>
           <input style="margin-top:20px;" type="button" value="Back" onClick="javascript:history.go(-1);">

    </div>
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
