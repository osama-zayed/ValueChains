<?php

namespace App\Http\Controllers;
use App\Models\Procedure;
use App\Notifications\Notifications;
use Mpdf\Mpdf;
use Illuminate\Http\Request;

class PdfHelperController extends Controller
{
    public static function printPdf()
    {
        $data = Procedure::whereIn('id', request('data'))->get();

        // Set up mPDF with UTF-8 support
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);

        // Render the view to HTML
        $html = view('report.index', [
            'data' => $data
        ])->render();

        // Debug the HTML content (optional)
        // file_put_contents('debug.html', $html);

        // Write HTML to PDF
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');
        // Return the PDF response
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="report.pdf"');
    }
}
