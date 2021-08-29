<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\RefWilayah;
use App\Models\DataProfileSatker;
use Illuminate\Support\Facades\Cache;

class RangkingController extends Controller
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

    public function setyear(Request $request, $tahun)
    {
        $request->session()->put('setyear', $tahun);
        return redirect()->route('/');
    }

    public function satker()
    {

        $year = $this->data['setyear'];

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
        return view('apps.ranking-satker',compact('data'));


    }

    public function propinsi()
    {

        $year = $this->data['setyear'];
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
        // return response()->json($data);


        return view('apps.ranking-propinsi',compact('data'));


    }

    public function pivotsatker()
    {
        $year = $this->data['setyear'];

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
            // return response()->json($data);
        return view('apps.ranking-propinsi-satker',compact('data'));
    }

    public function harian($top,$bottom)
    {

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
        return view('apps.ranking-satker-harian',compact('data','top','bottom'));


    }

    public function filter(Request $request)
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
        return view('apps.ranking-satker-harian',compact('data','top','bottom'));


    }

}
