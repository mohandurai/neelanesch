<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create()
    {
        $data = DB::select("select `id`, `class` from student_class WHERE `school_id` = 1 AND `is_deleted` = 0");
        foreach($data as $arr) {
            $classArr[$arr->id] = $arr->class;
        }

        return view('pages.payment.online')->with('classes', $classArr);
    }

}
