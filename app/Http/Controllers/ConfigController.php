<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use DB;
use File;
use DataTables;
use Carbon\Carbon;

class ConfigController extends Controller
{

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function config()
    {
        $data['configs'] = DB::table('config_setup')->where('id', 1)->first();
        // print_r($data);
        // exit;
        return View::make('pages.config', $data);
    }


    public function update(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit;

        $request->validate([
        'organization_name' => 'required',
        'address1' => 'required',
        'logo_url' => 'required'
        ]);

        if($request->file()) {
            $fileName = $request->logo_url->getClientOriginalName();
            $filePath = $request->file('logo_url')->storeAs('public/images',$fileName);
        }

        // echo $fileName . " <<<<<==========  " . $filePath;
        // exit;

        try {
            DB::table('config_setup')->where('id',1)->update(
                [
                    'organization_name'  =>   $request->organization_name,
                    'address1'   => $request->address1,
                    'address2'   =>   $request->address2,
                    'website_url'   =>   $request->website_url,
                    'email_id'   =>   $request->email_id,
                    'contact_phone1'   =>   $request->contact_phone1,
                    'contact_phone2'   =>   $request->contact_phone2,
                    'logo_url'   => $fileName,
                    'updated_at'=> Carbon::now(),
                ]
            );
        }

        catch (\Throwable $e) {
            print_r($e->getMessage());
            return View::make('pages.config')->with('message', "Some errrrrrrrrr already exists - Try different video name !!!");
        }

        return redirect()->route('config')->with('message', 'Configuration Setup updated successfully.');
    }


}
