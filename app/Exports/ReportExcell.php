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

class ReportExcell implements FromView,ShouldAutoSize,WithEvents,WithColumnFormatting
{

    protected $year;
    protected $segment;
    protected $unit;

    public function __construct(String $year,$segment,$unit) {
        $this->year    = $year;
        $this->segment = $segment;
        $this->unit    = $unit;
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
        $year = $this->year;
        $segment = $this->segment;
        $unit = $this->unit;

        switch ($segment) {
            case 'belanja':
                switch ($unit) {
                    case 'eselon1':
                        $data = Cache::remember('ReportBelanjaPDF',3600, function () use ($year) {
                            return DB::table('monira_ref_belanja')
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Belanja
                                            FROM monira_data_dipa
                                                WHERE TA=$year AND Revision=0
                                                    GROUP BY Belanja) as monira_data_dipa_awal
                                            "),
                                                'monira_data_dipa_awal.Belanja', '=', 'monira_ref_belanja.id' )
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Belanja
                                            FROM monira_data_dipa
                                                WHERE TA=$year AND IsActive=1
                                                    GROUP BY Belanja) as monira_data_dipa
                                            "),
                                                'monira_data_dipa.Belanja', '=', 'monira_ref_belanja.id' )
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Belanja
                                            FROM monira_data_belanja
                                                WHERE TA=$year AND Output<>'ZZZ' GROUP BY Belanja) as monira_data_belanja
                                            "),
                                                'monira_data_belanja.Belanja', '=', 'monira_ref_belanja.id')
                                ->selectRaw("
                                            id as Kode, monira_ref_belanja.Belanja as Keterangan,
                                            ifnull(PaguAwal,0) as PaguAwal,ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,
                                            ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                                            (Realisasi/Pagu)*100 as Persen
                                            ")
                                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                                ->get();
                            });

                        return view('reports.report-excell-one-level', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year
                        ]);
                    break;
                }
            break;

            case 'kegiatan':
                switch ($unit) {
                    case 'eselon1':
                        $data = Cache::remember('ReportKegiatanPDF', 3600, function () use ($year) {
                        return DB::table('monira_ref_kegiatan')
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Kegiatan
                                    FROM monira_data_dipa
                                        WHERE TA=$year AND Revision=0
                                            GROUP BY Kegiatan) as monira_data_dipa_awal
                                    "),
                                        'monira_data_dipa_awal.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan' )
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Kegiatan
                                    FROM monira_data_dipa
                                        WHERE TA=$year AND IsActive=1
                                            GROUP BY Kegiatan) as monira_data_dipa
                                    "),
                                        'monira_data_dipa.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan' )
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Kegiatan
                                    FROM monira_data_belanja
                                        WHERE TA=$year GROUP BY Kegiatan) as monira_data_belanja
                                    "),
                                        'monira_data_belanja.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan')
                        ->selectRaw("
                                    monira_ref_kegiatan.KdKegiatan as Kode,monira_ref_kegiatan.NamaKegiatan as Keterangan,
                                    ifnull(PaguAwal,0) as PaguAwal,ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,
                                    ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                                    (Realisasi/Pagu)*100 as Persen
                                    ")
                        ->where('pagu','!=','null')
                        ->get();
                        });

                        return view('reports.report-excell-one-level', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year
                        ]);
                    break;
                }
            break;

            case 'sumberdana':
                switch ($unit) {
                    case 'eselon1':
                        $data = Cache::remember('ReportSDanaPDF', 3600, function () use ($year) {
                            return DB::table('monira_ref_sumber_dana')
                            ->leftjoin(DB::raw("(
                                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.SumberDana
                                        FROM monira_data_dipa
                                            WHERE TA=$year AND Revision=0
                                                GROUP BY SumberDana) as monira_data_dipa_awal
                                        "),
                                            'monira_data_dipa_awal.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana' )
                            ->leftjoin(DB::raw("(
                                    SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.SumberDana
                                        FROM monira_data_dipa
                                            WHERE TA=$year AND IsActive=1
                                                GROUP BY SumberDana) as monira_data_dipa
                                        "),
                                            'monira_data_dipa.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana' )
                            ->leftjoin(DB::raw("(
                                    SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.SumberDana
                                        FROM monira_data_belanja
                                            WHERE TA=$year AND Output<>'ZZZ' GROUP BY SumberDana) as monira_data_belanja
                                        "),
                                            'monira_data_belanja.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana')
                            ->selectRaw("
                                        KodeSumberDana as Kode,NamaSumberDana as Keterangan,
                                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                                        IFNULL( TRUNCATE ( ( Realisasi/Pagu ) * 100, 2 ), 0.00 ) as Persen
                                        ")
                            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                            ->get();
                            });

                        return view('reports.report-excell-one-level', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year
                        ]);
                    break;
                }
            break;

            case 'belanja_covid':
                switch ($unit) {
                    case 'eselon1':
                        $data = Cache::remember('belanja_covid', 3600, function () use ($year) {
                            return DB::select(DB::raw("
                                SELECT
                                monira_ref_belanja.id as Kode,monira_ref_belanja.Belanja as Keterangan,
                                monira_ref_kegiatan.KdKegiatan,monira_ref_kegiatan.NamaKegiatan,
                                monira_ref_output.KdOutput,monira_ref_output.NamaOutput,
                                monira_ref_kewenangan.IdKewenangan as KdKewenangan,monira_ref_kewenangan.NamaKewenangan,
                                monira_ref_akun.KdAkun,monira_ref_akun.NamaAkun,
                                monira_ref_sumber_dana.ShortKode as KdSumberDana,monira_ref_sumber_dana.NamaSumberDana,
                                sum(PaguAwal) as PaguAwal,
                                sum(Pagu) as Pagu,
                                sum(Realisasi) as Realisasi,
                                sum(Prognosa) as Prognosa,
                                sum(Pagu)-Sum(Realisasi) as Sisa,
                                sum(Prognosa)-Sum(Realisasi) as SisaPrognosa,
                                sum(Realisasi)/Sum(pagu)*100 as Persen,
                                sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa
                                FROM
                                (
                                (
                                SELECT monira_data_dipa.Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi, 0 as Prognosa
                                FROM monira_data_dipa
                                WHERE TA=$year AND Revision=0
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_dipa.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi, 0 as Prognosa
                                FROM monira_data_dipa
                                WHERE TA=$year AND IsActive=1
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_belanja.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi, 0 as Prognosa
                                FROM monira_data_belanja
                                WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_prognosa.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, 0 as Pagu, 0 as Realisasi,sum(monira_data_prognosa.Amount) as Prognosa
                                FROM monira_data_prognosa
                                WHERE TA=$year
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                ) as UnionData
                                LEFT JOIN monira_ref_belanja ON monira_ref_belanja.id=UnionData.Belanja
                                LEFT JOIN monira_ref_sumber_dana ON monira_ref_sumber_dana.KodeSumberDana=UnionData.SumberDana
                                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=UnionData.Kegiatan
                                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=UnionData.Output
                                LEFT JOIN monira_ref_kewenangan ON monira_ref_kewenangan.IdKewenangan=UnionData.Kewenangan
                                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                                WHERE (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0 OR Prognosa <> 0) AND monira_ref_akun.isCovid=1
                                GROUP BY
                                monira_ref_belanja.Belanja
                            "));
                        });

                        return view('reports.report-excell-one-level', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year
                        ]);
                    break;
                }
            break;

            case 'kegiatan_covid':
                switch ($unit) {
                    case 'eselon1':
                        $data = Cache::remember('kegiatan_covid', 3600, function () use ($year) {
                            return DB::select(DB::raw("
                                SELECT
                                monira_ref_belanja.id as KdBelanja,monira_ref_belanja.Belanja as NamaBelanja,
                                monira_ref_kegiatan.KdKegiatan as Kode,monira_ref_kegiatan.NamaKegiatan as Keterangan,
                                monira_ref_output.KdOutput,monira_ref_output.NamaOutput,
                                monira_ref_kewenangan.IdKewenangan as KdKewenangan,monira_ref_kewenangan.NamaKewenangan,
                                monira_ref_akun.KdAkun,monira_ref_akun.NamaAkun,
                                monira_ref_sumber_dana.ShortKode as KdSumberDana,monira_ref_sumber_dana.NamaSumberDana,
                                sum(PaguAwal) as PaguAwal,
                                sum(Pagu) as Pagu,
                                sum(Realisasi) as Realisasi,
                                sum(Prognosa) as Prognosa,
                                sum(Pagu)-Sum(Realisasi) as Sisa,
                                sum(Prognosa)-Sum(Realisasi) as SisaPrognosa,
                                sum(Realisasi)/Sum(pagu)*100 as Persen,
                                sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa
                                FROM
                                (
                                (
                                SELECT monira_data_dipa.Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi, 0 as Prognosa
                                FROM monira_data_dipa
                                WHERE TA=$year AND Revision=0
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_dipa.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi, 0 as Prognosa
                                FROM monira_data_dipa
                                WHERE TA=$year AND IsActive=1
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_belanja.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi, 0 as Prognosa
                                FROM monira_data_belanja
                                WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_prognosa.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, 0 as Pagu, 0 as Realisasi,sum(monira_data_prognosa.Amount) as Prognosa
                                FROM monira_data_prognosa
                                WHERE TA=$year
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                ) as UnionData
                                LEFT JOIN monira_ref_belanja ON monira_ref_belanja.id=UnionData.Belanja
                                LEFT JOIN monira_ref_sumber_dana ON monira_ref_sumber_dana.KodeSumberDana=UnionData.SumberDana
                                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=UnionData.Kegiatan
                                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=UnionData.Output
                                LEFT JOIN monira_ref_kewenangan ON monira_ref_kewenangan.IdKewenangan=UnionData.Kewenangan
                                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                                WHERE (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0 OR Prognosa <> 0) AND monira_ref_akun.isCovid=1
                                GROUP BY
                                monira_ref_kegiatan.KdKegiatan
                            "));
                        });

                        return view('reports.report-excell-one-level', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year
                        ]);
                    break;
                }
            break;

            case 'sumberdana_covid':
                switch ($unit) {
                    case 'eselon1':
                        $data = Cache::remember('sumberdana_covid', 3600, function () use ($year) {
                            return DB::select(DB::raw("
                                SELECT
                                monira_ref_belanja.id as KdBelanja,monira_ref_belanja.Belanja as NamaBelanja,
                                monira_ref_kegiatan.KdKegiatan,monira_ref_kegiatan.NamaKegiatan,
                                monira_ref_output.KdOutput,monira_ref_output.NamaOutput,
                                monira_ref_kewenangan.IdKewenangan as KdKewenangan,monira_ref_kewenangan.NamaKewenangan,
                                monira_ref_akun.KdAkun,monira_ref_akun.NamaAkun,
                                monira_ref_sumber_dana.ShortKode as Kode,monira_ref_sumber_dana.NamaSumberDana as Keterangan,
                                sum(PaguAwal) as PaguAwal,
                                sum(Pagu) as Pagu,
                                sum(Realisasi) as Realisasi,
                                sum(Prognosa) as Prognosa,
                                sum(Pagu)-Sum(Realisasi) as Sisa,
                                sum(Prognosa)-Sum(Realisasi) as SisaPrognosa,
                                sum(Realisasi)/Sum(pagu)*100 as Persen,
                                sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa
                                FROM
                                (
                                (
                                SELECT monira_data_dipa.Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi, 0 as Prognosa
                                FROM monira_data_dipa
                                WHERE TA=$year AND Revision=0
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_dipa.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi, 0 as Prognosa
                                FROM monira_data_dipa
                                WHERE TA=$year AND IsActive=1
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_belanja.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi, 0 as Prognosa
                                FROM monira_data_belanja
                                WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                UNION ALL
                                (
                                SELECT monira_data_prognosa.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, 0 as Pagu, 0 as Realisasi,sum(monira_data_prognosa.Amount) as Prognosa
                                FROM monira_data_prognosa
                                WHERE TA=$year
                                GROUP BY Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana
                                )
                                ) as UnionData
                                LEFT JOIN monira_ref_belanja ON monira_ref_belanja.id=UnionData.Belanja
                                LEFT JOIN monira_ref_sumber_dana ON monira_ref_sumber_dana.KodeSumberDana=UnionData.SumberDana
                                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=UnionData.Kegiatan
                                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=UnionData.Output
                                LEFT JOIN monira_ref_kewenangan ON monira_ref_kewenangan.IdKewenangan=UnionData.Kewenangan
                                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                                WHERE (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0 OR Prognosa <> 0) AND monira_ref_akun.isCovid=1
                                GROUP BY
                                monira_ref_sumber_dana.ShortKode
                                ORDER BY monira_ref_sumber_dana.KodeSumberDana ASC
                            "));
                        });

                        return view('reports.report-excell-one-level', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year
                        ]);
                    break;
                }
            break;


            case 'ranking':
                switch ($unit) {
                    case 'eselon1':
                        $data = Cache::remember('ranking_satker',3600, function () use ($year) {
                            return DB::table('monira_ref_satker')
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.KdSatker
                                            FROM monira_data_dipa
                                                WHERE TA=$year AND Revision=0
                                                    GROUP BY KdSatker) as monira_data_dipa_awal
                                            "),
                                                'monira_data_dipa_awal.KdSatker', '=', 'monira_ref_satker.KodeSatker' )
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
                                ->leftjoin('monira_ref_wilayah','monira_ref_wilayah.KodeWilayah','=','monira_ref_satker.KodeWilayah')
                                ->selectRaw("

                                            KodeSatker,NamaSatuanKerja,WilayahName,
                                            IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                                            (Realisasi/Pagu)*100 as Persen
                                            ")
                                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                                ->orderBy('Persen','DESC')
                                ->get();
                                });

                    break;
                    case 'propinsi':
                        $data = Cache::remember('ranking_propinsi',3600, function () use ($year) {
                            return DB::table('monira_ref_wilayah')
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah
                                            FROM monira_data_dipa
                                                WHERE TA=$year AND Revision=0
                                                    GROUP BY KodeWilayah) as monira_data_dipa_awal
                                            "),
                                                'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah
                                            FROM monira_data_dipa
                                                WHERE TA=$year AND IsActive=1
                                                    GROUP BY KodeWilayah) as monira_data_dipa
                                            "),
                                                'monira_data_dipa.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
                                ->leftjoin(DB::raw("(
                                        SELECT sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah
                                            FROM monira_data_belanja
                                                WHERE TA=$year AND Output<>'ZZZ' GROUP BY KodeWilayah) as monira_data_belanja
                                            "),
                                                'monira_data_belanja.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
                                ->selectRaw("
                                            CONCAT(monira_ref_wilayah.KodeWilayah,'00') AS KodeWilayah,WilayahName,
                                            IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                                            (Realisasi/Pagu)*100 as Persen
                                            ")
                                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                                ->orderBy('Persen','DESC')
                                ->get();
                                });
                    break;
                    case 'satker':
                        $data_sql = Cache::remember('ranking_propinsi_satker',3600, function () use ($year) {
                            return DB::table('monira_ref_satker')
                                    ->leftjoin(DB::raw("(
                                            SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.KdSatker
                                                FROM monira_data_dipa
                                                    WHERE TA=$year AND Revision=0
                                                        GROUP BY KdSatker) as monira_data_dipa_awal
                                                "),
                                                    'monira_data_dipa_awal.KdSatker', '=', 'monira_ref_satker.KodeSatker' )
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
                                    ->leftjoin('monira_ref_wilayah','monira_ref_wilayah.KodeWilayah','=','monira_ref_satker.KodeWilayah')
                                    ->selectRaw("
                                                monira_ref_wilayah.KodeWilayah as KodeHeader,
                                                WilayahName as NamaHeader,KodeSatker as Kode,NamaSatuanKerja as Keterangan,
                                                IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                                                (Realisasi/Pagu)*100 as Persen
                                                ")
                                    ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                                    ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                                    ->orderBy(DB::raw("monira_ref_wilayah.KodeWilayah,Persen"),'DESC')
                                    ->get();

                                });

                            $data = NestCollection($data_sql,'2');
                    break;
                }

                return view('reports.report-excell-ranking', [
                    'data'    => $data,
                    'unit'    => $unit,
                    'segment' => $segment,
                    'year'    => $year
                ]);

            break;

            case 'pnbp':
                switch ($unit) {
                    case 'eselon1':
                        $data = SimpleCurl::get('http://datacenter.keuanganhubla.com/api/api/get_data_e1_bytahun/'.$year)->getResponseAsCollection();
                        return view('reports.report-excell-pnbp', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year
                        ]);
                    break;
                }
            break;

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
                            'year'    => $year
                        ]);
            break;


            case 'rpd':
                switch ($unit) {
                    case 'satker':
                        $data = RefWilayah::
                        with(['satker.pagupnbp' => function($query) use($year) {
                            $query->where('TA',$year);
                        }])
                        ->with(['satker.belanjapnbp' => function($query) use($year) {
                            $query->where('TA',$year);
                        }])
                        ->with(['satker.rpdpnbp' => function($query) use($year) {
                            $query->where('tahun',$year);
                        }])
                        ->with(['satker.mp' => function($query) use($year) {
                            $query->where('TA',$year);
                        }])
                        ->get();
                        return view('reports.report-excell-rpd-mp', [
                            'data'    => $data,
                            'segment' => $segment,
                            'year'    => $year
                        ]);
                    break;
                }
            break;

        }
    }
}


