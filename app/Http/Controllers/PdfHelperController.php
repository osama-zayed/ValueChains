<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use App\Notifications\Notifications;
use Alkoumi\LaravelHijriDate\Hijri;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Illuminate\Http\Request;

class PdfHelperController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public static function printPdf()
    {
        if (auth()->user()->user_type == 'admin') {
            $data = Procedure::whereIn('id', request('data'))->get();
        } else {
            $data = Procedure::where('user_id', auth()->user()->id)->whereIn('id', request('data'))->get();
        }
        return  self::print($data);
    }
    public static function print($data)
    {

        // Set up mPDF with UTF-8 support
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);
        $today = Carbon::now()->format('Y / m / d');
        $hijriToday = Hijri::Date('l dS F o', Carbon::now());
        $hijriToYear = Hijri::Date('o', Carbon::now());
        // Render the view to HTML
        $html = view('report.index', [
            'data' => $data,
            'today' => $today,
            'hijriToday' => $hijriToday,
            'hijriToYear' => $hijriToYear,
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
