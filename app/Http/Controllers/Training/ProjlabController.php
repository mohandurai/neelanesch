<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use DB;
use File;
use URL;
use DataTables;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;

class ProjlabController extends Controller
{
    //
    public function index()
    {
        $data['projlab'] = DB::select("select id, title, describe_activity, class_id, subject_id, chapter_id, attachment, max_marks FROM project_lab_activity ORDER BY id DESC");
        // print_r($projlab);
        // exit;

        $data2 = DB::table('student_class')->select('id','class')->where( 'is_deleted', '=', '0')->get();
        $result = $data2->toArray();
        foreach($result as $arr) {
            $classArr[$arr->id] = $arr->class;
        }
        return view('pages.training.projlab.index',$data)->with('classlist',$classArr);
    }

    public function projlablist()
    {
        // echo $id;
        // exit;
        $projlab = DB::select("select id, title, describe_activity, class_id, assign_to, attachment FROM project_lab_activity ORDER BY id DESC");

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
        return view('pages.training.projlab.create', $data);
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

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit;

        if($request->file('files')) {

            $image = $request->file('files');
            $imageName = $request->title . "_" . $image->getClientOriginalName();
            $storeAt = "project_activity\class_". $request->class_id;
            $filePath = $request->file('files')->storeAs($storeAt, $imageName, 'public');
        } else {
            $imageName = "";
        }

        try {

            DB::table('project_lab_activity')->insert(
                array(
                        'title'  => $request->title,
                        'describe_activity' => $request->describe_activity,
                        'attachment' => $imageName,
                        'class_id' => $request->class_id,
                        'sec_id' => $request->sec_id,
                        'subject_id' => $request->subject_id,
                        'chapter_id' => $request->chapter_id,
                        'max_marks' => $request->max_marks,
                        'created_date' => Carbon::now(),
                        'updated_date' => Carbon::now(),
                        'is_active' => 1
                )
            );
        }

        catch (\Throwable $e) {
            print_r($e->getMessage());
            return View::make('pages.training.projlab.projevaln')->with('message', "Some errrrrrrrrr already exists - Try different video name !!!");
        }

        return redirect()->route('projlab.projevaln')->with('message', "New Project/Lab Activity Created Successfully !!!");

    }

        /**
         * Show the form for creating a new post.
         *
         * @return \Illuminate\Http\Response
         */
        public function studprojindex()
        {
            $stud_id = auth()->user()->id;

            if($stud_id == 1) {
                $data['fullname'] = "Admin" . " " . "Central";
                $data['class'] = "0";
                $clssid = "0";
                $data['sec'] = "0";
                $secid = "0";
                $data['proj_roll_no'] = 1;
            } else {
                $qry3 = "select first_name, last_name, class_id, Section, id FROM students WHERE is_deleted = 0 AND user_id=".$stud_id;
                $loginfo = DB::select($qry3);
                $data['fullname'] = $loginfo[0]->first_name . " " . $loginfo[0]->last_name;
                $clssid = $loginfo[0]->class_id;
                $data['class'] = $clssid;
                $secid = $loginfo[0]->Section;
                $data['sec'] = $secid;
                $data['proj_roll_no'] = $loginfo[0]->id;
            }

            // echo $stud_id;
            // exit;
            // $data3 = DB::table('students')->select('class_id','Section')->where('user_id', '=', $stud_id)->get();
            // $clsid = $data3[0]->class_id;
            // $secid = $data3[0]->Section;

            $stuQry = "select id, title, class_id, sec_id, subject_id, chapter_id, max_marks FROM project_lab_activity WHERE (sec_id = '$secid' OR sec_id = '0') AND is_active=1 ORDER BY sec_id, id DESC";
            // print_r($stuQry);
            // exit;

            $data['projLabAct'] = DB::select($stuQry);

            return View::make('pages.training.projlab.studprojindex', $data);
        }

        public function studprojlist()
        {
            // echo $id;
            // exit;
            $stud_id = auth()->user()->id;

            if($stud_id == 1) {
                $data['fullname'] = "Admin" . " " . "Central";
                $data['class'] = "0";
                $clssid = "0";
                $data['sec'] = "0";
                $secid = "0";
                $data['proj_roll_no'] = 1;
            }  else {
                $qry3 = "select first_name, last_name, class_id, Section, id FROM students WHERE is_deleted = 0 AND user_id=".$stud_id;
                $loginfo = DB::select($qry3);
                $data['fullname'] = $loginfo[0]->first_name . " " . $loginfo[0]->last_name;
                $clssid = $loginfo[0]->class_id;
                $data['class'] = $clssid;
                $secid = $loginfo[0]->Section;
                $data['sec'] = $secid;
                $data['proj_roll_no'] = $loginfo[0]->id;
            }

            // $data3 = DB::table('students')->select('class_id','Section')->where( 'user_id', '=', $stud_id)->get();
            // $clsid = $data3[0]->class_id;
            // $secid = $data3[0]->Section;

            if($stud_id == 1) {
                $projlab6 = DB::select("select id, title, class_id, sec_id, subject_id, chapter_id, max_marks FROM project_lab_activity ORDER BY class_id, id DESC");
            } else {
                $projlab6 = DB::select("select id, title, class_id, sec_id, subject_id, chapter_id, max_marks FROM project_lab_activity ORDER BY class_id DESC");
            }

            // echo $projlab2;
            // exit;

            return datatables()->of($projlab6)
            ->addColumn('action',function($selected){
                return
                '<div class="col-lg-12 margin-tb"><div class="pull-right"> <a class="btn btn-success text-light" data-toggle="modal" id="mediumButton3" data-target="#mediumModal" data-attr="'. $selected->id . '/studsubmit" title="Submit Activity"  alt="'. $selected->id . '" title="Click to Start Project Submit .....">Submit</i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="Detailed View Record" href="'.$selected->id.'/studview">Show</i></a>';
            })->toJson();
        }

        public function studview($id)
        {
            // echo $id;
            // exit;
            $data['projLabAct'] = DB::table('project_lab_activity')->where('id', $id)->get()->first();
            return View::make('pages.training.projlab.studview', $data);

        }

        public function projsubmituser(Request $request)
        {

            // echo "<pre>";
            // print_r($request->all());
            // echo "</pre>";
            // exit;

            // $stud_id = auth()->user()->id;
            // $class_id = DB::table('students')->select('class_id')->where('user_id', $stud_id)->get();
            // $clsid = $class_id[0]->class_id;

            $pid = $request->exam_id;
            $studRoleId = $request->proj_roll_no;
            $stud_name = $request->stud_name;

            // $numRecs = DB::table('project_lab_activity_submit')->where('proj_roll_no', '=', $studRoleId)->where('proj_activity_id', '=', $pid)->count();

            // echo $numRecs . "  <br>";
            // exit;

            // if($numRecs >= 1) {
            //     return redirect()->route('projlab.studprojindex')->with('message', "You have already submitted this Project/Lab Activity !!!");
            //     exit;
            // }

            $data['projLabAct'] = DB::table('project_lab_activity')->where('id', $pid)->get()->first();

            $filetn = "storage/project_activity/" . $data['projLabAct']->class_id . "/" . $data['projLabAct']->attachment;

            $bbb = File::extension($filetn);

            $studSubmitImage = DB::table('project_lab_activity_submit')->select('student_submit_attach','student_remarks','student_status')->where('proj_roll_no', $studRoleId)->where('proj_activity_id', $pid)->get()->first();

            // echo "<pre>";
            // print_r($studSubmitImage);
            // echo "</pre>";
            // exit;

            // echo $studSubmitImage->student_remarks . " ZZZZZZZZZZZZZZZ <br";
            // exit;

            if(!empty($studSubmitImage)) {
                $studSubImg = $studSubmitImage->student_submit_attach;
                // $ccc = File::extension($studSubImg);
                $stuRem = $studSubmitImage->student_remarks;
                $studStat = $studSubmitImage->student_status;
            } else {
                $studSubImg = "";
                $stuRem = "";
                $studStat = "";
                // $ccc = "";
            }

            return View::make('pages.training.projlab.studsubmit', $data)->with('studid',$studRoleId)->with('stud_name',$stud_name)->with('filetype',$bbb)->with('studSubImg2',$studSubImg)->with('studRem',$stuRem)->with('studStat',$studStat);
        }


        /**
         * Show the form for creating a new post.
         *
         * @return \Illuminate\Http\Response
         */

        public function projevaln2($id)
        {
            return view('pages.training.projlab.projevaln2');
        }

        public function projlabeval2list()
        {
            $projlab = DB::select("select id, title, class_id, sec_id, subject_id, chapter_id, max_marks FROM project_lab_activity ORDER BY id DESC");

            return datatables()->of($projlab)
            ->addColumn('action',function($selected){
                return
                '<a class="btn btn-success" href="' . $selected->id . '/show" title="Detailed view of this Record"><i class="fas fa-eye"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="Edit this Record" href="'.$selected->id.'/edit"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" title="Delete this Record" href="'.$selected->id.'/delete"><i class="fas fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-success" href="' . $selected->id . '/evaluate2" title="Evaluate Activity">Evaluate</i></a>';
            })->toJson();
        }

        public function projevaln()
        {
            return view('pages.training.projlab.projevaln');
        }

        public function projlabevallist()
        {
            $projlab = DB::select("select id, title, class_id, sec_id, subject_id, chapter_id, max_marks FROM project_lab_activity WHERE is_active=1 ORDER BY id DESC");

            return datatables()->of($projlab)
            ->addColumn('action',function($selected){
                return
                '<a class="btn btn-success" href="' . $selected->id . '/show" title="Detailed view of this Record"><i class="fas fa-eye"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="Edit this Record" href="'.$selected->id.'/edit"><i class="fas fa-edit"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" title="Delete this Record" href="'.$selected->id.'/delete"><i class="fas fa-trash"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-success" href="' . $selected->id . '/evaluate2" title="Evaluate Activity">Proceed</i></a>';
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
            $data['projLabAct'] = DB::table('project_lab_activity')->where('id', $id)->first();
            // print_r($data);
            // exit;
            return View::make('pages.training.projlab.show', $data);
        }

        public function studsubmit($id)
        {
            $data['projLabAct'] = DB::table('project_lab_activity')->where('id', $id)->first();
            // print_r($data);
            // exit;
            return View::make('pages.training.projlab.studsubmit', $data);
        }

        public function projlabfinish(Request $request)
        {

            // echo "<pre>";
            // print_r($request->all());
            // echo "</pre>";
            // exit;

            $proj_id = $request->proj_id;
            $class_id = $request->class_id;
            $stud_roll_no = $request->student_id;
            $stud_name = $request->stud_name;

            $numRecs = DB::table('project_lab_activity_submit')->where('proj_roll_no', '=', $stud_roll_no)->where('proj_activity_id', '=', $proj_id)->count();

            // echo $numRecs . "<<<<========= <br>";
            // exit;

            // $request->validate([
            //     'image_projlab_finish' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:212048',
            // ]);

            if($numRecs >= 1) {

            // echo "Yessssssss Updating query .......... loop";
            // exit;

                if( $request->file('image_projlab_finish') != '' )
                {
                    // echo "Yessssssssssssssssssssssss";
                    // exit;
                    if($request->file('image_projlab_finish')) {

                        $image = $request->file('image_projlab_finish');
                        $imageName = "PID" . $proj_id . "_" . $stud_roll_no . "_" . $image->getClientOriginalName();
                        $storeAt = "project_activity\class_". $class_id;
                        $filePath = $request->file('image_projlab_finish')->storeAs($storeAt, $imageName, 'public');
                    }
                    DB::table('project_lab_activity_submit')->where('proj_roll_no', $stud_roll_no)->where('proj_activity_id', $proj_id)->update(
                        [
                            'student_submit_attach' => $imageName,
                            'student_name' => $stud_name,
                            'student_remarks' => $request->student_remarks,
                            'student_status' => $request->status,
                            'updated_date'=> Carbon::now()
                        ]
                    );
                }
                else {
                    // echo "Nooooooooooooooooooooooooooo";
                    // exit;
                    try {
                        DB::table('project_lab_activity_submit')->where('proj_roll_no', $stud_roll_no)->where('proj_activity_id', $proj_id)->update(
                            [
                                'student_submit_attach' => '',
                                'student_name' => $stud_name,
                                'student_remarks' => $request->student_remarks,
                                'student_status' => $request->status,
                                'updated_date'=> Carbon::now()
                            ]
                        );
                    }
                        catch (\Throwable $e) {
                            print_r($e->getMessage());
                            return View::make('pages.training.projlab.studprojindex')->with('message', "Some errrrrrrrrr already exists - Try different Project Acvitity name !!!");
                        }
                }


            } else {

                // echo "Nooooooooooo Inserting query .......... loop";
                // exit;

                if( $request->file('image_projlab_finish') != '' )
                {
                    // echo "Yessssssssssssssssssssssss";
                    // exit;
                    if($request->file('image_projlab_finish')) {

                        $image = $request->file('image_projlab_finish');
                        $imageName = "PID" . $proj_id . "_" . $stud_roll_no . "_" . $image->getClientOriginalName();
                        $storeAt = "project_activity\class_". $class_id;
                        $filePath = $request->file('image_projlab_finish')->storeAs($storeAt, $imageName, 'public');
                    }
                    DB::table('project_lab_activity_submit')->insert(
                        array(
                                'proj_activity_id'  => $proj_id,
                                'proj_roll_no' => $stud_roll_no,
                                'student_name' => $stud_name,
                                'student_submit_attach' => $imageName,
                                'student_remarks'  => $request->student_remarks,
                                'student_status'  => $request->status,
                                'created_date' => Carbon::now(),
                                'updated_date' => Carbon::now()
                        )
                    );
                }
                else {
                    // echo "Nooooooooooooooooooooooooooo";
                    // exit;
                    try {
                        DB::table('project_lab_activity_submit')->insert(
                        array(
                                'proj_activity_id' => $proj_id,
                                'proj_roll_no' => $stud_roll_no,
                                'student_name' => $stud_name,
                                'student_submit_attach' => '',
                                'student_remarks' => $request->student_remarks,
                                'student_status' => $request->status,
                                'created_date' => Carbon::now(),
                                'updated_date' => Carbon::now()
                            )
                        );
                    }
                    catch (\Throwable $e) {
                        print_r($e->getMessage());
                        return View::make('pages.training.projlab.studprojindex')->with('message', "Some errrrrrrrrr already exists - Try different Project Acvitity name !!!");
                    }
                }


            }


            return redirect()->route('projlab.studprojindex')->with('message', "Project/Lab Activity Submitted by Student Successfully !!!");

        }

        /**
         * Show the form for creating a new post.
         *
         * @return \Illuminate\Http\Response
         */
        public function evaluate(Request $request, $id)
        {
            $getStSubId = explode("/",$request->url());

            // echo "<pre>";
            $aaa = count($getStSubId);
            $idProj = $getStSubId[$aaa-3];
            $idStud = $getStSubId[$aaa-2];
            // exit;

            $class_id = DB::table('project_lab_activity')->select('title', 'max_marks','attachment','class_id')->where('id', $idProj)->get();
            $title = $class_id[0]->title;
            $max_marks = $class_id[0]->max_marks;
            $attachment = $class_id[0]->attachment;
            if($attachment != "")
                $attachment = $class_id[0]->attachment;
            else {
                $attachment = "";
            }
            $clsid = $class_id[0]->class_id;
            // echo $title;
            // echo "<br>";
            // echo $max_marks;
            // exit;
            $data['studSubProjval'] = DB::table('project_lab_activity_submit')->where('id', $idStud)->first();
            // echo "<pre>";
            // print_r($data);
            // exit;
            return View::make('pages.training.projlab.evaluate', $data)->with('title',$title)->with('max_marks',$max_marks)->with('attach_file',$attachment)->with('class_id',$clsid);
        }

        public function evaluate2($id)
        {
            $data['projLabStudSub'] = DB::table('project_lab_activity_submit')->where('proj_activity_id', $id)->first();
            // print_r($data);
            // exit;
            return View::make('pages.training.projlab.evaluate2', $data);
        }

        public function liststudsubmit($id)
        {
            // echo $id;
            // exit;
            $subprojlab = DB::select("select id, student_name, proj_roll_no, student_submit_attach, mark_scored, max_marks, student_status FROM project_lab_activity_submit WHERE proj_activity_id=$id ORDER BY id DESC");

            return datatables()->of($subprojlab)
            ->addColumn('action',function($selected){
                return
                '<a class="btn btn-success" alt="' . $selected->student_status . '" id="checkFinish" href="' . $selected->id . '/evaluate" title="Evaluate student submitted Project .....">Evaluate</i></a>';
            })->toJson();
        }


        public function evaluatefinish(Request $request)
        {

            // echo "<pre>";
            // print_r($request->all());
            // echo "</pre>";
            // exit;

            try {
                DB::table('project_lab_activity_submit')
                    ->where('id', $request->id)
                    ->update(
                        [
                            'mark_scored' => $request->mark_scored,
                            'max_marks' => $request->max_marks,
                            'evaluator_comments' => $request->evaluator_comments,
                            'evaluator_status' => $request->evaluator_status
                    ]);
            }

            catch (\Throwable $e) {
                print_r($e->getMessage());
                return View::make('pages.training.projlab.evaluate')->with('message', "Some errrrrrrrrr already exists - Try different video name !!!");
            }

            return redirect()->route('projlab.projevaln')->with('message', "Project/Lab Activity Evaluated Successfully !!!");

        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            DB::table('project_lab_activity')->delete($id);

            return redirect()->route('projlab.index')->with('message', 'Project Lab Activity deleted successfully');
        }



        public function getprojlab($id2)
        {
            $aaa = explode("~~~~~",$id2);

            $options2 = "";
            $projlab = DB::select("select id, title, class_id, sec_id, subject_id, chapter_id, max_marks FROM project_lab_activity ORDER BY id DESC");
            $res3 = DB::select("SELECT id, title FROM `chapters` WHERE class_id = $aaa[0] AND subject_id=$aaa[1] ORDER BY title");

            foreach($res3 as $data3) {
                $options2 .= "<option value='".$data3->id . "'>" . $data3->title ."</option>";
            }
            return $options2;
        }

        public function getprojchapt($ids)
        {
            $id = explode("~~~", $ids);
            // echo $id[0] . "   " . $id[1];
            // exit;
            $qry6 = "select CC.id as id, CC.title as title from subject_master as AA, content_master as BB, chapters as CC WHERE AA.id=$id[0] AND CC.class_id=$id[1] AND BB.subject_id=$id[0] AND  BB.chapter_id=CC.id AND BB.video_type_id=1 AND AA.is_active = 1 ORDER BY BB.title";
            // echo $qry6; exit;
            $subs = DB::select($qry6);
            $options = "";
            foreach ($subs as $data2) {
                $options .= "<option value='" . $data2->id . "'>" . $data2->title . "</option>";
            }
            // echo $options; exit;
            return $options;
        }

        public function getAllprojLab($id)
        {
            $res6 = DB::select("select id, title FROM project_lab_activity WHERE is_active = 1 AND class_id=$id");
            $options3 = "";
            foreach ($res6 as $data7) {
                $options3 .= "<option value='" . $data7->id . "'>" . $data7->title . "</option>";
            }
            return $options3;
        }

        public function getallstudents($id)
        {
            $res2 = DB::select("select proj_roll_no, student_name FROM project_lab_activity_submit WHERE is_active = 1 AND proj_activity_id=$id");
            $options2 = "";
            foreach ($res2 as $res2a) {
                $options2 .= "<option value='" . $res2a->proj_roll_no . "'>" .  $res2a->proj_roll_no . " - " .$res2a->student_name . "</option>";
            }
            return $options2;
        }



        public function getStudents2($id)
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

            $projlabid = $request->projlab_id;
            $studrollid = $request->studroll_id;

            if($request->report_type == 1) {
                $sql6 = "select D.proj_roll_no as roleid, D.student_name as stname, D.mark_scored, D.max_marks, A.title, A.class_id, A.sec_id FROM  project_lab_activity_submit D, project_lab_activity A WHERE A.id=D.proj_activity_id AND D.proj_activity_id = $projlabid AND D.proj_roll_no = '" . $studrollid . "' AND D.is_active = 1";
            } else {
                $sql6 = "select D.proj_roll_no as roleid, D.student_name as stname, D.mark_scored, D.max_marks, A.title, A.class_id, A.sec_id FROM  project_lab_activity_submit D, project_lab_activity A WHERE A.id=D.proj_activity_id AND D.proj_activity_id = $projlabid AND D.is_active = 1";
            }

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

            // echo $sql6 . "<br>";
            // echo $examtitle . "<br>";
            // echo "<pre>";
            // print_r($info3);
            // echo "</pre>";
            // exit;

            return view('pages.training.projlab.printreport',$setupInfo)->with('stud_data',$info3)->with('examtitle',$examtitle)->with('tmplid',$projlabid);
        }


        public function printpdf(Request $request)
        {
            $setupInfo['configs'] = DB::table('config_setup')->where('id', 1)->get();

            // echo "<pre>";
            // print_r($request->all());
            // echo "</pre>";
            // exit;

            $tmplid = $request->qntemplateid;
            $classid = $request->class_id;

            if(!isset($request->student_id))
            {
                $sql6 = "select C.id as roleid, CONCAT(C.first_name, ' ', C.last_name) as stname, D.class_id, D.sec_id, D.mark_scored, D.max_marks, D.title FROM students C, project_lab_activity D WHERE D.class_id=$classid AND D.id = $tmplid AND D.student_id = C.user_id";
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
                $sql6 = "select C.user_id as roleid, CONCAT(C.first_name, ' ', C.last_name) as stname, D.class_id, C.Section, D.mark_scored, D.max_marks, D.title FROM student_answers A, students C, project_lab_activity D WHERE A.is_deleted = 0 AND A.que_master_templ_id = D.id AND C.user_id = A.student_id AND D.id=A.que_master_templ_id AND C.user_id=" . $request->student_id;
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
            $pdf = Pdf::loadView('pages.training.projlab.printpdf',$finalarray);

            // echo $sql6 . "<br>";
            // echo $examtitle . "<br>";


            return $pdf->stream('pages.training.projlab.printpdf.pdf');
        }

}
