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

class ReportExcellPrognosa implements FromView,ShouldAutoSize,WithEvents,WithColumnFormatting
{

    protected $year;
    protected $segment;
    protected $unit;
    // protected $month;

    public function __construct(String $year,$segment,$unit) {
        $this->year    = $year;
        $this->segment = $segment;
        $this->unit    = $unit;
        // $this->month   = $month;
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
                    'A1:G5',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );

                $event->sheet->styleCells(
                    'A',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );

                $event->sheet->styleCells(
                    'E',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );

                $event->sheet->styleCells(
                    'F',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );

                $event->sheet->styleCells(
                    'H',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );


                $styleArray = [
                    'font' => [
                    'bold' => true,
                    ]
                    ];

                $event->sheet->styleCells('A1:G5', [
                    'font' => [
                        'bold' => true,
                        ]
                ]);

                $event->sheet->styleCells('A'.$event->getDelegate()->getHighestRow().':'.$event->getDelegate()-> getHighestColumn().
                $event->getDelegate()->getHighestRow(), [
                    'font' => [
                        'bold' => true,
                        ]
                ]);

            },
        ];
    }
    public function columnFormats(): array
    {
        $segment = $this->segment;
        switch ($segment) {
            default:
            return [
                'A' => NumberFormat::FORMAT_NUMBER,
                'C' => NumberFormat::FORMAT_NUMBER,
                'D' => NumberFormat::FORMAT_NUMBER,
                'E' => NumberFormat::FORMAT_NUMBER,
                'F' => NumberFormat::FORMAT_NUMBER,
        ];
            break;
            }
    }

    public function view(): View
    {
        $year    = $this->year;
        $segment = $this->segment;
        $unit    = $this->unit;

        $sql = SqlGroupPrognosa($unit);
        switch($segment) {
            case "belanja" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_belanja.id as Kode,monira_ref_belanja.Belanja as Keterangan,
                    sum(Prognosa) as Prognosa,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen,
                    sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa
                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPaguPrognosa']." monira_data_prognosa.Belanja,sum(monira_data_prognosa.Amount) as Prognosa, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_prognosa
                                    WHERE TA=$year
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Belanja, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Belanja, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                ) as UnionData
                LEFT JOIN monira_ref_belanja ON monira_ref_belanja.id=UnionData.Belanja
                ".$sql['join']."
                GROUP BY
                ".$sql['groupFinal']." monira_ref_belanja.Belanja
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"     => $data->KodeHeader ?? '',
                    "NamaHeader"     => $data->NamaHeader ?? '',
                    "KodeSubHeader"  => $data->KodeSubHeader ?? '',
                    "NamaSubHeader"  => $data->NamaSubHeader ?? '',
                    "Kode"           => $data->Kode,
                    "Keterangan"     => $data->Keterangan,
                    "Prognosa"       => $data->Prognosa,
                    "Pagu"           => $data->Pagu,
                    "Realisasi"      => $data->Realisasi,
                    "Sisa"           => $data->Sisa,
                    "Persen"         => $data->Persen,
                    "PersenPrognosa" => $data->PersenPrognosa,
                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
                // return response()->json($object);
            break;

            case "sumberdana" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_sumber_dana.KodeSumberDana as Kode, monira_ref_sumber_dana.NamaSumberDana as Keterangan,
                    sum(Prognosa) as Prognosa,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen,
                    sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa

                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPaguPrognosa']." monira_data_prognosa.SumberDana,sum(monira_data_prognosa.Amount) as Prognosa, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_prognosa
                                    WHERE TA=$year
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.SumberDana, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.SumberDana, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                ) as UnionData
                LEFT JOIN monira_ref_sumber_dana ON monira_ref_sumber_dana.KodeSumberDana=UnionData.SumberDana
                ".$sql['join']."
                GROUP BY
                ".$sql['groupFinal']." monira_ref_sumber_dana.KodeSumberDana
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"     => $data->KodeHeader ?? '',
                    "NamaHeader"     => $data->NamaHeader ?? '',
                    "KodeSubHeader"  => $data->KodeSubHeader ?? '',
                    "NamaSubHeader"  => $data->NamaSubHeader ?? '',
                    "Kode"           => $data->Kode,
                    "Keterangan"     => $data->Keterangan,
                    "Prognosa"       => $data->Prognosa,
                    "Pagu"           => $data->Pagu,
                    "Realisasi"      => $data->Realisasi,
                    "Sisa"           => $data->Sisa,
                    "Persen"         => $data->Persen,
                    "PersenPrognosa" => $data->PersenPrognosa,

                   ];
                });

                $object = json_decode(json_encode($data), FALSE);
            break;

            case "kegiatan" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_kegiatan.KdKegiatan as Kode, monira_ref_kegiatan.NamaKegiatan as Keterangan,
                    sum(Prognosa) as Prognosa,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen,
                    sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa

                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPaguPrognosa']." monira_data_prognosa.Kegiatan,sum(monira_data_prognosa.Amount) as Prognosa, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_prognosa
                                    WHERE TA=$year
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kegiatan, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kegiatan, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                ) as UnionData
                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=UnionData.Kegiatan
                ".$sql['join']."
                WHERE (Prognosa <> 0 OR Pagu <> 0 OR Realisasi <> 0)
                GROUP BY
                ".$sql['groupFinal']." monira_ref_kegiatan.KdKegiatan
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"     => $data->KodeHeader ?? '',
                    "NamaHeader"     => $data->NamaHeader ?? '',
                    "KodeSubHeader"  => $data->KodeSubHeader ?? '',
                    "NamaSubHeader"  => $data->NamaSubHeader ?? '',
                    "Kode"           => $data->Kode,
                    "Keterangan"     => $data->Keterangan,
                    "Prognosa"       => $data->Prognosa,
                    "Pagu"           => $data->Pagu,
                    "Realisasi"      => $data->Realisasi,
                    "Sisa"           => $data->Sisa,
                    "Persen"         => $data->Persen,
                    "PersenPrognosa" => $data->PersenPrognosa,

                   ];
                });
                                                // return response()->json($data);

                $object = json_decode(json_encode($data), FALSE);
            break;

            case "output" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_output.KdOutput as Kode, monira_ref_output.NamaOutput as Keterangan,
                    sum(Prognosa) as Prognosa,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen,
                    sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa

                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPaguPrognosa']." monira_data_prognosa.Output,sum(monira_data_prognosa.Amount) as Prognosa, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_prognosa
                                    WHERE TA=$year
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Output, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Output, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                ) as UnionData
                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=UnionData.Output
                ".$sql['join']."
                WHERE (Prognosa <> 0 OR Pagu <> 0 OR Realisasi <> 0)
                GROUP BY
                ".$sql['groupFinal']." monira_ref_output.KdOutput
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"     => $data->KodeHeader ?? '',
                    "NamaHeader"     => $data->NamaHeader ?? '',
                    "KodeSubHeader"  => $data->KodeSubHeader ?? '',
                    "NamaSubHeader"  => $data->NamaSubHeader ?? '',
                    "Kode"           => $data->Kode,
                    "Keterangan"     => $data->Keterangan,
                    "Prognosa"       => $data->Prognosa,
                    "Pagu"           => $data->Pagu,
                    "Realisasi"      => $data->Realisasi,
                    "Sisa"           => $data->Sisa,
                    "Persen"         => $data->Persen,
                    "PersenPrognosa" => $data->PersenPrognosa,


                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;

            case "kewenangan" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_kewenangan.IdKewenangan as Kode, monira_ref_kewenangan.NamaKewenangan as Keterangan,
                    sum(Prognosa) as Prognosa,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen,
                    sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa

                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPaguPrognosa']." monira_data_prognosa.Kewenangan,sum(monira_data_prognosa.Amount) as Prognosa, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_prognosa
                                    WHERE TA=$year
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kewenangan, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kewenangan, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                ) as UnionData
                LEFT JOIN monira_ref_kewenangan ON monira_ref_kewenangan.IdKewenangan=UnionData.Kewenangan
                ".$sql['join']."
                WHERE (Prognosa <> 0 OR Pagu <> 0 OR Realisasi <> 0)
                GROUP BY
                ".$sql['groupFinal']." monira_ref_kewenangan.IdKewenangan
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"     => $data->KodeHeader ?? '',
                    "NamaHeader"     => $data->NamaHeader ?? '',
                    "KodeSubHeader"  => $data->KodeSubHeader ?? '',
                    "NamaSubHeader"  => $data->NamaSubHeader ?? '',
                    "Kode"           => $data->Kode,
                    "Keterangan"     => $data->Keterangan,
                    "Prognosa"       => $data->Prognosa,
                    "Pagu"           => $data->Pagu,
                    "Realisasi"      => $data->Realisasi,
                    "Sisa"           => $data->Sisa,
                    "Persen"         => $data->Persen,
                    "PersenPrognosa" => $data->PersenPrognosa,

                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;


            case "akun" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_akun.KdAkun as Kode, monira_ref_akun.NamaAkun as Keterangan,
                    sum(Prognosa) as Prognosa,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen,
                    sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa

                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPaguPrognosa']." monira_data_prognosa.Akun,sum(monira_data_prognosa.Amount) as Prognosa, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_prognosa
                                    WHERE TA=$year
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Akun, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Akun, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                ) as UnionData
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                ".$sql['join']."
                WHERE (Prognosa <> 0 OR Pagu <> 0 OR Realisasi <> 0)
                GROUP BY
                ".$sql['groupFinal']." monira_ref_akun.KdAkun
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"     => $data->KodeHeader ?? '',
                    "NamaHeader"     => $data->NamaHeader ?? '',
                    "KodeSubHeader"  => $data->KodeSubHeader ?? '',
                    "NamaSubHeader"  => $data->NamaSubHeader ?? '',
                    "Kode"           => $data->Kode,
                    "Keterangan"     => $data->Keterangan,
                    "Prognosa"       => $data->Prognosa,
                    "Pagu"           => $data->Pagu,
                    "Realisasi"      => $data->Realisasi,
                    "Sisa"           => $data->Sisa,
                    "Persen"         => $data->Persen,
                    "PersenPrognosa" => $data->PersenPrognosa,

                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;
            }

            switch ($unit) {
                case 'eselon1':
                    return view('reports.report-excell-prognosa', [
                        'data'    => $data,
                        'unit'    => $unit,
                        'segment' => $segment,
                        'year'    => $year,
                    ]);
                    break;
                case 'propinsi':
                    $data = NestCollection($object,'2');
                    return view('reports.report-excell-prognosa', [
                        'data'    => $data,
                        'unit'    => $unit,
                        'segment' => $segment,
                        'year'    => $year,
                    ]);
                    break;
                case 'satker':
                    $data = NestCollection($object,'3');
                    return view('reports.report-excell-prognosa', [
                        'data'    => $data,
                        'unit'    => $unit,
                        'segment' => $segment,
                        'year'    => $year,
                    ]);
                    break;
            }


    }
}


