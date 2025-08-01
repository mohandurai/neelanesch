<?php

namespace App\Http\Controllers\Training;

date_default_timezone_set("Asia/Calcutta");

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Auth;
use DB;
use DataTables;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;

class OlexamController extends Controller
{
    //
    public function index()
    {
        $login_id = Auth::user()->id;

        // echo $login_id;
        // exit;

        if($login_id == 1) {
            $data['fullname'] = "Admin" . " " . "Central";
            $data['class'] = "NA";
            $data['sec'] = "-";
            $data['roleid'] = 1;
        } else {
            $qry3 = "select first_name, last_name, class_id, Section, id FROM students WHERE is_deleted = 0 AND user_id=".$login_id;
            $loginfo = DB::select($qry3);
            $data['fullname'] = $loginfo[0]->first_name . " " . $loginfo[0]->last_name;
            $data['class'] = $loginfo[0]->class_id;
            $data['sec'] = $loginfo[0]->Section;
            $data['roleid'] = $loginfo[0]->id;
        }

        return view('pages.training.olexam.index', $data);
    }

    public function examslist()
    {

        // Store datetime in variable today
        $today = date("Y-m-d H:i:s");

        // if ($today) {
        //     echo $today;
        // } else {
        //     echo "can't display time";
        // }


        $qry6 = "select id, test_title, qn_master_templ_id, class_id, sec_id, start_date, end_date, duration FROM allocate_test WHERE is_active = 1 AND start_date <= '$today' AND end_date >= '$today' ORDER BY id DESC";

        // echo $qry6;
        // exit;

        $subs = DB::select($qry6);

        return datatables()->of($subs)
            ->addColumn('action', function ($selected) {
                return
                    '<div class="col-lg-12 margin-tb"><div class="pull-right"> <a class="btn btn-success text-light" data-toggle="modal" id="mediumButton2" data-target="#mediumModal" data-attr="'. $selected->id . '/attendexam" alt="'. $selected->id . "~~~~~" . $selected->sec_id . "~~~~~" . $selected->class_id . '" title="Click to Start Exam ....."> Start <i class="fas fa-book-reader"></i> </a> </div>';
            })->toJson();

    }

    public function attendexam(Request $request)
    {

        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit;

        $examid = $request->exam_id;
        $roleid = $request->rollno;
        $stud_name = $request->stud_name;
        $sectn = $request->Section;

        $numRecs = DB::table('student_answers')->where('exam_roll_no', '=', $roleid)->where('alloc_test_id', '=', $examid)->count();
        // echo $numRecs . "  <br>";
        // exit;
        if($numRecs >= 1) {
            return redirect()->route('olexam.index')->with('message', "You have already attending this Exam !!!");
            exit;
        }

        $result3 = DB::select("select test_title, qn_master_templ_id, duration, start_date, end_date, correction_type FROM allocate_test WHERE is_active = 1 AND id=$examid");
        $examtitle = $result3[0]->test_title;
        $qnmastid = $result3[0]->qn_master_templ_id;
        $examDur = $result3[0]->duration;
        $stdt = $result3[0]->start_date;
        $enddt = $result3[0]->end_date;
        $correctType = $result3[0]->correction_type;

        $now = date("Y-m-d h:i:sa");

        function isBetween($from, $till, $input) {
            $fromTime = strtotime($from);
            $toTime = strtotime($till);
            $inputTime = strtotime($input);

            return($inputTime >= $fromTime and $inputTime <= $toTime);
        }

        $checkExamDt = isBetween($stdt, $enddt, $now);

        if($checkExamDt == false) {
            return redirect()->route('olexam.index')->with('message', "Allocated Exam Date not Valid .... Please Check in Allocation of Exam !!!");
        }

        // echo $qnmastid . " MMMMMMMMMM   " . $stud_id;
        // exit;

        $result2 = DB::select("select temp_questions, image_qns_ans from question_master_qns_ans WHERE is_active = 1 AND qn_master_temp_id=$qnmastid");
        // echo "<pre>";
        // print_r($result2);
        // echo "</pre>";
        // echo "sSSSSSSSSSSSSSS";
        // exit;

        if(empty($result2[0]->temp_questions))
        {
            return redirect()->route('olexam.index')->with('message', "Question & Answered not prepared for this Template .... Pleae Check in Question Master !!!");
        }

        $res = json_decode($result2[0]->temp_questions, true);
        $imgQue = json_decode($result2[0]->image_qns_ans, true);

        // echo "<pre>";
        // print_r($res);
        // echo "</pre>";
        // exit;


        $temp = "";
        foreach ($res as $key => $qns) {
            if($key == "AAA") {
                $reord6 = $qns;
                continue;
            } else { $reord6 = ""; }

            $qtype = explode("_", $key);
            if ($qtype[0] != $temp) {
                if(str_contains($qtype[0], "ReOrd6") || str_contains($qtype[0], "left") || str_contains($qtype[0], "right")  || str_contains($qtype[0], "AAA")) {
                    continue;
                } else {
                    $qry6 = "select title from question_master_title WHERE is_active = 1 AND id=$qtype[0]";
                    // echo $qry6;
                    // exit;
                    $res3 = DB::select($qry6);
                    $QnsTitle[$qtype[0]] = $res3[0]->title;
                }
             }
            $temp = $qtype[0];

            if ($qtype[0] == 7) {
                $type7 = explode("~~~~~", $qns);


                if (isset($type7[0])) {
                    $Qns[$qtype[0]][$qtype[1]][0] = $type7[0];
                }
                if (isset($type7[1])) {
                    $Qns[$qtype[0]][$qtype[1]][1] = $type7[1];
                }
                if (isset($type7[2])) {
                    $Qns[$qtype[0]][$qtype[1]][2] = $type7[2];
                }
                if (isset($type7[3])) {
                    $Qns[$qtype[0]][$qtype[1]][3] = $type7[3];
                }
                if (isset($type7[4])) {
                    $Qns[$qtype[0]][$qtype[1]][4] = $type7[4];
                }
                if (isset($type7[5])) {
                    $Qns[$qtype[0]][$qtype[1]][5] = $type7[5];
                }
                if (isset($type7[6])) {
                    $Qns[$qtype[0]][$qtype[1]][6] = $type7[6];
                }
                if (isset($type7[7])) {
                    $Qns[$qtype[0]][$qtype[1]][7] = $type7[7];
                }
            } elseif ($qtype[0] == 10) {

                // echo "<pre>";
                // print_r($qns);
                // exit;
                for($kk=1; $kk <= 5; $kk++) {
                    if(isset($qns["Qleft_10"][$kk])) {
                        $Q10[$kk] = $qns["Qleft_10"][$kk] . "~~~~~" . $qns["Qright_10"][$kk];
                    } else {
                        continue;
                    }
                }
                $Qns[10] = $Q10;

            } elseif ($qtype[0] == 3) {
                $Qns[3]['qns'][] = $qns;

            } else {
                $Qns[$qtype[0]][$qtype[1]] = $qns;
            }
        }

        $romanLetters = array(1 => "I", 2 => "II", 3 => "III", 4 => "IV", 5 => "V", 6 => "VI", 7 => "VII", 8 => "VIII", 9 => "IX", 10 => "X", 11 => "XI", 12 => "XII", 13 => "XIII", 14 => "XIV", 15 => "XV", 16 => "XVI", 17 => "XVII", 18 => "XVIII", 19 => "XIX", 20 => "XX");

        if(isset($reord6)) {
            $reord6a = $reord6;
        } else {
            $reord6a = array();
        }

        //echo "<pre>";
        // print_r($QnsTitle);
        //print_r($Qns);
        //print_r($reord6);
        // print_r($imgQue);
        //echo "</pre>";
        //exit;

        $examDurationMinutes = (int)$examDur; // Set the exam duration to 5 minutes
        $startTime = now(); // Current time
        $endTime = $startTime->copy()->addMinutes($examDurationMinutes); // Calculate end time

        return view('pages.training.olexam.attendexam')->with('qntit', $QnsTitle)->with('qns', $Qns)->with('romlet', $romanLetters)->with('examtitle', $examtitle)->with('rollid', $roleid)->with('sectn',$sectn)->with('studname',$stud_name)->with('test_id', $examid)->with('qnstempid', $qnmastid)->with('imgQuens', $imgQue)->with('endTime', $endTime)->with('reord6', $reord6a)->with('cortype', $correctType);
    }


    public function saveexam(Request $request)
    {

        $stud_id = $request->student_id;
        $qnstemp_id = $request->qnstempid;
        $test_id = $request->test_id;
        $rollid = $request->rollid;
        $studname = $request->studname;
        $cortype = $request->cortype;
        $section = $request->section6;

        $answers = $request->all();

        // echo "<pre>";
        // print_r($answers);
        // echo "</pre>";
        // exit;

        unset($answers['_token']);
        unset($answers['student_id']);
        unset($answers['qnstempid']);
        unset($answers['rollid']);
        unset($answers['studname']);
        unset($answers['test_id']);
        unset($answers['cortype']);
        unset($answers['files']);
        unset($answers['section6']);

        $result22 = DB::select("select temp_answers from question_master_qns_ans WHERE is_active = 1 AND qn_master_temp_id = $qnstemp_id");
        $act_answer = json_decode($result22[0]->temp_answers, true);

        $result33 = DB::select("select question_template from question_master_template WHERE is_active = 1 AND id = $qnstemp_id");
        $queTemp = json_decode($result33[0]->question_template, true);

        // echo "<pre>";
        // print_r($queTemp);
        // echo "<br><br>============================<br>";
        // print_r($answers);
        // echo "<br><br>============================<br>";
        // print_r($act_answer);
        // echo "</pre>";
        // exit;


        if ($cortype == 1) {

            $totans = 0;

            foreach($queTemp as $kkk => $val3)
            {
                foreach($act_answer as $mm => $val4) {
                    if(!isset($answers[$mm])) {
                        $totans = $totans + 0;
                    } elseif ($answers[$mm] == $val4) {
                        $totans = $totans + $val3[1];
                    } else {
                        $totans = $totans + 0;
                    }
                }
            }

            // echo "<br><br>============================<br>";
            // echo "Total Marks: " . $totans;

        } else {
            $totans = "";
        }



        $ansjson = json_encode($answers, true);

        try {
            DB::table('student_answers')->insert(
                array(
                    'student_id'  =>   $stud_id,
                    'que_master_templ_id'   => $qnstemp_id,
                    'alloc_test_id'   => $test_id,
                    'exam_roll_no'   => $rollid,
                    'student_name'   => $studname,
                    'answer'   => $ansjson,
                    'mark'   => $totans,
                    'section'   => $section,
                    'school_id'   => 1,
                    'created_date'   => Carbon::now(),
                    'updated_date'   => Carbon::now(),
                    'is_deleted'   => 0
                )
            );
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            return View('pages.training.olexam.attendexam')->with('message', "Error Submitting Answers - Try once again !!!");
        }

        return redirect()->route('olexam.index')->with('message', "Finished Exam and Recorded Successfully !!!");
    }

    public function correct()
    {
        return view('pages.training.olexam.correct');
    }

    public function examcorrect()
    {
        $subs = DB::select("select A.id, D.class_id, D.title, A.exam_roll_no as examroll, A.student_name as stname, A.is_validated, A.mark, D.total_marks FROM student_answers A, question_master_template D WHERE A.is_deleted = 0 AND A.que_master_templ_id = D.id AND D.id=A.que_master_templ_id");

        return datatables()->of($subs)
            ->addColumn('action', function ($selected) {
                return
                    '<a class="btn btn-success" href="' . $selected->id . '/correctpaper" title="Correct Exam">Correct Paper</i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="View Student Answered paper" href="' . $selected->id . '/view">View Paper</i></a>';
            })->toJson();
    }

    public function correctpaper($id)
    {
        // echo $id . " MMMMMMMMMM   " . Auth::id();
        // exit;
        // $stud_id = Auth::id() . "~~~~~" . $id;

        $res4 = DB::select("select id, que_master_templ_id, student_id, answer FROM student_answers WHERE is_deleted = 0 AND id=$id");
        $stud_id = $res4[0]->id;
        $qntempid = $res4[0]->que_master_templ_id;
        // $allc_id = $res4[0]->alloc_test_id;

        $stud_answer = json_decode($res4[0]->answer, true);

        //echo "<pre>";
        //print_r($stud_answer);
        //echo " ================================ <br>";
        // print_r($act_answer);
        //exit;

        $res5 = DB::select("select title, question_template from question_master_template WHERE is_active = 1 AND id=$qntempid");
        $marks_each = json_decode($res5[0]->question_template, true);
        $examtitle2 = $res5[0]->title;
        foreach ($marks_each as $mm => $vals) {
            $emark[$mm] = $vals[1];
            $noqtns[$mm] = $vals[0];
        }


        $result2 = DB::select("select temp_questions, temp_answers from question_master_qns_ans WHERE is_active = 1 AND qn_master_temp_id=$qntempid");
        $qtns = json_decode($result2[0]->temp_questions, true);
        $act_answer = json_decode($result2[0]->temp_answers, true);


        $temp = 0;
        foreach ($qtns as $key => $qns) {

            if($key == "AAA") {
                $reord6 = $qns;
                continue;
            } else { $reord6 = ""; }

            $qtype = explode("_", $key);
            if ($qtype[0] != $temp) {
                if(str_contains($qtype[0], "ReOrd") || str_contains($qtype[0], "left") || str_contains($qtype[0], "right") || str_contains($qtype[0], "AAA")) {
                    continue;
                } else {
                    $qry6 = "select title from question_master_title WHERE is_active = 1 AND id=$qtype[0]";
                    // echo $qry6;
                    // exit;
                    $res3 = DB::select($qry6);
                    $QnsTitle[$qtype[0]] = $res3[0]->title;
                }
             }
            $temp = $qtype[0];

            if ($qtype[0] == 7) {
                $type7 = explode("~~~~~", $qns);
                if (isset($type7[0])) {
                    $Qns[$qtype[0]][$qtype[1]][0] = $type7[0];
                }
                if (isset($type7[1])) {
                    $Qns[$qtype[0]][$qtype[1]][1] = $type7[1];
                }
                if (isset($type7[2])) {
                    $Qns[$qtype[0]][$qtype[1]][2] = $type7[2];
                }
                if (isset($type7[3])) {
                    $Qns[$qtype[0]][$qtype[1]][3] = $type7[3];
                }
                if (isset($type7[4])) {
                    $Qns[$qtype[0]][$qtype[1]][4] = $type7[4];
                }
                if (isset($type7[5])) {
                    $Qns[$qtype[0]][$qtype[1]][5] = $type7[5];
                }
                if (isset($type7[6])) {
                    $Qns[$qtype[0]][$qtype[1]][6] = $type7[6];
                }
                if (isset($type7[7])) {
                    $Qns[$qtype[0]][$qtype[1]][7] = $type7[7];
                }
            } elseif ($qtype[0] == 10) {
                // echo "<pre>";
                // print_r($qns);
                // exit;
                for($pp = 1; $pp <= 5; $pp++) {
                    if(isset($qns['Qleft_10'][$pp])) {
                        $Q10[$pp] = $qns['Qleft_10'][$pp] . "~~~~~" . $qns['Qright_10'][$pp];
                    }

                }
                $Qns[10]['qns'] = $Q10;


            } elseif ($qtype[0] == 3) {
                $Qns[3]['qns'][] = $qns;

            } else {
                $Qns[$qtype[0]][$qtype[1]] = $qns;
            }
        }

        $romanLetters = array(1 => "I", 2 => "II", 3 => "III", 4 => "IV", 5 => "V", 6 => "VI", 7 => "VII", 8 => "VIII", 9 => "IX", 10 => "X", 11 => "XI", 12 => "XII", 13 => "XIII", 14 => "XIV", 15 => "XV", 16 => "XVI", 17 => "XVII", 18 => "XVIII", 19 => "XIX", 20 => "XX");

        //echo "<pre>";
        // print_r($QnsTitle);
        //print_r($Qns);
        // echo "<br><br>";
        // print_r($stud_answer);
        // echo "<br><br>";
        //print_r($act_answer);
        // echo "<br><br>";
        // print_r($emark);
        //echo "</pre>";
        //exit;

        // return redirect()->route('olexam.correct')->with('message', "Saving answers Successfully !!!");

        return view('pages.training.olexam.correctpaper')->with('qntit', $QnsTitle)->with('qns', $Qns)->with('romlet', $romanLetters)->with('examtitle2', $examtitle2)->with('stud_id', $stud_id)->with('stud_ans', $stud_answer)->with('act_ans', $act_answer)->with('qn_template_id', $qntempid)->with('eachmark', $emark)->with('eachmark', $emark)->with('noqtns', $noqtns)->with('reord6', $reord6);
    }

    public function view($id)
    {
        // echo $id . " MMMMMMMMMM   " . Auth::id();
        // exit;
        // $stud_id = Auth::id() . "~~~~~" . $id;

        $setupInfo['configs'] = DB::table('config_setup')->where('id', 1)->get();

        $res4 = DB::select("select alloc_test_id, que_master_templ_id, A.exam_roll_no as examroll, A.student_name as stname, answer FROM student_answers as A WHERE A.is_deleted = 0 AND A.id=$id");
        $stud_id = $res4[0]->examroll;
        $qntempid = $res4[0]->que_master_templ_id;
        $allc_id = $res4[0]->alloc_test_id;
        $student_name = $res4[0]->stname;

        $stud_answer = json_decode($res4[0]->answer, true);

        $res5 = DB::select("select title, question_template from question_master_template WHERE is_active = 1 AND id=$qntempid");
        $marks_each = json_decode($res5[0]->question_template, true);
        $examtitle2 = $res5[0]->title;
        foreach ($marks_each as $mm => $vals) {
            $emark[$mm] = $vals[1];
        }
        // echo "<pre>";
        // print_r($emark);
        // exit;

        $result2 = DB::select("select temp_questions, temp_answers from question_master_qns_ans WHERE is_active = 1 AND qn_master_temp_id=$qntempid");
        $qtns = json_decode($result2[0]->temp_questions, true);
        $act_answer = json_decode($result2[0]->temp_answers, true);

        $temp = 0;
        foreach ($qtns as $key => $qns) {
            $qtype = explode("_", $key);
            if ($qtype[0] != $temp) {
                $res3 = DB::select("select title from question_master_title WHERE is_active = 1 AND id=$qtype[0]");
                $QnsTitle[$qtype[0]] = $res3[0]->title;
            }
            $temp = $qtype[0];

            if ($qtype[0] == 7) {
                $type7 = explode("~~~~~", $qns);
                if (isset($type7[0])) {
                    $Qns[$qtype[0]][$qtype[1]][0] = $type7[0];
                }
                if (isset($type7[1])) {
                    $Qns[$qtype[0]][$qtype[1]][1] = $type7[1];
                }
                if (isset($type7[2])) {
                    $Qns[$qtype[0]][$qtype[1]][2] = $type7[2];
                }
                if (isset($type7[3])) {
                    $Qns[$qtype[0]][$qtype[1]][3] = $type7[3];
                }
                if (isset($type7[4])) {
                    $Qns[$qtype[0]][$qtype[1]][4] = $type7[4];
                }
                if (isset($type7[5])) {
                    $Qns[$qtype[0]][$qtype[1]][5] = $type7[5];
                }
                if (isset($type7[6])) {
                    $Qns[$qtype[0]][$qtype[1]][6] = $type7[6];
                }
                if (isset($type7[7])) {
                    $Qns[$qtype[0]][$qtype[1]][7] = $type7[7];
                }
            } else {
                $Qns[$qtype[0]][$qtype[1]] = $qns;
            }
        }

        $romanLetters = array(1 => "I", 2 => "II", 3 => "III", 4 => "IV", 5 => "V", 6 => "VI", 7 => "VII", 8 => "VIII", 9 => "IX", 10 => "X", 11 => "XI", 12 => "XII", 13 => "XIII", 14 => "XIV", 15 => "XV", 16 => "XVI", 17 => "XVII", 18 => "XVIII", 19 => "XIX", 20 => "XX");

        $sql6 = "select A.exam_roll_no as examroll, A.student_name as stname, D.class_id, A.mark, D.total_marks, D.title FROM student_answers A, question_master_template D WHERE A.is_deleted = 0 AND A.que_master_templ_id = $qntempid AND A.que_master_templ_id = D.id";
            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->examroll;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark;
                    $info3[$kk][3] = $data2->total_marks;
                    $info3[$kk][4] = $data2->class_id;
                    $info3[$kk][5] = 0;
                }
            } else {
                $info3 = [];
                $examtitle = "";
            }

        // echo "<pre>";
        // print_r($QnsTitle);
        // print_r($Qns);
        // echo "<br><br>";
        // print_r($stud_answer);
        // echo "<br><br>";
        // print_r($act_answer);
        // echo "<br><br>";
        // print_r($emark);
        // echo "</pre>";
        // exit;

        // return redirect()->route('olexam.correct')->with('message', "Saving answers Successfully !!!");

        return view('pages.training.olexam.view',$setupInfo)->with('qntit', $QnsTitle)->with('qns', $Qns)->with('romlet', $romanLetters)->with('examtitle2', $examtitle2)->with('stud_id', $stud_id)->with('stud_name', $student_name)->with('stud_ans', $stud_answer)->with('act_ans', $act_answer)->with('qn_template_id', $qntempid)->with('allocTestId', $allc_id)->with('eachmark', $emark)->with('examtitle',$examtitle);
    }

    public function savecorrected(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // echo "ZZZZZZZZZZZZZZZZ";
        // exit;

        $marks = $request->all();

        $studid = $marks['student_id'];
        // $testId = $marks['allocTestId'];
        $qntmpid = $marks['qn_template_id'];

        unset($marks['_token']);
        unset($marks['student_id']);
        unset($marks['qn_template_id']);

        $totmark = 0;
        foreach ($marks as $k2 => $vals) {
            if (str_contains($k2, 'mark_')) {
                $totmark = $totmark + $vals;
            }
        }

        // echo "<pre>";
        // print_r($marks);
        // echo "</pre>";

        // echo $studid . " <<<==== " . $testId  . " <<<==== " . $qntmpid . " <<<==== " . $totmark;
        // exit;

        try {
            DB::table('student_answers')
                ->where('id', $studid)
                ->where('que_master_templ_id', $qntmpid)
                ->where('is_deleted', 0)
                ->update(['mark' => $totmark, 'is_validated' => "Yes", 'updated_date' => Carbon::now()]);
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            return View::make('pages.training.olexam.correct')->with('message', "Error Submitting Answers - Try once again !!!");
        }

        // return View::make('pages.training.olexam.correct');
        return redirect()->route('olexam.correct')->with('message', "Student Paper corrected Successfully !!!");
    }

    public function getStudents($id)
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

    public function getAllocExams($id)
    {
        $res6 = DB::select("select qn_master_templ_id, test_title FROM allocate_test WHERE is_deleted = 0 AND class_id=$id");
        $options3 = "";
        foreach ($res6 as $data7) {
            $options3 .= "<option value='" . $data7->qn_master_templ_id . "'>" . $data7->test_title . "</option>";
        }
        return $options3;
    }


    public function getallstudents2($id)
    {
        $res2 = DB::select("select exam_roll_no, student_name FROM student_answers WHERE is_deleted = 0 AND que_master_templ_id=$id");
        $options2 = "";
        foreach ($res2 as $res2a) {
            $options2 .= "<option value='" . $res2a->exam_roll_no . "'>" .  $res2a->exam_roll_no . " - " .$res2a->student_name . "</option>";
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

        $tmplid = $request->que_templ_id;
        $secid = $request->sec_id;

        if($request->report_type == 1)
        {
            $sql6 = "select A.exam_roll_no as examroll, A.section, A.student_name as stname, D.class_id, A.mark, D.total_marks, D.title FROM student_answers A, question_master_template D WHERE A.is_deleted = 0 AND A.exam_roll_no='". $request->studroll_id ."' AND A.que_master_templ_id = $tmplid AND A.que_master_templ_id = D.id";
            // echo $sql6; exit;
            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
            // exit;
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->examroll;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark;
                    $info3[$kk][3] = $data2->total_marks;
                    $info3[$kk][4] = $data2->class_id;
                    $info3[$kk][5] = $data2->section;
                }
            } else {
                $info3 = [];
                $examtitle = "";
            }
        } else {
            if($secid == 0) {
                $sql6 = "select A.exam_roll_no as examroll, A.section, A.student_name as stname, D.class_id, A.mark, D.total_marks, D.title FROM student_answers A, question_master_template D WHERE A.is_deleted = 0 AND A.que_master_templ_id = $tmplid AND A.que_master_templ_id = D.id";
            } else {
                $sql6 = "select A.exam_roll_no as examroll, A.section, A.student_name as stname, D.class_id, A.mark, D.total_marks, D.title FROM student_answers A, question_master_template D WHERE A.is_deleted = 0 AND A.section='". $secid ."' AND A.que_master_templ_id = $tmplid AND A.que_master_templ_id = D.id";
            }
            // $sql6 = "select A.exam_roll_no as examroll, A.section, A.student_name as stname, D.class_id, A.mark, D.total_marks, D.title FROM student_answers A, question_master_template D WHERE A.is_deleted = 0 AND A.section='". $secid ."' AND A.que_master_templ_id = $tmplid AND A.que_master_templ_id = D.id";

            // echo $sql6; exit;

            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->examroll;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark;
                    $info3[$kk][3] = $data2->total_marks;
                    $info3[$kk][4] = $data2->class_id;
                    $info3[$kk][5] = $data2->section;
                }
            } else {
                $info3 = [];
                $examtitle = "";
            }
        }

        // $pdf = Pdf::loadView('pages.training.olexam.printreport');

        // echo $sql6 . "<br>";
        // echo $examtitle . "<br>";
        // echo "<pre>";
        // print_r($info3);
        // echo "</pre>";
        // exit;

        // return $pdf->stream('document.pdf');

        return view('pages.training.olexam.printreport',$setupInfo)->with('stud_data',$info3)->with('examtitle',$examtitle)->with('tmplid',$tmplid);
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
            $sql6 = "select C.user_id as roleid, CONCAT(C.first_name, ' ', C.last_name) as stname, D.class_id, C.Section, A.mark, D.total_marks, D.title FROM student_answers A, students C, question_master_template D WHERE A.is_deleted = 0 AND D.class_id=$classid AND A.que_master_templ_id = $tmplid AND A.que_master_templ_id = D.id AND A.student_id = C.user_id";
            // echo $sql6; exit;
            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
            // exit;
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->roleid;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark;
                    $info3[$kk][3] = $data2->total_marks;
                    $info3[$kk][4] = $data2->class_id;
                    $info3[$kk][5] = $data2->Section;
                }
            } else {
                $info3 = [];
                $examtitle = "";
            }
        } else {
            $sql6 = "select C.user_id as roleid, CONCAT(C.first_name, ' ', C.last_name) as stname, D.class_id, C.Section, A.mark, D.total_marks, D.title FROM student_answers A, students C, question_master_template D WHERE A.is_deleted = 0 AND A.que_master_templ_id = D.id AND C.user_id = A.student_id AND D.id=A.que_master_templ_id AND C.user_id=" . $request->student_id;
            $prep3 = DB::select($sql6);
            if(!empty($prep3)) {
                foreach ($prep3 as $kk => $data2) {
                    $examtitle = $data2->title;
                    $info3[$kk][0] = $data2->roleid;
                    $info3[$kk][1] = $data2->stname;
                    $info3[$kk][2] = $data2->mark;
                    $info3[$kk][3] = $data2->total_marks;
                    $info3[$kk][4] = $data2->class_id;
                    $info3[$kk][5] = $data2->Section;
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
        $pdf = Pdf::loadView('pages.training.olexam.printpdf',$finalarray);

        // echo $sql6 . "<br>";
        // echo $examtitle . "<br>";


        return $pdf->stream('pages.training.olexam.printpdf.pdf');
    }

}
