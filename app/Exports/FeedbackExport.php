<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FeedbackExport implements FromView, ShouldAutoSize, WithEvents
{
    private $data;
    function __construct($data)
    {
        $this->data = $data;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRangeHeader = 'A1:W1'; // All headers
                $cellRangeHeader2 = 'A2:W2'; // All headers
                $cellRange = 'A:W'; // All body
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle($cellRangeHeader)->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle($cellRangeHeader)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRangeHeader2)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRangeHeader2)->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            },
        ];
    }

    public function view(): View
    {

        return view('feedback_list_excel', [
            'final_data' => $this->data
        ]);
    }
}
