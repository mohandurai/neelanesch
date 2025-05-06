<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DB;
use DataTables;
use Carbon\Carbon;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    //
    public function index()
    {
        return view('pages.student.staff.index');
    }

    public function stafflist()
    {

        $chaps = DB::select("SELECT A.id, A.name, A.email, A.gender, A.department, A.role FROM `users` as A  WHERE A.category=2 AND A.status=1 ORDER BY A.updated_at DESC");

        return datatables()->of($chaps)
            ->addColumn('action', function ($selected) {
                return
                    '<a class="btn btn-success" href="' . $selected->id . '/show" title="Detailed view of this Record"> <i class="fas fa-eye"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="Edit this Record" href="' . $selected->id . '/edit"> <i class="fas fa-edit"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="' . $selected->id . '/destroy" onclick="return confirmation();" title="Delete this Record"><i class="fas fa-trash"></i></a>';
            })->toJson();
    }


    // routes functions
    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.student.staff.create');
    }



    /**
     * Store a newly created resource in storage. Success warning
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // print_r($request->all());
        // exit;

        $request->validate([
            'first_name' => 'required',
            'email' => 'required'
        ]);

        $chekEmail = sizeof(DB::table('users')->where('email', '=', $request->email)->get());

        if($chekEmail > 0 ) {
            return redirect()->route('staff.index')->with('message', 'Email already exists in the system. Please try different email.');
        }
        // exit;

        if ($request->require_login == "1") {

            try {
                DB::table('users')->insert(
                    array(
                       'name' => $request->first_name . " " . $request->last_name,
                            'email' => $request->email,
                            'password' => Hash::make("Pass@123"),
                            'contact_info'   => $request->contact_info,
                            'gender'   => $request->gender,
                            'department'   => $request->department,
                            'role'   => $request->role,
                            'status'   => 1,
                            'created_at'   => Carbon::now(),
                            'updated_at'   => Carbon::now(),
                            'category'   => 2
                    )
                );
            } catch (\Throwable $e) {
                print_r($e->getMessage());
                return View::make('pages.student.staff.index')->with('message', "Staff Title already exists - Try different video name !!!");
            }

        } else {

            try {
                DB::table('users')->insert(
                    array(
                       'name' => $request->first_name . " " . $request->last_name,
                            'email' => $request->email,
                            'password' => Hash::make("Pass@123"),
                            'contact_info'   => $request->contact_info,
                            'gender'   => $request->gender,
                            'department'   => $request->department,
                            'role'   => $request->role,
                            'status'   => 0,
                            'created_at'   => Carbon::now(),
                            'updated_at'   => Carbon::now(),
                            'category'   => 2
                    )
                );
            } catch (\Throwable $e) {
                print_r($e->getMessage());
                return View::make('pages.student.staff.index')->with('message', "Staff Title already exists - Try different video name !!!");
            }

        }


        if ($request->file()) {
            $fileName = $request->id . "_" . $request->first_name . "_" . $request->file->getClientOriginalName();
            $filePath = $request->file('photo_image')->storeAs('images/staff', $fileName, 'public');
        } else {
            $fileName = "";
        }


        return redirect()->route('staff.index')->with('message', "New Staff Created Successfully !!!");
    }


    /**
     * Bulk Import Students
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkimport(Request $request)
    {
        // print_r($request->all());
        // echo "<br><br>";
        // exit;
        $dttime = Carbon::now();
        $dttime1 = str_replace("-", "_", $dttime);
        $dttime2 = str_replace(":", "", $dttime1);
        $dttime3 = str_replace(" ", "_", $dttime2);

        if($request->file('csvfile')) {
            $image = $request->file('csvfile');
            $imageName = $dttime3 . "_" . $image->getClientOriginalName();
            $storeAt = "csvfiles";
            $filePath = $request->file('csvfile')->storeAs($storeAt, $imageName, 'public');
        }

        $showimg2 = "storage/csvfiles/" . $imageName;
        // echo $showimg2;
        // exit;
        $handle = fopen($showimg2, "r");
        $count = 0;
        while (($row = fgetcsv($handle)) !== FALSE) {
            $count++;
            if ($count == 1) { continue; }
            // echo "<pre>";
            // print_r($row);
            // exit;
            $chekEmail = sizeof(DB::table('users')->where('email', '=', $row[2])->get());
            if($chekEmail > 0 ) {
                echo $row[2] . '<<<<==== This email id already exists students master record. Please try different emailid. Student Name ' . $row[0] . ' ' . $row[1] . '<br>';
            } else {

                if ($row[9] == "1") {
                    $lastInsertedId = DB::table('users')->insertGetId([
                        'name' => $row[0] . " " . $row[1],
                        'email' => $row[2],
                        'password' => Hash::make("Pass@123"),
                    ]);
                } else {
                    $lastInsertedId = null;
                }

                // echo $insUId . " Yesssssssssssssss";
                // exit;

            }
        }

        fclose($handle);

        return redirect()->route('student.index')->with('message', "Bulk Import Completed Successfully !!!");
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // echo "</pre>";
        // exit;

        DB::table('students')->where('id', $request->id)->update(
            [
                'first_name'  =>   $request->first_name,
                'last_name'  =>   $request->last_name,
                'mobile'  =>   $request->mobile,
                'class_id'  =>   $request->class_id,
                'require_login' => $request->require_login,
                'gender'  =>   $request->gender,
                'dob'  =>   $request->dob,
                'marks_history'  =>  $request->marks_history,
                'fees_paid_history'  => $request->fees_paid_history,
                'upload_pps_image_info' => $request->upload_pps_image_info,
                'updated_date' => Carbon::now(),
                'is_deleted'  => 0
            ]
        );
        return redirect()->route('student.index')->with('message', 'Student Info updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('students')->delete($id);
        return redirect()->route('student.index')->with('message', 'Student removed successfully');
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['studinfo'] = DB::table('students')->where('id', $id)->first();
        // print_r($data);
        // exit;
        return View::make('pages.student.student.show', $data);
    }


    /**
     * Show the form for editing the specified post.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $classlist = DB::table('student_class')->select('id', 'class')->where('school_id', '=', 1)->where('is_deleted', '=', '0')->get();
        foreach ($classlist as $arr) {
            $classArr[$arr->id] = $arr->class;
        }
        // print_r($classArr);
        // exit;
        $data['studinfo'] = DB::table('students')->where('id', $id)->first();
        return View::make('pages.student.student.edit', $data)->with('classlist', $classArr);
    }
}
