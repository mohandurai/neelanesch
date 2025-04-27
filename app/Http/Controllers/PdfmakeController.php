<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class PdfmakeController extends Controller
{

	public function generatePdf()
	{
	    $data = [
	        'title' => 'Sample PDF SSSSSSSSSSSSSSSS',
	        'content' => 'This is the PDF content generated from a Blade template.'
	    ];

	    $pdf = Pdf::loadView('pdf.document', $data);

	    // For direct download:
	    // return $pdf->download('document.pdf');

	    // To save to storage:
	    // Storage::put('public/pdf/document.pdf', $pdf->output());
	    // return response()->json(['success' => true]);

	    // To display in browser:
	    return $pdf->stream('document.pdf');
	}
}
