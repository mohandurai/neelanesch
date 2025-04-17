<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use DB;
use DataTables;
use Carbon\Carbon;

class CltController extends Controller
{
    //
    public function cltlist()
    {
        $data['cltlist'] = DB::table('student_class')->select('id','class')->where('school_id', '=', 1)->where( 'is_deleted', '=', '0')->get();

        foreach($data['cltlist'] as $clsid){
            // $chapters[$clsid->id] = DB::select("SELECT AA.id, BB.title, AA.file_path FROM `video_master` as AA, `content_master` as BB WHERE AA.id=BB.video_id AND AA.class_id = $clsid->id AND AA.type=1 ORDER BY BB.title");
            $chapters[$clsid->id] = DB::select("SELECT AA.id, CC.id as subjid, BB.title, AA.file_path FROM `video_master` as AA, `content_master` as BB, `subject_master` as CC WHERE CC.id=BB.subject_id AND AA.id=BB.video_id AND AA.class_id = $clsid->id AND AA.type=1 AND BB.video_type_id=1 ORDER BY BB.title");
            $data2[$clsid->id] = DB::select("SELECT DISTINCT(AA.id), AA.title FROM `subject_master` as AA, `content_master` as BB WHERE AA.id=BB.subject_id AND BB.video_type_id=1 AND BB.class_id = $clsid->id ORDER BY AA.title");
        }
        //echo "<pre>";
        //print_r($chapters);
        // echo "================================<br>";
        // print_r($data2);
        // echo "</pre>";
        //exit;
        return view('pages.training.cltkit',$data)->with('chapters', $chapters)->with('subjects', $data2);

    }

    public function getchapsubj($id)
    {
        $chapts = "";
        $res3 = DB::select("SELECT AA.id, BB.title, AA.file_path FROM `video_master` as AA, `content_master` as BB, `subject_master` as CC WHERE CC.id=BB.subject_id AND AA.id=BB.video_id AND BB.subject_id = $id AND AA.type=1 ORDER BY BB.title");

        foreach($res3 as $data3) {
            $chapts .= '<a href="#myModal" id="get-heading" alt="storage/videos/'. $data3->file_path . '" class="btn btn-primary geturl" data-toggle="modal">' . $data3->title . '</a><br><br>';
        }

        return $chapts;

    }

    public function datatab()
    {
        $videos = DB::table('student_class')->orderBy('created_date', 'desc');
        return datatables()->of($videos)
            ->addColumn('action',function($selected){
                return
                '<a class="btn btn-success" href="' . $selected->id . '/show" title="Detailed view of this Record">V</i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="Edit this Record" href="'.$selected->id.'/edit">E</i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="' . $selected->id . '/destroy" onclick="return confirmation();" title="Delete this Record">D</a>';
            })->toJson();

    }
}
