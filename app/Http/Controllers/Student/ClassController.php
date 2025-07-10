<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DB;
use DataTables;
use Carbon\Carbon;

class ClassController extends Controller
{
    //
    //
    public function index()
    {
        return view('pages.student.class.index');
    }

    public function masterclasslist()
    {

        $chaps = DB::select("SELECT id, class, remarks FROM `student_class` WHERE is_deleted=0 ORDER BY class");

        return datatables()->of($chaps)
            ->addColumn('action', function ($selected) {
                return
                    '<a class="btn btn-success" href="' . $selected->id . '/show" title="Detailed view of this Record"> <i class="fas fa-eye"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning" title="Edit this Record" href="' . $selected->id . '/edit"> <i class="fas fa-edit"></i> </a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="' . $selected->id . '/destroy" onclick="return confirmation();" title="Delete this Record"><i class="fas fa-trash"></i></a>';
            })->toJson();
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
            'class_title' => 'required'
        ]);

        try {
            DB::table('student_class')->insert(
                array(
                       'class'  =>   $request->class_title,
                       'remarks'  =>   $request->remarks,
                       'school_id'   =>   1,
                       'created_date'   => Carbon::now(),
                       'updated_date'   => Carbon::now(),
                       'is_deleted'   => 0
                )
           );
        //    echo "Yessssssssssssssss";
        //    exit;
        }
        catch (\Throwable $e) {
            print_r($e->getMessage());
            return View::make('pages.student.class.index')->with('message', "Class Title already exists - Try different video name !!!");
        }

        return redirect()->route('class.index')->with('message', "New Class Created Successfully !!!");
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

        DB::table('student_class')->where('id', $request->id)->update(
            [
                'class'  =>   $request->title,
                'remarks'  =>   $request->remarks,
                'is_deleted'  =>   $request->is_deleted,
                'updated_date' => Carbon::now()
            ]
        );
        return redirect()->route('class.index')->with('message', 'Class Info updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('student_class')->delete($id);
        return redirect()->route('class.index')->with('message', 'Class Info removed successfully');
    }


    // routes functions
    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.student.class.create');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['clsinfo'] = DB::table('student_class')->where('id', $id)->first();
        // print_r($data);
        // exit;
        return View::make('pages.student.class.show', $data);
    }


    /**
     * Show the form for editing the specified post.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['clsinfo'] = DB::table('student_class')->where('id', '=', $id)->where('is_deleted', '=', '0')->first();
        // foreach ($classlist as $arr) {
        //     $classArr[$arr->id] = $arr->class;
        // }
        // echo "<pre>";
        // print_r($data);
        // exit;
        return View::make('pages.student.class.edit', $data);
    }
}
