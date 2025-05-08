<?php
namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use DB;
use File;
use DataTables;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;

class HomeworkController extends Controller
{
    //
    //
    public function index()
    {
        $data = DB::table('student_class')->select('id','class')->where('school_id', '=', 1)->where( 'is_deleted', '=', '0')->get();
        $result = $data->toArray();
        foreach($result as $arr) {
            $classArr[$arr->id] = $arr->class;
        }
        return view('pages.training.homework.index')->with('classlist',$classArr);
    }

    public function homeworklist()
    {
        // echo $id;
        // exit;
        $projlab = DB::select("select id, title, describe_activity, class_id, assign_to, attachment FROM homework ORDER BY id DESC");

        return datatables()->of($projlab)
        ->addColumn('action',function($selected){
            return
            '<a class="btn btn-success" href="' . $selected->id . '/show" title="Detailed view of this Record">V</i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="Edit this Record" href="#">E</i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="' . $selected->id . '/destroy" onclick="return confirmation();" title="Delete this Record">D</a>';
        })->toJson();
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['classlist'] = DB::table('student_class')->select('id','class')->where('school_id', '=', 1)->where( 'is_deleted', '=', '0')->get();

        // $data2 = DB::table('students')->select('user_id','first_name')->where( 'is_deleted', '=', '0')->get();
        // $result = $data2->toArray();
        // foreach($result as $arr) {
        //     $student[$arr->user_id] = $arr->first_name;
        // }
        return view('pages.training.homework.create', $data);
    }

    public function store(Request $request)
    {

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit;

        $request->validate([
            'title' => 'required'
        ]);


        if($request->file('files')) {

            $image = $request->file('files');
            $imageName = $request->title . "_" . $image->getClientOriginalName();
            $storeAt = "homework\class_". $request->class_id;
            $filePath = $request->file('files')->storeAs($storeAt, $imageName, 'public');

        } else {
            $imageName = "";
        }


        try {

            DB::table('homework')->insert(
                array(
                        'title'  => $request->title,
                        'describe_activity' => $request->describe_activity,
                        'attachment' => $imageName,
                        'class_id' => $request->class_id,
                        'sec_id' => $request->sec_id,
                        'subject_id' => $request->subject_id,
                        'chapter_id' => $request->chapter_id,
                        'assign_to' => $request->assign_to,
                        'max_marks' => $request->max_marks,
                        'created_date' => Carbon::now(),
                        'updated_date' => Carbon::now(),
                        'is_active' => 1
                )
            );
        }

        catch (\Throwable $e) {
            print_r($e->getMessage());
            return View::make('pages.training.homework.homeworkevaln')->with('message', "Some errrrrrrrrr already exists - Try different video name !!!");
        }

        return redirect()->route('homework.homeworkevaln')->with('message', "New Home Work Created Successfully !!!");

    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function homeworkindex()
    {
        $stud_id = auth()->user()->id;

        if($stud_id == 1) {
            $data['fullname'] = "Admin" . " " . "Central";
            $data['class'] = "NA";
            $data['sec'] = "-";
        } else {
            $qry3 = "select first_name, last_name, class_id, Section, id FROM students WHERE is_deleted = 0 AND user_id=".$stud_id;
            $loginfo = DB::select($qry3);
            $data['fullname'] = $loginfo[0]->first_name . " " . $loginfo[0]->last_name;
            $data['class'] = $loginfo[0]->class_id;
            $data['sec'] = $loginfo[0]->Section;
            $data['hw_roll_no'] = $loginfo[0]->id;
        }

        return View::make('pages.training.homework.homeworkindex', $data);
    }

    public function homework2list()
    {
        // echo $id;
        // exit;
        $stud_id = auth()->user()->id;
        $data3 = DB::table('students')->select('class_id','Section')->where( 'user_id', '=', $stud_id)->get();
        $clsid = $data3[0]->class_id;
        $secid = $data3[0]->Section;

        if($stud_id == 1) {
            $hwquery = DB::select("select id, title, class_id, sec_id, evaluator_status, mark_scored, max_marks, status FROM homework ORDER BY sec_id, id DESC");
        } else {
            $hwquery = DB::select("select id, title, class_id, sec_id, evaluator_status, mark_scored, max_marks, status FROM homework WHERE (class_id=$clsid) AND (sec_id = '$secid' OR sec_id = '0') ORDER BY sec_id, id DESC");
        }
        // echo $hwquery;
        // exit;

        return datatables()->of($hwquery)
        ->addColumn('action',function($selected){
            return
            '<a class="btn btn-success text-light" data-toggle="modal" id="mediumButton4" data-target="#mediumModal" data-attr="' . $selected->id . '/homeworksubmit" title="Submit Activity" alt="'. $selected->id . '" title="Click to Start Home Work Submit .....">Submit</i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="Detailed View Record" href="'.$selected->id.'/homeworkview">Show</i></a>';
        })->toJson();
    }

    public function homeworkview($id)
    {
        // echo $id;
        // exit;
        $data['projLabAct'] = DB::table('homework')->where('id', $id)->get()->first();
        return View::make('pages.training.homework.homeworkview', $data);

    }

    public function homeworksubmituser(Request $request)
    {

        $stud_id = auth()->user()->id;
        $class_id = DB::table('students')->select('id', 'class_id')->where('user_id', $stud_id)->get();
        $clsid = $class_id[0]->class_id;
        $roleid = $class_id[0]->id;

        $id = $request->exam_id;

        $data['homework'] = DB::table('homework')->where('id', $id)->get()->first();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // exit;

        $filetn = "storage/homework/" . $clsid . "/" . $data['homework']->attachment;

        $bbb = File::extension($filetn);

        // echo $bbb;
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // exit;

        return View::make('pages.training.homework.homeworksubmit', $data)->with('studid',$stud_id)->with('roleid',$roleid)->with('filetype',$bbb);
    }


    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function homeworkevaln()
    {
        return view('pages.training.homework.homeworkevaln');
    }

    public function homeworkevalnlist()
    {
        $stud_id = auth()->user()->id;
        // echo " User Id ==== " . $stud_id;
        // exit;
        $data3 = DB::table('students')->select('class_id','Section')->where( 'user_id', '=', $stud_id)->get();
        $clsid = $data3[0]->class_id;
        $secid = $data3[0]->Section;

        if($stud_id == 1) {
            $hwqry = "select id, title, class_id, sec_id, evaluator_status, mark_scored, evaluator_comments FROM homework ORDER BY id DESC";
        }
        else {
            $hwqry = "select id, title, class_id, sec_id, evaluator_status, mark_scored, evaluator_comments FROM homework WHERE (class_id=$clsid) AND (sec_id = '$secid' OR sec_id = '0') ORDER BY sec_id, id DESC";
        }

        // echo $hwqry;
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // exit;

        $projlab = DB::select($hwqry);

        return datatables()->of($projlab)
        ->addColumn('action',function($selected){
            return
            '<a class="btn btn-success" href="' . $selected->id . '/evaluate" title="Evaluate Activity">Evaluate</i></a>';
        })->toJson();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['projLabAct'] = DB::table('homework')->where('id', $id)->first();
        // print_r($data);
        // exit;
        return View::make('pages.training.homework.show', $data);
    }

    public function homeworksubmit($id)
    {
        $data['projLabAct'] = DB::table('homework')->where('id', $id)->first();
        // print_r($data);
        // exit;
        return View::make('pages.training.homework.homeworksubmit', $data);
    }

    public function homeworkfinish(Request $request)
    {

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit;

        $proj_id = $request->proj_id;
        $class_id = $request->class_id;
        $hw_roll_no = $request->hw_roll_no;

        $stud_id = $request->student_id;

        // $request->validate([
        //     'image_projlab_finish' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:212048',
        // ]);

        if( $request->file('image_projlab_finish') != '' )
        {
            // echo "Yessssssssssssssssssssssss";
            // exit;
            if($request->file('image_projlab_finish')) {

                $image = $request->file('image_projlab_finish');
                $imageName = "PID" . $proj_id . "_Student" . $stud_id . "_" . $image->getClientOriginalName();
                $storeAt = "homework\class_". $class_id;
                $filePath = $request->file('image_projlab_finish')->storeAs($storeAt, $imageName, 'public');
            }
            DB::table('homework')->where('id', $proj_id)->update(
                [
                    'student_submit_attach' => $imageName,
                    'student_id' => $stud_id,
                    'hw_roll_no' => $hw_roll_no,
                    'student_remarks' => $request->student_remarks,
                    'status' => $request->status,
                    'updated_date'=> Carbon::now()
                ]
            );
        }
        else {
            // echo "Nooooooooooooooooooooooooooo";
            // exit;
            try {
                DB::table('homework')->where('id', $proj_id)->update(
                    [
                        'student_remarks' => $request->student_remarks,
                        'status' => $request->status,
                        'updated_date'=> Carbon::now()
                    ]
                );
            }
                catch (\Throwable $e) {
                    print_r($e->getMessage());
                    return View::make('pages.training.homework.homeworkindex')->with('message', "Some errrrrrrrrr already exists - Try different Project Acvitity name !!!");
                }
        }


        return redirect()->route('homework.homeworkindex')->with('message', "Home Work Activity Submitted by Student Successfully !!!");

    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function evaluate($id)
    {
        $data['projLabEval'] = DB::table('homework')->where('id', $id)->first();
        // echo "<pre>";
        // $filetype = $data['projLabEval']->attachment;
        // $infoPath = pathinfo($filetype);
        // $ftype = $infoPath['extension'];

        // echo $ftype;
        // exit;

        return View::make('pages.training.homework.evaluate', $data);
    }


    public function evaluatefinish(Request $request)
    {

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit;

        try {
            DB::table('homework')
                ->where('id', $request->id)
                ->update(
                    [
                        'mark_scored' => $request->mark_scored,
                        'evaluator_comments' => $request->evaluator_comments,
                        'evaluator_status' => $request->evaluator_status
                ]);
        }

        catch (\Throwable $e) {
            print_r($e->getMessage());
            return View::make('pages.training.homework.evaluate')->with('message', "Some errrrrrrrrr already exists - Try different video name !!!");
        }

        return redirect()->route('homework.homeworkevaln')->with('message', "Home Work Activity Evaluated Successfully !!!");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('homework')->delete($id);

        return redirect()->route('homework.index')->with('message', 'Home Work Activity deleted successfully');
    }


    public function getAllHw($id)
    {
        $res6 = DB::select("select id, title FROM homework WHERE is_active = 1 AND class_id=$id");
        $options3 = "";
        foreach ($res6 as $data7) {
            $options3 .= "<option value='" . $data7->id . "'>" . $data7->title . "</option>";
        }
        return $options3;
    }

    public function getStudents3($id)
    {
        $clssec = explode("~~~~~",$id);

        $qry5 = "select user_id, CONCAT('Roll No. ', user_id, ' - ', first_name, ' ', last_name) as student_name FROM students WHERE is_deleted = 0 AND class_id=" . $clssec[0] . " AND Section='" . $clssec[1] . "'";
        // echo $qry5;
        // exit;
        $res6 = DB::select($qry5);

        $options2 = "<option value='0'>Select Student</option>";
        foreach ($res6 as $data3) {
            $options2 .= "<option value='" . $data3->user_id . "'>" . $data3->student_name . "</option>";
        }
        return $options2;
    }

    public function printreport(Request $request)
    {
        $setupInfo['configs'] = DB::table('config_setup')->where('id', 1)->get();

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit;

        $hwid = $request->hw_id;

        if(!isset($request->student_id))
        {
            $sql6 = "select C.id as roleid, CONCAT(C.first_name, ' ', C.last_name) as stname, D.class_id, D.sec_id, D.mark_scored, D.max_marks, D.title FROM students C, homework D WHERE D.is_active = 1 AND D.id = $hwid AND D.class_id=$request->class_id AND (C.Section = '" . $request->sec_id . "' OR D.sec_id = '0') AND D.student_id=C.user_id";
            // echo $sql6; exit;
            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
            // exit;
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->roleid;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark_scored;
                    $info3[$kk][3] = $data2->max_marks;
                    $info3[$kk][4] = $data2->class_id;
                    $info3[$kk][5] = $data2->sec_id;
                }
            } else {
                $info3 = [];
                $examtitle = "";
            }
        } else {
            $sql6 = "select C.id as roleid, CONCAT(C.first_name, ' ', C.last_name) as stname, D.class_id, D.sec_id, D.mark_scored, D.max_marks, D.title FROM students C, homework D WHERE D.is_active = 1 AND D.id = $hwid AND D.class_id=$request->class_id AND D.sec_id = '" . $request->sec_id . "' AND C.user_id = D.student_id AND C.user_id = $request->student_id  AND (D.assign_to=C.user_id OR D.student_id=C.user_id)";

            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->roleid;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark_scored;
                    $info3[$kk][3] = $data2->max_marks;
                    $info3[$kk][4] = $data2->class_id;
                }
            } else {
                $info3 = [];
                $examtitle = "";
            }
        }
        // echo $sql6;
        // echo $examtitle . "<br>";
        // echo "<pre>";
        // print_r($info3);
        // echo "</pre>";
        // exit;

        return view('pages.training.homework.printreport',$setupInfo)->with('stud_data',$info3)->with('examtitle',$examtitle)->with('tmplid',$hwid);
    }

    public function printpdf(Request $request)
    {
        $setupInfo['configs'] = DB::table('config_setup')->where('id', 1)->get();

        //echo "<pre>";
        // print_r($request->all());
        //print_r($setupInfo);
        //echo "</pre>";
        //exit;

        $tmplid = $request->qntemplateid;
        $classid = $request->class_id;

        if(!isset($request->student_id))
        {
            $sql6 = "select C.id as roleid, CONCAT(C.first_name, ' ', C.last_name) as stname, D.class_id, D.sec_id, D.mark_scored, D.max_marks, D.title FROM students C, homework D WHERE D.class_id=$classid AND D.id = $tmplid AND D.student_id = C.user_id";
            // echo $sql6; exit;
            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
            // exit;
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->roleid;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark_scored;
                    $info3[$kk][3] = $data2->max_marks;
                    $info3[$kk][4] = $data2->class_id;
                    $info3[$kk][5] = $data2->sec_id;
                }
            } else {
                $info3 = [];
                $examtitle = "";
            }
        } else {
            $sql6 = "select C.id as roleid, CONCAT(C.first_name, ' ', C.last_name) as stname, D.class_id, D.sec_id, D.mark_scored, D.max_marks, D.title FROM student_answers A, students C, homework D WHERE A.is_deleted = 0 AND A.que_master_templ_id = D.id AND C.user_id = A.student_id AND D.id=A.que_master_templ_id AND C.user_id=" . $request->student_id;
            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->roleid;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark_scored;
                    $info3[$kk][3] = $data2->max_marks;
                    $info3[$kk][4] = $data2->class_id;
                    $info3[$kk][5] = $data2->sec_id;
                }
            } else {
                $info3 = [];
                $examtitle = "";
            }
        }

        $examtitle = array('examtitle' => $examtitle);
        $inforec = array('stud_data' => $info3);

        $finalarray = array_merge($inforec, $setupInfo, $examtitle);

        // echo "<pre>";
        // print_r($finalarray);
        // echo "</pre>";
        // exit;

        // $pdf = Pdf::loadView('pages.training.olexam.printpdf');
        $pdf = Pdf::loadView('pages.training.homework.printpdf',$finalarray);

        // For direct download:
	    // return $pdf->download('document.pdf');

        // echo $sql6 . "<br>";
        // echo $examtitle . "<br>";

        return $pdf->stream('pages.training.homework.printpdf.pdf');
	}

}
