<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Auth;
use App\Models\PaguDipa;
use App\Models\PendapatanDipa;
use App\Models\RefBelanja;
use Illuminate\Support\Facades\DB;
use SimpleCurl;
use LarapexChart;
use Illuminate\Support\Facades\Cache;
use App\Models\BelanjaDipa;
use App\Models\DataPrognosa;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExcell;
use App\Exports\ReportExcellSpending;
use App\Exports\ReportExcellPrognosa;
use Illuminate\Support\Carbon;
use App\Models\DataMessage;
use App\Models\DataMessageAttachment;
use App\Models\DataMessageSatker;
use App\Models\PaguDipaPadatKarya;
use App\Models\RefNamaBulan;

class ReportController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(empty($request->session()->get('setyear')  )) {
                $year = DATE('Y');
            } else {
                $year = $request->session()->get('setyear');
            }
            // dd($tahun);

            $this->data = array('setyear' => $year);

            View:: share(array(
                'profile' => Auth:: user()->username,
                'setyear' => $year,
                'today'   => DATE ('Y'),
                ));
            return $next($request);
        });

    }

    public function index($type,$unit,$segment)
    {
        $year = $this->data['setyear'];
        $date = Carbon::now()->format('dmY his');
        $KdSatker = Auth:: user()->kdsatker;

        switch ($segment) {
            case 'belanja':
                $data = Cache::remember('ReportBelanjaPDF_'.Auth:: user()->kdsatker,3600, function () use ($year, $KdSatker) {
                    return DB::table('monira_ref_belanja')
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Belanja
                                    FROM monira_data_dipa
                                        WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0 AND KdSatker=$KdSatker
                                            GROUP BY Belanja) as monira_data_dipa_awal
                                    "),
                                        'monira_data_dipa_awal.Belanja', '=', 'monira_ref_belanja.id' )
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Belanja
                                    FROM monira_data_dipa
                                        WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1 AND KdSatker=$KdSatker
                                            GROUP BY Belanja) as monira_data_dipa
                                    "),
                                        'monira_data_dipa.Belanja', '=', 'monira_ref_belanja.id' )
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Belanja
                                    FROM monira_data_belanja
                                        WHERE TA=$year AND KdSatker=$KdSatker  AND KdSatker=$KdSatker AND Output<>'ZZZ'
                                            GROUP BY Belanja) as monira_data_belanja
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
                break;

            case 'kegiatan':
                $data = Cache::remember('ReportKegiatanPDF_'.Auth:: user()->kdsatker, 3600, function () use ($year,$KdSatker) {
                    return DB::table('monira_ref_kegiatan')
                    ->leftjoin(DB::raw("(
                            SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Kegiatan
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0 AND KdSatker=$KdSatker
                                        GROUP BY Kegiatan) as monira_data_dipa_awal
                                "),
                                    'monira_data_dipa_awal.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan' )
                    ->leftjoin(DB::raw("(
                            SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Kegiatan
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1 AND KdSatker=$KdSatker
                                        GROUP BY Kegiatan) as monira_data_dipa
                                "),
                                    'monira_data_dipa.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan' )
                    ->leftjoin(DB::raw("(
                            SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Kegiatan
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker   AND KdSatker=$KdSatker
                                        GROUP BY Kegiatan) as monira_data_belanja
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
                break;

                case 'sumberdana':
                    $data = Cache::remember('ReportSDanaPDF_'.Auth:: user()->kdsatker, 3600, function () use ($year,$KdSatker) {
                        return DB::table('monira_ref_sumber_dana')
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.SumberDana
                                    FROM monira_data_dipa
                                        WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0 AND KdSatker=$KdSatker
                                            GROUP BY SumberDana) as monira_data_dipa_awal
                                    "),
                                        'monira_data_dipa_awal.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana' )
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.SumberDana
                                    FROM monira_data_dipa
                                        WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1 AND KdSatker=$KdSatker
                                            GROUP BY SumberDana) as monira_data_dipa
                                    "),
                                        'monira_data_dipa.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana' )
                        ->leftjoin(DB::raw("(
                                SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.SumberDana
                                    FROM monira_data_belanja
                                        WHERE TA=$year AND KdSatker=$KdSatker  AND Output<>'ZZZ' AND KdSatker=$KdSatker
                                            GROUP BY SumberDana) as monira_data_belanja
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
                    break;

                    case 'ranking':

                        switch ($unit) {
                            case 'eselon1':
                                $data = Cache::remember('Reportranking_satkerPDF_'.Auth:: user()->kdsatker,3600, function () use ($year, $KdSatker) {
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
                                    $data = Cache::remember('ranking_propinsi_'.Auth:: user()->kdsatker,3600, function () use ($year, $KdSatker) {
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
                                    $data_sql = Cache::remember('ranking_propinsi_satker_'.Auth:: user()->kdsatker,3600, function () use ($year, $KdSatker) {
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

                        break;


                    case 'pnbp':
                        $data = SimpleCurl::get('http://datacenter.keuanganhubla.com/api/api/get_data_satker_bytahun/'.$year.'/'.$KdSatker)->getResponseAsCollection();
                        break;

        }

        switch ($unit) {
            case 'eselon1':
                switch ($type) {
                    case 'pdf':
                        $pdf = PDF::loadView('reports.report-pdf', compact('data','year','unit','segment'));
                        return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                        break;
                    case 'excell':
                        return Excel::download(new ReportExcell($year,$segment,$unit), 'Rekap Monira '.$date.'.xlsx');
                        break;
                }
                break;

            case 'propinsi':
                switch ($type) {
                    case 'pdf':
                        $pdf = PDF::loadView('reports.report-pdf', compact('data','year','unit','segment'));
                        return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                        break;
                    case 'excell':
                        return Excel::download(new ReportExcell($year,$segment,$unit), 'Rekap Monira '.$date.'.xlsx');
                        break;
                }
                break;

            case 'satker':
                switch ($type) {
                    case 'pdf':
                        $pdf = PDF::loadView('reports.report-pdf', compact('data','year','unit','segment'));
                        return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                        break;
                    case 'excell':
                        return Excel::download(new ReportExcell($year,$segment,$unit), 'Rekap Monira '.$date.'.xlsx');
                        break;
                }
                break;

        }

    }


    public function spending($type,$unit,$segment,$month)
    {
        // dd($month);
        $date = Carbon::now()->format('dmY his');
        $year = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;
        $sql = SqlGroup($unit);
        switch($segment) {
            case "belanja" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_belanja.id as Kode,monira_ref_belanja.Belanja as Keterangan,
                    sum(PaguAwal) as PaguAwal,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen
                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Belanja,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Belanja, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Belanja, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                    "KodeHeader"    => $data->KodeHeader ?? '',
                    "NamaHeader"    => $data->NamaHeader ?? '',
                    "KodeSubHeader" => $data->KodeSubHeader ?? '',
                    "NamaSubHeader" => $data->NamaSubHeader ?? '',
                    "Kode"          => $data->Kode,
                    "Keterangan"    => $data->Keterangan,
                    "PaguAwal"      => $data->PaguAwal,
                    "Pagu"          => $data->Pagu,
                    "Realisasi"     => $data->Realisasi,
                    "Sisa"          => $data->Sisa,
                    "Persen"        => $data->Persen,
                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;

            case "sumberdana" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_sumber_dana.KodeSumberDana as Kode, monira_ref_sumber_dana.NamaSumberDana as Keterangan,
                    sum(PaguAwal) as PaguAwal,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen
                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.SumberDana,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.SumberDana, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.SumberDana, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                    "KodeHeader"    => $data->KodeHeader ?? '',
                    "NamaHeader"    => $data->NamaHeader ?? '',
                    "KodeSubHeader" => $data->KodeSubHeader ?? '',
                    "NamaSubHeader" => $data->NamaSubHeader ?? '',
                    "Kode"          => $data->Kode,
                    "Keterangan"    => $data->Keterangan,
                    "PaguAwal"      => $data->PaguAwal,
                    "Pagu"          => $data->Pagu,
                    "Realisasi"     => $data->Realisasi,
                    "Sisa"          => $data->Sisa,
                    "Persen"        => $data->Persen,
                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;

            case "kegiatan" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_kegiatan.KdKegiatan as Kode, monira_ref_kegiatan.NamaKegiatan as Keterangan,
                    sum(PaguAwal) as PaguAwal,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen
                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kegiatan,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kegiatan, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kegiatan, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                ) as UnionData
                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=UnionData.Kegiatan
                ".$sql['join']."
                WHERE (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)
                GROUP BY
                ".$sql['groupFinal']." monira_ref_kegiatan.KdKegiatan
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"    => $data->KodeHeader ?? '',
                    "NamaHeader"    => $data->NamaHeader ?? '',
                    "KodeSubHeader" => $data->KodeSubHeader ?? '',
                    "NamaSubHeader" => $data->NamaSubHeader ?? '',
                    "Kode"          => $data->Kode,
                    "Keterangan"    => $data->Keterangan,
                    "PaguAwal"      => $data->PaguAwal,
                    "Pagu"          => $data->Pagu,
                    "Realisasi"     => $data->Realisasi,
                    "Sisa"          => $data->Sisa,
                    "Persen"        => $data->Persen,
                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;

            case "output" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_output.KdOutput as Kode, monira_ref_output.NamaOutput as Keterangan,
                    sum(PaguAwal) as PaguAwal,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen
                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Output,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Output, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Output, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                ) as UnionData
                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=UnionData.Output
                ".$sql['join']."
                WHERE (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)
                GROUP BY
                ".$sql['groupFinal']." monira_ref_output.KdOutput
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"    => $data->KodeHeader ?? '',
                    "NamaHeader"    => $data->NamaHeader ?? '',
                    "KodeSubHeader" => $data->KodeSubHeader ?? '',
                    "NamaSubHeader" => $data->NamaSubHeader ?? '',
                    "Kode"          => $data->Kode,
                    "Keterangan"    => $data->Keterangan,
                    "PaguAwal"      => $data->PaguAwal,
                    "Pagu"          => $data->Pagu,
                    "Realisasi"     => $data->Realisasi,
                    "Sisa"          => $data->Sisa,
                    "Persen"        => $data->Persen,
                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;

            case "kewenangan" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_kewenangan.IdKewenangan as Kode, monira_ref_kewenangan.NamaKewenangan as Keterangan,
                    sum(PaguAwal) as PaguAwal,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen
                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kewenangan,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kewenangan, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kewenangan, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                ) as UnionData
                LEFT JOIN monira_ref_kewenangan ON monira_ref_kewenangan.IdKewenangan=UnionData.Kewenangan
                ".$sql['join']."
                WHERE (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)
                GROUP BY
                ".$sql['groupFinal']." monira_ref_kewenangan.IdKewenangan
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"    => $data->KodeHeader ?? '',
                    "NamaHeader"    => $data->NamaHeader ?? '',
                    "KodeSubHeader" => $data->KodeSubHeader ?? '',
                    "NamaSubHeader" => $data->NamaSubHeader ?? '',
                    "Kode"          => $data->Kode,
                    "Keterangan"    => $data->Keterangan,
                    "PaguAwal"      => $data->PaguAwal,
                    "Pagu"          => $data->Pagu,
                    "Realisasi"     => $data->Realisasi,
                    "Sisa"          => $data->Sisa,
                    "Persen"        => $data->Persen,
                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;


            case "akun" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    monira_ref_akun.KdAkun as Kode, monira_ref_akun.NamaAkun as Keterangan,
                    sum(PaguAwal) as PaguAwal,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen
                FROM
                (
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Akun,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Akun, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                ) as UnionData
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                ".$sql['join']."
                WHERE (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)
                GROUP BY
                ".$sql['groupFinal']." monira_ref_akun.KdAkun
                ".$sql['orderBy']."
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"    => $data->KodeHeader ?? '',
                    "NamaHeader"    => $data->NamaHeader ?? '',
                    "KodeSubHeader" => $data->KodeSubHeader ?? '',
                    "NamaSubHeader" => $data->NamaSubHeader ?? '',
                    "Kode"          => $data->Kode,
                    "Keterangan"    => $data->Keterangan,
                    "PaguAwal"      => $data->PaguAwal,
                    "Pagu"          => $data->Pagu,
                    "Realisasi"     => $data->Realisasi,
                    "Sisa"          => $data->Sisa,
                    "Persen"        => $data->Persen,
                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
            break;
            }

            switch ($unit) {
                case 'eselon1':
                    switch ($type) {
                        case 'pdf':
                            $pdf = PDF::loadView('reports.report-pdf-spending', compact('data','year','unit','segment','month'));
                            return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellSpending($year,$segment,$unit,$month), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;

                case 'propinsi':
                    $data = NestCollection($object,'2');
                    switch ($type) {
                        case 'pdf':
                            set_time_limit(300);
                            $pdf = PDF::loadView('reports.report-pdf-spending', compact('data','year','unit','segment','month'));
                            return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellSpending($year,$segment,$unit,$month), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;
                case 'satker':
                    $data = NestCollection($object,'3');
                    switch ($type) {
                        case 'pdf':
                            set_time_limit(300);
                            $pdf = PDF::loadView('reports.report-pdf-spending', compact('data','year','unit','segment','month'));
                            return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellSpending($year,$segment,$unit,$month), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;
            }


    }

    public function prognosa($type,$unit,$segment)
    {
        $year = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;


        $dataSQL = Cache::remember('prognosa_satker'.Auth:: user()->kdsatker, 3600, function () use ($year,$KdSatker) {
            return DB::table('monira_ref_output')
            ->leftjoin('monira_ref_kegiatan','monira_ref_output.KdKegiatan','monira_ref_kegiatan.KdKegiatan')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Kegiatan,monira_data_dipa.Output,monira_data_dipa.Akun,monira_data_dipa.SumberDana,monira_data_dipa.Kewenangan,monira_data_dipa.Program
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1 AND KdSatker=$KdSatker
                                GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_dipa_awal
                        "), function($join){
                            $join->on('monira_data_dipa_awal.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                            $join->on('monira_data_dipa_awal.Output', '=', 'monira_ref_output.KdOutput');

                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_prognosa.Amount) as Prognosa,monira_data_prognosa.Kegiatan,monira_data_prognosa.Output,monira_data_prognosa.Akun,monira_data_prognosa.SumberDana,monira_data_prognosa.Kewenangan,monira_data_prognosa.Program
                        FROM monira_data_prognosa
                            WHERE TA=$year AND KdSatker=$KdSatker
                                GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_prognosa
                        "), function($join){
                            $join->on('monira_data_prognosa.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                            $join->on('monira_data_prognosa.Output', '=', 'monira_ref_output.KdOutput');
                            $join->on('monira_data_prognosa.Akun', '=', 'monira_data_dipa_awal.Akun');
                            $join->on('monira_data_prognosa.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                            $join->on('monira_data_prognosa.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                            $join->on('monira_data_prognosa.Program', '=', 'monira_data_dipa_awal.Program');

                        })
            ->leftjoin(DB::raw("(
                    SELECT monira_data_justifikasi.Justifikasi,monira_data_justifikasi.Kegiatan,monira_data_justifikasi.Output,monira_data_justifikasi.Akun,monira_data_justifikasi.SumberDana,monira_data_justifikasi.Kewenangan,monira_data_justifikasi.Program
                        FROM monira_data_justifikasi
                            WHERE TA=$year AND KdSatker=$KdSatker
                                GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_justifikasi
                        "), function($join){
                            $join->on('monira_data_justifikasi.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                            $join->on('monira_data_justifikasi.Output', '=', 'monira_ref_output.KdOutput');
                            $join->on('monira_data_justifikasi.Akun', '=', 'monira_data_dipa_awal.Akun');
                            $join->on('monira_data_justifikasi.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                            $join->on('monira_data_justifikasi.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                            $join->on('monira_data_justifikasi.Program', '=', 'monira_data_dipa_awal.Program');
                        })
            ->leftjoin('monira_ref_akun','KdAkun','monira_data_dipa_awal.Akun')
            ->leftjoin('monira_ref_sumber_dana','KodeSumberDana','monira_data_dipa_awal.SumberDana')
            ->leftjoin('monira_ref_kewenangan','IdKewenangan','monira_data_dipa_awal.Kewenangan')
            ->leftjoin('monira_ref_program','monira_ref_program.KdProgram','monira_data_dipa_awal.Program')
            ->selectRaw("
                        monira_ref_kegiatan.KdKegiatan as KodeHeader,monira_ref_kegiatan.NamaKegiatan as NamaHeader,
                        monira_ref_output.KdOutput as KodeSubHeader,monira_ref_output.NamaOutput as NamaSubHeader,
                        monira_ref_sumber_dana.KodeSumberDana, monira_ref_sumber_dana.ShortKode,
                        monira_ref_akun.KdAkun as Kode, monira_ref_akun.NamaAkun as Keterangan,
                        monira_ref_kewenangan.IdKewenangan as KodeKewenangan, monira_ref_kewenangan.NamaKewenangan,
                        monira_ref_program.KdProgram as KodeProgram, monira_ref_program.NamaProgram,
                        ifnull(PaguAwal,0) as Pagu,
                        (monira_data_prognosa.Prognosa/PaguAwal)*100 as Persen,
                        ifnull(monira_data_prognosa.Prognosa,0) as Prognosa,
                        monira_data_justifikasi.Justifikasi
                        ")
            ->where('PaguAwal','!=','null')
            ->orderby('KodeHeader','ASC')
            ->orderby('KdOutput','ASC')
            ->orderby('KdAkun','ASC')
            ->get();
        });


        $data = NestCollection($dataSQL,'prognosa');

        // return response()->json($dataSQL);

                $level = "Propinsi";
                $pdf = PDF::loadView('reports.report-pdf-prognosa-satker', compact('data','year'));
                return $pdf->stream("Laporan Prognosa ".$unit." Per ".$segment.".pdf");

                // return view('apps.prognosa-satker',compact('data'));
    }

    public function prognosaold($type,$unit,$segment)
    {
        $date = Carbon::now()->format('dmY his');
        $year = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;
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
                                    WHERE TA=$year AND KdSatker=$KdSatker
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Belanja, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Belanja, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.SumberDana, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.SumberDana, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kegiatan, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kegiatan, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Output, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Output, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kewenangan, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kewenangan, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Akun, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Akun, 0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker  AND Output<>'ZZZ'
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
                    switch ($type) {
                        case 'pdf':
                            $pdf = PDF::loadView('reports.report-pdf-prognosa', compact('data','year','unit','segment'));
                            return $pdf->stream("Laporan Prognosa Monira Per ".$unit." Per ".$segment.".pdf");
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellPrognosa($year,$segment,$unit), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;

                case 'propinsi':
                    $data = NestCollection($object,'2');
                    switch ($type) {
                        case 'pdf':
                            set_time_limit(300);
                            $pdf = PDF::loadView('reports.report-pdf-prognosa', compact('data','year','unit','segment'));
                            return $pdf->stream("Laporan Prognosa Monira Per ".$unit." Per ".$segment.".pdf");
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellPrognosa($year,$segment,$unit), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;
                case 'satker':
                    $data = NestCollection($object,'3');
                    switch ($type) {
                        case 'pdf':
                            set_time_limit(300);
                            $pdf = PDF::loadView('reports.report-pdf-prognosa', compact('data','year','unit','segment'));
                            return $pdf->stream("Laporan Prognosa Monira Per ".$unit." Per ".$segment.".pdf");
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellPrognosa($year,$segment,$unit), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;
            }


    }

    public function reporting($type,$unit,$segment,$month)
    {
        $year = $this->data['setyear'];
        $date = Carbon::now()->format('dmY his');
        $KdSatker = Auth:: user()->kdsatker;
        // dd($month);
        switch ($segment) {
            case 'covid':
                $dataSQL = Cache::remember('dipa_covid_satker'.Auth:: user()->kdsatker, 1, function () use ($year,$KdSatker) {
                    return DB::table('monira_ref_output')
                    ->leftjoin('monira_ref_kegiatan','monira_ref_output.KdKegiatan','monira_ref_kegiatan.KdKegiatan')
                    ->leftjoin(DB::raw("(
                            SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Kegiatan,monira_data_dipa.Output,monira_data_dipa.Akun,monira_data_dipa.SumberDana,monira_data_dipa.Kewenangan,monira_data_dipa.Program
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1 AND KdSatker=$KdSatker
                                        GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_dipa_awal
                                "), function($join){
                                    $join->on('monira_data_dipa_awal.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                                    $join->on('monira_data_dipa_awal.Output', '=', 'monira_ref_output.KdOutput');

                                })
                    ->leftjoin(DB::raw("(
                            SELECT monira_data_dipa_covid.BudgetType,monira_data_dipa_covid.idtable,monira_data_dipa_covid.guid,monira_data_dipa_covid.Amount,monira_data_dipa_covid.Uraian,monira_data_dipa_covid.Volume,monira_data_dipa_covid.Satuan,monira_data_dipa_covid.Kegiatan,monira_data_dipa_covid.Output,monira_data_dipa_covid.Akun,monira_data_dipa_covid.SumberDana,monira_data_dipa_covid.Kewenangan,monira_data_dipa_covid.Program
                                FROM monira_data_dipa_covid
                                    WHERE TA=$year AND KdSatker=$KdSatker ORDER BY monira_data_dipa_covid.idtable ASC) as monira_data_dipa_covid
                                "), function($join){
                                    $join->on('monira_data_dipa_covid.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                                    $join->on('monira_data_dipa_covid.Output', '=', 'monira_ref_output.KdOutput');
                                    $join->on('monira_data_dipa_covid.Akun', '=', 'monira_data_dipa_awal.Akun');
                                    $join->on('monira_data_dipa_covid.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                                    $join->on('monira_data_dipa_covid.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                                    $join->on('monira_data_dipa_covid.Program', '=', 'monira_data_dipa_awal.Program');

                                })
                    ->leftjoin(DB::raw("(
                        SELECT monira_data_belanja_covid.idtable,monira_data_belanja_covid.guid,sum(monira_data_belanja_covid.Amount) as pencairan,monira_data_belanja_covid.Uraian,sum(monira_data_belanja_covid.Volume) as Volume,monira_data_belanja_covid.Satuan,monira_data_belanja_covid.Kegiatan,monira_data_belanja_covid.Output,monira_data_belanja_covid.Akun,monira_data_belanja_covid.SumberDana,monira_data_belanja_covid.Kewenangan,monira_data_belanja_covid.Program
                            FROM monira_data_belanja_covid
                                WHERE TA=$year AND KdSatker=$KdSatker GROUP BY guid ORDER BY monira_data_belanja_covid.idtable ASC) as monira_data_belanja_covid
                            "), function($join){
                                $join->on('monira_data_belanja_covid.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                                $join->on('monira_data_belanja_covid.Output', '=', 'monira_ref_output.KdOutput');
                                $join->on('monira_data_belanja_covid.Akun', '=', 'monira_data_dipa_awal.Akun');
                                $join->on('monira_data_belanja_covid.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                                $join->on('monira_data_belanja_covid.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                                $join->on('monira_data_belanja_covid.Program', '=', 'monira_data_dipa_awal.Program');
                                $join->on('monira_data_belanja_covid.guid', '=', 'monira_data_dipa_covid.guid');

                            })
                    ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Kegiatan,monira_data_belanja.Output,monira_data_belanja.Akun,monira_data_belanja.SumberDana,monira_data_belanja.Kewenangan,monira_data_belanja.Program
                        FROM monira_data_belanja
                            WHERE TA=$year AND KdSatker=$KdSatker
                                GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                            $join->on('monira_data_belanja.Output', '=', 'monira_ref_output.KdOutput');
                            $join->on('monira_data_belanja.Akun', '=', 'monira_data_dipa_awal.Akun');
                            $join->on('monira_data_belanja.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                            $join->on('monira_data_belanja.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                            $join->on('monira_data_belanja.Program', '=', 'monira_data_dipa_awal.Program');

                        })
                    ->leftjoin(DB::RAW("(SELECT * FROM monira_ref_akun WHERE isCovid=1) as monira_ref_akun"),
                                function($join){
                                    $join->on('monira_ref_akun.KdAkun', '=', 'monira_data_dipa_awal.Akun');
                                })
                    ->leftjoin('monira_ref_sumber_dana','KodeSumberDana','monira_data_dipa_awal.SumberDana')
                    ->leftjoin('monira_ref_kewenangan','IdKewenangan','monira_data_dipa_awal.Kewenangan')
                    ->leftjoin('monira_ref_program','monira_ref_program.KdProgram','monira_data_dipa_awal.Program')
                    ->selectRaw("
                                monira_ref_kegiatan.KdKegiatan as KodeHeader,monira_ref_kegiatan.NamaKegiatan as NamaHeader,
                                monira_ref_output.KdOutput as KodeSubHeader,monira_ref_output.NamaOutput as NamaSubHeader,
                                monira_ref_sumber_dana.KodeSumberDana, monira_ref_sumber_dana.ShortKode,
                                monira_ref_akun.KdAkun as Kode, monira_ref_akun.NamaAkun as Keterangan,
                                monira_ref_kewenangan.IdKewenangan as KodeKewenangan, monira_ref_kewenangan.NamaKewenangan,
                                monira_ref_program.KdProgram as KodeProgram, monira_ref_program.NamaProgram,
                                monira_data_dipa_covid.guid,
                                monira_data_dipa_covid.Amount as PaguKegiatan,
                                monira_data_belanja_covid.pencairan as BelanjaKegiatan,
                                monira_data_dipa_covid.Uraian,
                                monira_data_dipa_covid.BudgetType as Catatan,
                                monira_data_dipa_covid.Volume as VolumePagu,
                                monira_data_belanja_covid.Volume as VolumeBelanja,
                                monira_data_dipa_covid.Satuan as SatuanPagu,
                                monira_data_belanja_covid.Satuan as SatuanBelanja,
                                ifnull(PaguAwal,0) as Pagu,
                                ifnull(Realisasi,0) as Realisasi,
                                (Realisasi/PaguAwal)*100 as Persen
                                ")
                    ->where('PaguAwal','!=','null')
                    ->whereNotNull('monira_ref_akun.KdAkun')
                    ->orderby('KodeHeader','ASC')
                    ->orderby('KdOutput','ASC')
                    ->orderby('KdAkun','ASC')
                    ->orderby('monira_data_dipa_covid.idtable','ASC')
                    ->get();
                });


                $data = NestCollection($dataSQL,'covid');
                // return response()->json($data);

                // $bulan = RefNamaBulan::get();
                // return view('apps.monitoring-covid',compact('data'));
                $pdf = PDF::loadView('reports.report-pdf-monitoring', compact('data','year','month','segment'));
                return $pdf->stream("Laporan Covid Monira Per ".$unit.".pdf");
                break;
            case 'padatkarya':
                $bulan = RefNamaBulan::get();
                $akun = PaguDipa::where('Belanja','52')->where('TA',$year)->where('isActive','1')->where('KdSatker',Auth:: user()->kdsatker)->groupby('Id')->with('keterangan')->get();
                $data = PaguDipaPadatKarya::with('akun.akun')->with('sumrealisasi')->with('akun')->with('realisasi.akun')->where('TA',$year)->where('KdSatker',Auth:: user()->kdsatker)
                ->leftjoin(DB::RAW("(SELECT nama as Propinsi,kode FROM monira_ref_desa) as monira_ref_desa_prop"),'monira_ref_desa_prop.kode',DB::raw("substr(KdLokasi,1,2)"))
                ->leftjoin(DB::RAW("(SELECT nama as Kabupaten,kode FROM monira_ref_desa) as monira_ref_desa_kab"),'monira_ref_desa_kab.kode',DB::raw("substr(KdLokasi,1,5)"))
                ->leftjoin(DB::RAW("(SELECT nama as Kecamatan,kode FROM monira_ref_desa) as monira_ref_desa_kec"),'monira_ref_desa_kec.kode',DB::raw("substr(KdLokasi,1,8)"))
                ->leftjoin(DB::RAW("(SELECT nama as Desa,kode FROM monira_ref_desa) as monira_ref_desa_nama"),'monira_ref_desa_nama.kode',DB::raw("substr(KdLokasi,1,13)"))
                ->get();

                // return response()->json($data);

                // return view('apps.monitoring-padat-karya',compact('bulan','akun','data'));
                $pdf = PDF::loadView('reports.report-pdf-monitoring', compact('bulan','data','year','akun','segment'));
                return $pdf->stream("Laporan Padat Karya Monira Per ".$unit.".pdf");
                break;

        }

    }

}
