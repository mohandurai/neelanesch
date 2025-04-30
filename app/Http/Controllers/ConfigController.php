<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use DB;

use Carbon\Carbon;

class ConfigController extends Controller
{

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function configs()
    {
        $data['configs'] = DB::table('config_setup')->where('id', 1)->first();
        // print_r($data);
        // exit;
        return View::make('pages.configs', $data);
    }


    public function update(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // echo $id;
        // exit;

        // $request->validate([
        // 'organization_name' => 'required',
        // 'address1' => 'required',
        // 'logo_url' => 'required',
        // 'status' => 'required'
        // ]);

        if($request->file()) {
            $fileName = $request->logo_url->getClientOriginalName();
            $filePath = $request->file('logo_url')->storeAs('public/images',$fileName);
        } else {
            $fileName = "";
        }

        // echo $fileName . " <<<<<==========  ";
        // exit;

        DB::table('config_setup')->where('id',1)->update(
            [
                'organization_name'  =>   $request->organization_name,
                'address1'   => $request->address1,
                'address2'   =>   $request->address2,
                'website_url' =>   $request->website_url,
                'email_id'   =>   $request->email_id,
                'contact_phone1' =>   $request->contact_phone1,
                'contact_phone2' =>   $request->contact_phone2,
                'contact_phone2' =>   $request->contact_phone2,
                'status' => $request->status,
                'logo_url'   => $fileName,
                'updated_at'=> Carbon::now(),
            ]
        );


        return redirect()->route('configs')->with('message', 'Configuration Setup updated successfully.');
    }


}
