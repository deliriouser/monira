<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Facades\DB;
use SimpleCurl;
use Illuminate\Support\Facades\Cache;
use App\Models\RefWilayah;

class ReportExcellHarian implements FromView,ShouldAutoSize,WithEvents,WithColumnFormatting
{

    protected $year;
    protected $segment;
    protected $unit;
    protected $top;
    protected $bottom;

    public function __construct(String $year,$segment,$unit,$top,$bottom) {
        $this->year    = $year;
        $this->segment = $segment;
        $this->unit    = $unit;
        $this->top     = $top;
        $this->bottom  = $bottom;
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class    => function(AfterSheet $event) {

                 $event->sheet->styleCells(
                    'A5:'.
                    $event->getDelegate()-> getHighestColumn().
                    $event->getDelegate()->getHighestRow()
                    ,
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ]
                    ]
                );

                $event->sheet->styleCells(
                    'A',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );

                $event->sheet->styleCells(
                    'G',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );

                $event->sheet->styleCells(
                    'H',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );
                $event->sheet->styleCells(

                    'A1:G5',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                    ,
                );

            },
        ];
    }
    public function columnFormats(): array
    {
        $segment = $this->segment;
        switch ($segment) {
            case 'pnbp':
                return [
                    'A' => NumberFormat::FORMAT_NUMBER,
                    'C' => NumberFormat::FORMAT_NUMBER,
                    'D' => NumberFormat::FORMAT_NUMBER,
                    'E' => NumberFormat::FORMAT_NUMBER,
                ];

            default:
            return [
                'A' => NumberFormat::FORMAT_NUMBER,
                'C' => NumberFormat::FORMAT_NUMBER,
                'D' => NumberFormat::FORMAT_NUMBER,
                'E' => NumberFormat::FORMAT_NUMBER,
                'F' => NumberFormat::FORMAT_NUMBER,
                'G' => NumberFormat::FORMAT_NUMBER,
        ];
            break;
            }
    }

    public function view(): View
    {
        $year    = $this->year;
        $segment = $this->segment;
        $unit    = $this->unit;
        $top     = $this->top;
        $bottom  = $this->bottom;

        switch ($segment) {

            case 'rankharian':
                        $data = Cache::remember('ranking_satker_harian',1, function () use ($year) {
                            return DB::table('monira_ref_satker')
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.KdSatker
                                            FROM monira_data_dipa
                                                WHERE TA=$year AND IsActive=1
                                                    GROUP BY KdSatker) as monira_data_dipa
                                            "),
                                                'monira_data_dipa.KdSatker', '=', 'monira_ref_satker.KodeSatker' )
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.KdSatker
                                            FROM monira_data_belanja
                                                WHERE TA=$year AND Output<>'ZZZ' GROUP BY KdSatker) as monira_data_belanja
                                            "),
                                                'monira_data_belanja.KdSatker', '=', 'monira_ref_satker.KodeSatker' )
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_komitmen.Amount) as Prognosa,monira_data_komitmen.KdSatker,monira_data_komitmen.Persen as Persen_satker
                                            FROM monira_data_komitmen
                                                WHERE TA=$year GROUP BY KdSatker) as monira_data_prognosa
                                            "),
                                                'monira_data_prognosa.KdSatker', '=', 'monira_ref_satker.KodeSatker' )
                                ->leftjoin('monira_ref_wilayah','monira_ref_wilayah.KodeWilayah','=','monira_ref_satker.KodeWilayah')
                                ->selectRaw("

                                            KodeSatker,NamaSatuanKerja,
                                            WilayahName,
                                            IFNULL(Pagu,0) as Pagu,
                                            IFNULL(Realisasi,0) as Realisasi,
                                            IFNULL(Prognosa,0) as Prognosa,
                                            Persen_satker*100 as Persen_satker,
                                            (Pagu-Realisasi) as Sisa,
                                            (Realisasi/Pagu)*100 as Persen,
                                            (Prognosa/Pagu)*100 as Persen_prognosa
                                            ")
                                ->whereRaw('(Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                                ->whereRaw('(Pagu <> 0 OR Realisasi <> 0)')
                                ->orderBy('Persen','DESC')
                                ->get();
                                });

                        return view('reports.report-excell-ranking-harian', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year,
                            'top'     => $top,
                            'bottom'  => $bottom,
                        ]);
            break;




        }
    }
}


