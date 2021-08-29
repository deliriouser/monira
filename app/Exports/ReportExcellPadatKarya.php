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
use Illuminate\Support\Facades\Auth;
use App\Models\RefWilayah;

class ReportExcellPadatKarya implements FromView,ShouldAutoSize,WithEvents,WithColumnFormatting
{

    protected $year;
    protected $segment;
    protected $unit;
    protected $month;

    public function __construct(String $year,$segment,$unit,$month) {
        $this->year    = $year;
        $this->segment = $segment;
        $this->unit    = $unit;
        $this->month   = $month;
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

                // $event->sheet->styleCells(
                //     'B',
                //     [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                // );

                $event->sheet->styleCells(
                    'G',
                    [ 'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],],
                );

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
        ];
            break;
            }
    }

    public function view(): View
    {
        $year     = $this->year;
        $month    = $this->month;
        $segment  = $this->segment;
        $unit     = $this->unit;
        $sql = SqlPadatKarya($unit);
        switch($segment) {
            case "akun" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                ".$sql['selectList']."
                    monira_data_pakar_akun.Program,NamaProgram,
                    monira_data_pakar_akun.Kegiatan,NamaKegiatan,
                    monira_data_pakar_akun.Output,NamaOutput,
                    ShortKode as SumberDana,NamaSumberDana,
                    monira_data_pakar_akun.Akun,NamaAkun,
                    SUM(ifnull(Amount,0)) AS Pagu,
                    (ifnull(Realisasi,0)) AS Dsa,
                    (ifnull(Realisasi,0))/SUM(ifnull(Amount,0))*100 as Persen,
                    SUM(ifnull(Amount,0))-(ifnull(Realisasi,0)) as Sisa
                FROM monira_data_pakar_akun
                LEFT JOIN (
                        SELECT ".$sql['selectListInner']." Program,Kegiatan,Output,SumberDana,Akun, SUM(TotalPagu) as Realisasi
                        FROM monira_data_pakar_belanja
                        GROUP BY ".$sql['groupBy']." Program,Kegiatan,Output,SumberDana,Akun) as monira_data_pakar_belanja
                            ON ".$sql['onjoin']."
                LEFT JOIN monira_ref_program ON monira_ref_program.KdProgram=monira_data_pakar_akun.Program
                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=monira_data_pakar_akun.Kegiatan
                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=monira_data_pakar_akun.Output
                LEFT JOIN monira_ref_sumber_dana ON monira_ref_sumber_dana.KodeSumberDana=monira_data_pakar_akun.SumberDana
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=monira_data_pakar_akun.Akun
                ".$sql['join']."
                WHERE monira_data_pakar_akun.TA=$year
                GROUP BY ".$sql['groupBy']." Program,Kegiatan,Output,SumberDana,Akun
                "));

                // return response()->json($dataSQL);

                $data = collect($dataSQL)->map(function($data){
                    return [
                        "KodeWilayah"    => $data->KodeWilayah ?? '',
                        "NamaWilayah"    => $data->WilayahName ?? '',
                        "KodeSatker"     => $data->KdSatker ?? '',
                        "NamaSatker"     => $data->NamaSatuanKerja ?? '',
                        "KodeProgram"    => $data->Program ?? '',
                        "NamaProgram"    => $data->NamaProgram ?? '',
                        "KodeProgram"    => $data->Program ?? '',
                        "NamaProgram"    => $data->NamaProgram ?? '',
                        "KodeKegiatan"   => $data->Kegiatan ?? '',
                        "NamaKegiatan"   => $data->NamaKegiatan ?? '',
                        "KodeOutput"     => $data->Output ?? '',
                        "NamaOutput"     => $data->NamaOutput ?? '',
                        "KodeSumberDana" => $data->SumberDana ?? '',
                        "NamaSumberDana" => $data->NamaSumberDana ?? '',
                        "KodeAkun"       => $data->Akun ?? '',
                        "NamaAkun"       => $data->NamaAkun ?? '',
                        "Pagu"           => $data->Pagu,
                        "Dsa"            => $data->Dsa,
                        "Persen"         => $data->Persen,
                        "Sisa"           => $data->Sisa,
                    ];
                });
                $object = json_decode(json_encode($data), FALSE);

                // return response()->json($object);

                break;
                case "rekap" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                ".$sql['selectListRekap']."
                    SUM(Target_JumlahOrang) as Target_JumlahOrang,
                    SUM(Target_JumlahHari) as Target_JumlahHari,
                    SUM(Target_JumlahOrangHari) as Target_JumlahOrangHari,
                    SUM(Target_UpahHarian) as Target_UpahHarian,
                    SUM(Target_TotalBiayaUpah) as Target_TotalBiayaUpah,
                    SUM(Target_TotalBiayaLain) as Target_TotalBiayaLain,
                    SUM(Target_TotalBiayaUpah)/SUM(Target_TotalPagu)*100 as Target_PersenBiayaUpah,
                    SUM(Target_TotalPagu) as Target_TotalPagu,
                    SUM(Daser_JumlahOrang) as Daser_JumlahOrang,
                    SUM(Daser_JumlahHari) as Daser_JumlahHari,
                    SUM(Daser_JumlahOrangHari) as Daser_JumlahOrangHari,
                    SUM(Daser_UpahHarian) as Daser_UpahHarian,
                    SUM(Daser_TotalBiayaUpah) as Daser_TotalBiayaUpah,
                    SUM(Daser_TotalBiayaLain) as Daser_TotalBiayaLain,
                    SUM(Daser_TotalBiayaUpah)/SUM(Daser_TotalPagu)*100 as Daser_PersenBiayaUpah,
                    SUM(Daser_TotalPagu) as Daser_TotalPagu
                FROM(
                    (
                    SELECT
                        ".$sql['selectListInnerPagu']."
                        TA,SUM(JumlahOrang) as Target_JumlahOrang,SUM(JumlahHari) as Target_JumlahHari,Sum(JumlahOrangHari) as Target_JumlahOrangHari,AVG(UpahHarian) as Target_UpahHarian,SUM(TotalBiayaUpah) as Target_TotalBiayaUpah,SUM(TotalBiayaLain) as Target_TotalBiayaLain,0 as Target_PersenBiayaUpah,SUM(TotalPagu) as Target_TotalPagu,
                        0 as Daser_JumlahOrang,0 as Daser_JumlahHari,0 as Daser_JumlahOrangHari,0 as Daser_UpahHarian,0 as Daser_TotalBiayaUpah,0 as Daser_TotalBiayaLain,0 as Daser_PersenBiayaUpah,0 as Daser_TotalPagu
                    FROM monira_data_pakar_dipa
                    WHERE TA=$year
                    GROUP BY TA ".$sql['groupByInnerList']."
                    )
                    UNION ALL
                    (
                    SELECT
                        ".$sql['selectListInnerBelanja']."
                        TA,0 as Target_JumlahOrang,0 as Target_JumlahHari,0 as Target_JumlahOrangHari,0 as Target_UpahHarian,0 as Target_TotalBiayaUpah,0 as Target_TotalBiayaLain,0 as Target_PersenBiayaUpah,0 as Target_TotalPagu,
                        SUM(JumlahOrang) as Daser_JumlahOrang,SUM(JumlahHari) as Daser_JumlahHari,Sum(JumlahOrangHari) as Daser_JumlahOrangHari,AVG(UpahHarian) as Daser_UpahHarian,SUM(TotalBiayaUpah) as Daser_TotalBiayaUpah,SUM(TotalBiayaLain) as Daser_TotalBiayaLain,0 as Daser_PersenBiayaUpah,SUM(TotalPagu) as Daser_TotalPagu
                    FROM monira_data_pakar_belanja
                    WHERE TA=$year
                    GROUP BY TA ".$sql['groupByInnerList']."
                    )) as UnionData
                    ".$sql['joinRekap']."
                    GROUP BY ".$sql['groupByRekap']." TA



                "));

                // return response()->json($dataSQL);

                $data = collect($dataSQL)->map(function($data){
                    return [
                        "KodeWilayah"            => $data->KodeWilayah ?? '',
                        "NamaWilayah"            => $data->WilayahName ?? '',
                        "KodeSatker"             => $data->KdSatker ?? '',
                        "NamaSatker"             => $data->NamaSatuanKerja ?? '',
                        "Kabupaten"              => STRTOUPPER($data->Kabupaten ?? ''),
                        "Kecamatan"              => STRTOUPPER($data->Kecamatan ?? ''),
                        "Desa"                   => STRTOUPPER($data->Desa ?? ''),
                        "Target_JumlahOrang"     => $data->Target_JumlahOrang ?? '',
                        "Target_JumlahHari"      => $data->Target_JumlahHari ?? '',
                        "Target_JumlahOrangHari" => $data->Target_JumlahOrangHari ?? '',
                        "Target_UpahHarian"      => $data->Target_UpahHarian ?? '',
                        "Target_TotalBiayaUpah"  => $data->Target_TotalBiayaUpah ?? '',
                        "Target_TotalBiayaLain"  => $data->Target_TotalBiayaLain ?? '',
                        "Target_PersenBiayaUpah" => $data->Target_PersenBiayaUpah ?? '',
                        "Target_TotalPagu"       => $data->Target_TotalPagu ?? '',
                        "Daser_JumlahOrang"      => $data->Daser_JumlahOrang ?? '',
                        "Daser_JumlahHari"       => $data->Daser_JumlahHari ?? '',
                        "Daser_JumlahOrangHari"  => $data->Daser_JumlahOrangHari ?? '',
                        "Daser_UpahHarian"       => $data->Daser_UpahHarian ?? '',
                        "Daser_TotalBiayaUpah"   => $data->Daser_TotalBiayaUpah ?? '',
                        "Daser_TotalBiayaLain"   => $data->Daser_TotalBiayaLain ?? '',
                        "Daser_PersenBiayaUpah"  => $data->Daser_PersenBiayaUpah ?? '',
                        "Daser_TotalPagu"        => $data->Daser_TotalPagu ?? '',
                    ];
                });
                $object = json_decode(json_encode($data), FALSE);


                break;


                case "rincian" :

                    $data = RefWilayah::with(['satker.datapadatkarya' => function($query) use($year) {
                        $query->where('TA',$year);
                        $query->leftjoin(DB::RAW("(SELECT nama as Propinsi,kode FROM monira_ref_desa) as monira_ref_desa_prop"),'monira_ref_desa_prop.kode',DB::raw("substr(monira_data_pakar_dipa.KdLokasi,1,2)"));
                        $query->leftjoin(DB::RAW("(SELECT nama as Kabupaten,kode FROM monira_ref_desa) as monira_ref_desa_kab"),'monira_ref_desa_kab.kode',DB::raw("substr(monira_data_pakar_dipa.KdLokasi,1,5)"));
                        $query->leftjoin(DB::RAW("(SELECT nama as Kecamatan,kode FROM monira_ref_desa) as monira_ref_desa_kec"),'monira_ref_desa_kec.kode',DB::raw("substr(monira_data_pakar_dipa.KdLokasi,1,8)"));
                        $query->leftjoin(DB::RAW("(SELECT nama as Desa,kode FROM monira_ref_desa) as monira_ref_desa_nama"),'monira_ref_desa_nama.kode',DB::raw("substr(monira_data_pakar_dipa.KdLokasi,1,13)"));
                    }])
                    ->with(['satker.pagupadatkarya' => function($query) use($year) {
                        $query->where('TA',$year);
                    }])
                    // ->with('satker.datapadatkarya.akun')
                    // ->with('satker.datapadatkarya.akun.realisasi_akun')
                    // ->with('satker.datapadatkarya.akun')
                    ->with(['satker.realisasipadatkarya' => function($query) use($year) {
                        $query->where('TA',$year);
                    }])
                    ->get();
                    // return response()->json($data);

                    break;

            }

            switch ($unit) {
                case 'eselon1':
                    switch ($segment) {
                        case 'akun':
                            $data = NestCollection($object,'PadKaryaOne');
                            return view('reports.report-excell-padatkarya-one-level', [
                                'data'    => $data,
                                'unit'    => $unit,
                                'segment' => $segment,
                                'year'    => $year,
                                'month'   => $month
                            ]);
                            break;
                        case 'rekap':
                            $data = $object;
                            return view('reports.report-excell-padatkarya-rekap-one-level', [
                                'data'    => $data,
                                'unit'    => $unit,
                                'segment' => $segment,
                                'year'    => $year,
                                'month'   => $month
                            ]);
                            break;
                    }
                    break;
                case 'propinsi':
                    switch ($segment) {
                        case 'akun':
                            $data = NestCollection($object,'PadKaryaTwo');
                            return view('reports.report-excell-padatkarya-two-level', [
                                'data'    => $data,
                                'unit'    => $unit,
                                'segment' => $segment,
                                'year'    => $year,
                                'month'   => $month
                            ]);
                            break;
                        case 'rekap':
                            $data = NestCollection($object,'PadKaryaTwoRekap');
                            return view('reports.report-excell-padatkarya-rekap-two-level', [
                                'data'    => $data,
                                'unit'    => $unit,
                                'segment' => $segment,
                                'year'    => $year,
                                'month'   => $month
                            ]);
                            break;
                    }
                    break;
                case 'satker':
                    switch ($segment) {
                        case 'akun':
                            $data = NestCollection($object,'PadKaryaThree');
                            return view('reports.report-excell-padatkarya-three-level', [
                                'data'    => $data,
                                'unit'    => $unit,
                                'segment' => $segment,
                                'year'    => $year,
                                'month'   => $month
                            ]);
                            break;
                        case 'rekap':
                            $data = NestCollection($object,'PadKaryaThreeRekap');
                            return view('reports.report-excell-padatkarya-rekap-three-level', [
                                'data'    => $data,
                                'unit'    => $unit,
                                'segment' => $segment,
                                'year'    => $year,
                                'month'   => $month
                            ]);
                            break;
                        case 'rincian':
                            return view('reports.report-excell-padatkarya-rekap-rincian-level', [
                                'data'    => $data,
                                'unit'    => $unit,
                                'segment' => $segment,
                                'year'    => $year,
                                'month'   => $month
                            ]);
                            break;
                    }

                    break;
            }


    }
}


