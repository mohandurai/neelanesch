<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use DB;
use DataTables;
use Carbon\Carbon;

class TermController extends Controller
{
    //
    //
    public function index()
    {
        return view('pages.student.term.index');
    }

    public function termlist()
    {

        $chaps = DB::select("SELECT id, title, details FROM `terms` WHERE is_deleted=0 ORDER BY title");

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
            'title' => 'required'
        ]);

        try {
            DB::table('terms')->insert(
                array(
                       'title'  =>   $request->title,
                       'details'   =>  $request->details,
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
            return View::make('pages.student.term.index')->with('message', "Term Title already exists - Try different video name !!!");
        }

        return redirect()->route('term.index')->with('message', "New Term Created Successfully !!!");
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

        DB::table('terms')->where('id', $request->id)->update(
            [
                'title'  =>   $request->title,
                'details'  =>   $request->details,
                'updated_date' => Carbon::now()
            ]
        );
        return redirect()->route('term.index')->with('message', 'Term Info updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('terms')->delete($id);
        return redirect()->route('term.index')->with('message', 'Term removed successfully');
    }


    // routes functions
    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.student.term.create');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['staffinfo'] = DB::table('terms')->where('id', $id)->first();
        // print_r($data);
        // exit;
        return View::make('pages.student.term.show', $data);
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
        $data['terminfo'] = DB::table('terms')->where('id', $id)->first();
        return View::make('pages.student.term.edit', $data)->with('classlist', $classArr);
    }
}
