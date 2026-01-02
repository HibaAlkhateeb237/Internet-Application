<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class AdminReportController extends Controller
{


    public function exportCsv()
    {
        $fileName = 'complaints_report.csv';

        $complaints = Complaint::with('user:id,name', 'agency:id,name')->get();

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // ✅ UTF-8 BOM for Arabic support in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'Reference',
                'User',
                'Agency',
                'Status',
                'Created At'
            ]);

            Complaint::with('user:id,name', 'agency:id,name')
                ->chunk(500, function ($complaints) use ($file) {
                    foreach ($complaints as $c) {
                        fputcsv($file, [
                            $c->reference_number,
                            $c->user?->name ?? '-',
                            $c->agency?->name ?? '-',
                            $c->status,
                           // $c->created_at?->format('Y-m-d H:i'),
                            "'" . $c->created_at?->format('Y-m-d H:i'),

                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    //=========================================================================================


/*

    public function exportPdf()
    {

        $complaints = Complaint::with(['user', 'agency'])->get();

        $pdf = Pdf::loadView('reports.complaints', compact('complaints'));
        return $pdf->download('complaints_report.pdf');

    }*/


    public function exportPdf()
    {
        $complaints = Complaint::with(['user', 'agency'])->get();

        // إعدادات mPDF للغة العربية
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'setAutoBottomMargin' => 'stretch',
        ]);

        // تمرير البيانات إلى Blade وتحويلها إلى HTML
        $html = view('reports.complaints', compact('complaints'))->render();

        $mpdf->WriteHTML($html);
        return $mpdf->Output('complaints_report.pdf', 'D'); // 'D' = تحميل
    }





}
