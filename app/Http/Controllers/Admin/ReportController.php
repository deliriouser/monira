<?php

namespace App\Http\Controllers\Admin;

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
use App\Exports\ReportExcellCovid;
use App\Exports\ReportExcellPadatKarya;
use App\Exports\ReportExcellHarian;
use Illuminate\Support\Carbon;
use App\Models\RefWilayah;

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

        switch ($segment) {
            case 'belanja':
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
                break;

            case 'kegiatan':
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
                break;

                case 'belanja_covid':
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
                break;

                case 'kegiatan_covid':
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
                break;

                case 'sumberdana_covid':
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
                break;

                case 'sumberdana':
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
                    break;


                    case 'ranking':

                        switch ($unit) {
                            case 'eselon1':
                                $data = Cache::remember('Reportranking_satkerPDF',3600, function () use ($year) {
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

                        break;


                    case 'pnbp':
                        $data = SimpleCurl::get('http://datacenter.keuanganhubla.com/api/api/get_data_e1_bytahun/'.$year)->getResponseAsCollection();
                    break;

                    case 'rpd':
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
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Belanja, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Belanja, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.SumberDana, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.SumberDana, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kegiatan, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kegiatan, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Output, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Output, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kewenangan, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kewenangan, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Akun, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
        $date = Carbon::now()->format('dmY his');
        $year = $this->data['setyear'];
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


    public function covid($type,$unit,$segment,$month)
    {
        // dd($month);
        $date = Carbon::now()->format('dmY his');
        $year = $this->data['setyear'];
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
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Belanja,monira_data_dipa.Akun,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Belanja,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Belanja, monira_data_dipa.Akun,0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Belanja,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Belanja, monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Belanja,Akun
                    )
                ) as UnionData
                LEFT JOIN monira_ref_belanja ON monira_ref_belanja.id=UnionData.Belanja
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                ".$sql['join']."
                WHERE monira_ref_akun.isCovid='1'
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

                // return response()->json($object);

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
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.SumberDana,monira_data_dipa.Akun,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." SumberDana,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.SumberDana,monira_data_dipa.Akun, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." SumberDana,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.SumberDana,monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." SumberDana,Akun
                    )
                ) as UnionData
                LEFT JOIN monira_ref_sumber_dana ON monira_ref_sumber_dana.KodeSumberDana=UnionData.SumberDana
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                ".$sql['join']."
                WHERE monira_ref_akun.isCovid='1'
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
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kegiatan,monira_data_dipa.Akun,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Kegiatan,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kegiatan, monira_data_dipa.Akun,0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kegiatan,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kegiatan, monira_data_belanja.Akun,0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Kegiatan,Akun
                    )
                ) as UnionData
                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=UnionData.Kegiatan
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                ".$sql['join']."
                WHERE monira_ref_akun.isCovid='1'
                AND (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)
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
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Output,monira_data_dipa.Akun,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Output,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Output, monira_data_dipa.AKun,0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Output,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Output, monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Output,AKun
                    )
                ) as UnionData
                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=UnionData.Output
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                ".$sql['join']."
                WHERE monira_ref_akun.isCovid='1'
                AND (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)
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
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kewenangan,monira_data_dipa.Akun,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Kewenangan,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kewenangan, monira_data_dipa.Akun,0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kewenangan,Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kewenangan,monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Kewenangan,Akun
                    )
                ) as UnionData
                LEFT JOIN monira_ref_kewenangan ON monira_ref_kewenangan.IdKewenangan=UnionData.Kewenangan
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                ".$sql['join']."
                WHERE monira_ref_akun.isCovid='1'
                AND (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)
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
                                    WHERE TA=$year AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Akun, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                ) as UnionData
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                ".$sql['join']."
                WHERE monira_ref_akun.isCovid='1'
                AND (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)
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

            case "volume" :
                $dataSQL = DB::select(DB::raw("
                SELECT
                    ".$sql['selectList']."
                    guid,
                    monira_ref_kegiatan.KdKegiatan as KodeKegiatan,
                    monira_ref_kegiatan.NamaKegiatan,
                    monira_ref_output.KdOutput as KodeOutput,
                    monira_ref_output.NamaOutput,
                    monira_ref_akun.KdAkun as Kode, monira_ref_akun.NamaAkun as Keterangan,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen,
                    Uraian,
                    sum(voltarget) as VolTarget,sattarget as SatTarget,sum(rptarget) as RpTarget,
                    sum(voldsa) as VolDsa,CONCAT(satdsa) as SatDsa,sum(rpdsa) as RpDsa,
                    sum(rpdsa)/Sum(rptarget)*100 as PersenKegiatan,
                    sum(rptarget)-Sum(rpdsa) as SisaKegiatan,
                    monira_ref_sumber_dana.ShortKode as SumberDana,
                    Catatan as Catatan,
                    GROUP_CONCAT(Nosp2d separator '<br>') as Nosp2d,
                    GROUP_CONCAT(Tglsp2d separator '<br>') as Tglsp2d
                FROM
                (
                    (
                        SELECT KdSatker,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah, Kegiatan,Output,0 as guid,monira_data_dipa.Akun, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi,0 as Uraian,0 as voltarget, 0 as sattarget, 0 as voldsa,0 as satdsa,0 as rptarget, 0 as rpdsa,SumberDana,null as Catatan,null as Tglsp2d,null as Nosp2d
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kegiatan,Output,Akun,SumberDana,guid
                    )
                    UNION ALL
                    (
                        SELECT KdSatker,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah, Kegiatan,Output,0 as guid,monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi,0 as Uraian,0 as voltarget, 0 as sattarget, 0 as voldsa,0 as satdsa,0 as rptarget, 0 as rpdsa,SumberDana,null as Catatan,null as Tglsp2d,null as Nosp2d
                                FROM monira_data_belanja
                                    WHERE TA=$year AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ' AND Amount<>0
                                        GROUP BY ".$sql['groupBy']." Kegiatan,Output,Akun,SumberDana,guid
                    )
                    UNION ALL
                    (
                        SELECT KdSatker,monira_data_dipa_covid.Lokasi as KodeWilayah, Kegiatan,Output,guid,monira_data_dipa_covid.Akun, 0 as PaguAwal, 0 as Pagu, 0 as Realisasi,monira_data_dipa_covid.Uraian as Uraian,sum(monira_data_dipa_covid.Volume) as voltarget, monira_data_dipa_covid.Satuan as sattarget, 0 as voldsa,0 as satdsa,sum(monira_data_dipa_covid.Amount) as rptarget, 0 as rpdsa,SumberDana,BudgetType as Catatan,null as Tglsp2d,null as Nosp2d
                                FROM monira_data_dipa_covid
                                    WHERE TA=$year
                                        GROUP BY ".$sql['groupBy']." Kegiatan,Output,Akun,SumberDana,guid
                    )
                    UNION ALL
                    (
                        SELECT KdSatker,monira_data_belanja_covid.Lokasi as KodeWilayah, Kegiatan,Output,guid,monira_data_belanja_covid.Akun, 0 as PaguAwal, 0 as Pagu, 0 as Realisasi,monira_data_belanja_covid.Uraian as Uraian,0 as voltarget, 0 as sattarget, sum(monira_data_belanja_covid.Volume) as voldsa,monira_data_belanja_covid.Satuan as satdsa,0 as rptarget, sum(monira_data_belanja_covid.Amount) as rpdsa, SumberDana, null as Catatan,GROUP_CONCAT(DATE_FORMAT(Bulan, '%d/%m/%Y'),' NO ', Nosp2d separator '
                        ') as Tglsp2d,GROUP_CONCAT(Nosp2d separator '<br>') as Nospd2
                                FROM monira_data_belanja_covid
                                    WHERE TA=$year AND MONTH(monira_data_belanja_covid.Bulan)<=$month
                                        GROUP BY ".$sql['groupBy']." Kegiatan,Output,Akun,SumberDana,guid
                    )
                ) as UnionData
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                LEFT JOIN monira_ref_sumber_dana ON monira_ref_sumber_dana.KodeSumberDana=UnionData.SumberDana
                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=UnionData.Kegiatan
                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=UnionData.Output
                ".$sql['join']."
                WHERE monira_ref_akun.isCovid='1'
                GROUP BY
                ".$sql['groupFinal']." monira_ref_kegiatan.KdKegiatan,monira_ref_output.KdOutput,monira_ref_akun.KdAkun,UnionData.guid
                ".$sql['orderBy']."
                "));

                // return response()->json($dataSQL);


                $data = collect($dataSQL)->map(function($data){
                    return [
                        "KodeHeader"      => $data->KodeHeader ?? '',
                        "NamaHeader"      => $data->NamaHeader ?? '',
                        "KodeSubHeader"   => $data->KodeSubHeader ?? '',
                        "NamaSubHeader"   => $data->NamaSubHeader ?? '',
                        "KodeKegiatan"    => $data->KodeKegiatan,
                        "NamaKegiatan"    => $data->NamaKegiatan,
                        "KodeOutput"      => $data->KodeOutput,
                        "NamaOutput"      => $data->NamaOutput,
                        "Kode"            => $data->Kode,
                        "Keterangan"      => $data->Keterangan,
                        "SumberDana"      => $data->SumberDana,
                        "Pagu"            => $data->Pagu,
                        "Realisasi"       => $data->Realisasi,
                        "Sisa"            => $data->Sisa,
                        "Persen"          => $data->Persen,
                        "Uraian"          => $data->Uraian,
                        "Catatan"         => $data->Catatan,
                        "VolumeTarget"    => $data->VolTarget,
                        "SatuanTarget"    => $data->SatTarget,
                        "RpTarget"        => $data->RpTarget,
                        "VolumeRealisasi" => $data->VolDsa,
                        "SatuanRealisasi" => $data->SatDsa,
                        "RpRealisasi"     => $data->RpDsa,
                        "PersenKegiatan"  => $data->PersenKegiatan,
                        "SisaKegiatan"    => $data->SisaKegiatan,
                        "Tglsp2d"         => $data->Tglsp2d,
                        "Nosp2d"          => $data->Nosp2d,
                    ];
                });
                $object = json_decode(json_encode($data), FALSE);


            break;
            }


            switch ($unit) {
                case 'eselon1':
                    switch ($type) {
                        case 'pdf':
                            $pdf = PDF::loadView('reports.report-pdf-covid', compact('data','year','unit','segment','month'));
                            return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellCovid($year,$segment,$unit,$month), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;

                case 'propinsi':
                    $data = NestCollection($object,'2');
                    switch ($type) {
                        case 'pdf':
                            set_time_limit(300);
                            $pdf = PDF::loadView('reports.report-pdf-covid', compact('data','year','unit','segment','month'));
                            return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellCovid($year,$segment,$unit,$month), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;
                case 'satker':
                    switch ($type) {
                        case 'pdf':

                            if($segment=='volume') {
                                $data = NestCollection($object,'RincianCovid');
                                set_time_limit(3000);
                                $pdf = PDF::loadView('reports.report-pdf-covid', compact('data','year','unit','segment','month'))->setPaper('a4', 'landscape');
                                return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");

                            } else {
                                $data = NestCollection($object,'3');
                                set_time_limit(3000);
                                $pdf = PDF::loadView('reports.report-pdf-covid', compact('data','year','unit','segment','month'));
                                return $pdf->stream("Laporan Realisasi Monira Per ".$unit." Per ".$segment.".pdf");
                            }
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellCovid($year,$segment,$unit,$month), 'Rekap Monira '.$date.'.xlsx');
                            break;
                    }
                    break;
            }



    }


    public function padatkarya($type,$unit,$segment,$month)
    {
        // dd($month);
        $date = Carbon::now()->format('dmY his');
        $year = $this->data['setyear'];
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
                    switch ($type) {
                        case 'pdf':
                            switch ($segment) {
                                case 'akun':
                                    $data = NestCollection($object,'PadKaryaOne');
                                    $pdf = PDF::loadView('reports.report-pdf-padatkarya-one-level', compact('data','year','unit','segment','month'));
                                    return $pdf->stream("Laporan Realisasi Padat Karya Per ".$unit." Per ".$segment.".pdf");

                                    break;
                                case 'rekap':
                                    $data = $object;
                                    $pdf = PDF::loadView('reports.report-pdf-padatkarya-rekap-one-level', compact('data','year','unit','segment','month'));
                                    return $pdf->stream("Laporan Realisasi Padat Karya Per ".$unit." Per ".$segment.".pdf");
                                    break;
                            }
                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellPadatKarya($year,$segment,$unit,$month), 'Rekap Padat Karya '.$date.'.xlsx');
                            break;
                    }
                    break;

                case 'propinsi':
                    switch ($type) {
                        case 'pdf':
                            switch ($segment) {
                                case 'akun':
                                    $data = NestCollection($object,'PadKaryaTwo');
                                    $pdf = PDF::loadView('reports.report-pdf-padatkarya-two-level', compact('data','year','unit','segment','month'));
                                    return $pdf->stream("Laporan Realisasi Padat Karya Per ".$unit." Per ".$segment.".pdf");
                                break;
                                case 'rekap':
                                    $data = NestCollection($object,'PadKaryaTwoRekap');
                                    $pdf = PDF::loadView('reports.report-pdf-padatkarya-rekap-two-level', compact('data','year','unit','segment','month'))->setPaper('a4', 'landscape');
                                    return $pdf->stream("Laporan Realisasi Padat Karya Per ".$unit." Per ".$segment.".pdf");
                                break;
                            }
                        break;
                        case 'excell':
                            return Excel::download(new ReportExcellPadatKarya($year,$segment,$unit,$month), 'Rekap Padat Karya '.$date.'.xlsx');
                        break;
                    }
                break;
                case 'satker':
                    switch ($type) {
                        case 'pdf':

                            switch ($segment) {
                                case 'akun':
                                    $data = NestCollection($object,'PadKaryaThree');
                                    $pdf = PDF::loadView('reports.report-pdf-padatkarya-three-level', compact('data','year','unit','segment','month'));
                                    return $pdf->stream("Laporan Realisasi Padat Karya Per ".$unit." Per ".$segment.".pdf");
                                break;
                                    case 'rekap':
                                    $data = NestCollection($object,'PadKaryaThreeRekap');
                                    $pdf = PDF::loadView('reports.report-pdf-padatkarya-rekap-three-level', compact('data','year','unit','segment','month'))->setPaper('a4', 'landscape');
                                    return $pdf->stream("Laporan Realisasi Padat Karya Per ".$unit." Per ".$segment.".pdf");
                                break;
                                    case 'rincian':
                                    $pdf = PDF::loadView('reports.report-pdf-padatkarya-rekap-rincian-level', compact('data','year','unit','segment','month'))->setPaper('a4', 'landscape');
                                    return $pdf->stream("Laporan Realisasi Padat Karya Per ".$unit." Per ".$segment.".pdf");
                                break;
                        }

                            break;
                        case 'excell':
                            return Excel::download(new ReportExcellPadatKarya($year,$segment,$unit,$month), 'Rekap Padat Karya '.$date.'.xlsx');
                            break;
                    }
                    break;
            }



    }

    public function harian($type,$top,$bottom)
    {
        $top = request('top');
        $bottom = request('bottom');

        $year = $this->data['setyear'];

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
        // return response()->json($data);
        $date = Carbon::now()->format('dmY');

        switch ($type) {
            case 'pdf':
                $pdf = PDF::loadView('reports.report-pdf-ranking-harian', compact('data','top','bottom'));
                return $pdf->stream("Laporan Ranking Realisasi Satker DJPL ".$date.".pdf");
            break;
            case 'excell':
                $segment = "rankharian";
                $unit = "eselon1";
                return Excel::download(new ReportExcellHarian($year,$segment,$unit,$top,$bottom), 'Rekap Ranking Harian '.$date.'.xlsx');
                break;
        }
    }


    public function snipper($type,$unit,$segment)
    {
        // dd($month);
        $date = Carbon::now()->format('dmY his');
        $year = $this->data['setyear'];

        $propinsi = RefWilayah::where('KodeWilayah',$unit)->with('satker')
        ->with(['satker.pejabat' => function($query) use($year) {
            $query->where('TA',$year);
        }])
        ->first();
        // return response()->json($propinsi);
        return view('reports.report-pdf-snipper-propinsi',compact('propinsi','year'));

    }

}
