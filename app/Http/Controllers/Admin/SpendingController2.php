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


class SpendingController extends Controller
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
        Cache::flush();
        return redirect()->route('/');
    }

    public function eselon1($segment)
    {
        $year = $this->data['setyear'];

        switch($segment) {
            case "belanja" :

                $data = Cache::remember('spending_belanja', 3600, function () use ($year) {
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
                            IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                            ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                            (Realisasi/Pagu)*100 as Persen
                            ")
                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                ->get();
                });

                return view('apps.belanja-one-level',compact('data','segment'));

            break;
            case "kegiatan" :

                $data = Cache::remember('spending_kegiatan', 3600, function () use ($year) {
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
                                WHERE TA=$year AND Output<>'ZZZ' GROUP BY Kegiatan) as monira_data_belanja
                            "),
                                'monira_data_belanja.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan')
                ->selectRaw("
                            KdKegiatan as Kode,NamaKegiatan as Keterangan,
                            IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                            ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                                (Realisasi/Pagu)*100 as Persen
                            ")
                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                ->where('KdKegiatan','!=','ZZZZ')
                ->orderby('Kode','ASC')
                ->get();
                });
                return view('apps.belanja-one-level',compact('data','segment'));

            break;

            case "output" :

                $data = Cache::remember('spending_output', 3600, function () use ($year) {
                return DB::table('monira_ref_output')
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Output
                            FROM monira_data_dipa
                                WHERE TA=$year AND Revision=0
                                    GROUP BY Output) as monira_data_dipa_awal
                            "),
                                'monira_data_dipa_awal.Output', '=', 'monira_ref_output.KdOutput' )
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Output
                            FROM monira_data_dipa
                                WHERE TA=$year AND IsActive=1
                                    GROUP BY Output) as monira_data_dipa
                            "),
                                'monira_data_dipa.Output', '=', 'monira_ref_output.KdOutput' )
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Output
                            FROM monira_data_belanja
                                WHERE TA=$year AND Output<>'ZZZ' GROUP BY Output) as monira_data_belanja
                            "),
                                'monira_data_belanja.Output', '=', 'monira_ref_output.KdOutput')
                ->selectRaw("
                            KdOutput as Kode,NamaOutput as Keterangan,
                            IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                            ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                                (Realisasi/Pagu)*100 as Persen
                            ")
                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                ->orderby('Kode','ASC')
                ->groupby('KdOutput')
                ->get();
                });
                return view('apps.belanja-one-level',compact('data','segment'));

            break;
            case "sumberdana":

                $data = Cache::remember('spending_sumberdana', 3600, function () use ($year) {
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
                                (Realisasi/Pagu)*100 as Persen
                            ")
                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                ->get();
                });
                return view('apps.belanja-one-level',compact('data','segment'));

            break;
            case "kewenangan":

                $data = Cache::remember('spending_kewenangan', 3600, function () use ($year) {
                return DB::table('monira_ref_kewenangan')
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Kewenangan
                            FROM monira_data_dipa
                                WHERE TA=$year AND Revision=0
                                    GROUP BY Kewenangan) as monira_data_dipa_awal
                            "),
                                'monira_data_dipa_awal.Kewenangan', '=', 'monira_ref_kewenangan.IdKewenangan' )
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Kewenangan
                            FROM monira_data_dipa
                                WHERE TA=$year AND IsActive=1
                                    GROUP BY Kewenangan) as monira_data_dipa
                            "),
                                'monira_data_dipa.Kewenangan', '=', 'monira_ref_kewenangan.IdKewenangan' )
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Kewenangan
                            FROM monira_data_belanja
                                WHERE TA=$year AND Output<>'ZZZ' GROUP BY Kewenangan) as monira_data_belanja
                            "),
                                'monira_data_belanja.Kewenangan', '=', 'monira_ref_kewenangan.IdKewenangan')
                ->selectRaw("
                            IdKewenangan as Kode,NamaKewenangan as Keterangan,
                            IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                            ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                                (Realisasi/Pagu)*100 as Persen
                            ")
                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                ->get();
                });
                return view('apps.belanja-one-level',compact('data','segment'));

            break;
            case "kinerja":

                $data = Cache::remember('spending_kinerja', 3600, function () use ($year) {
                return DB::table('monira_ref_output')
                ->leftjoin('monira_ref_kegiatan','monira_ref_output.KdKegiatan','monira_ref_kegiatan.KdKegiatan')
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Kegiatan,monira_data_dipa.Output
                            FROM monira_data_dipa
                                WHERE TA=$year AND Revision=0
                                    GROUP BY Kegiatan,Output) as monira_data_dipa_awal
                            "), function($join){
                                $join->on('monira_data_dipa_awal.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                                $join->on('monira_data_dipa_awal.Output', '=', 'monira_ref_output.KdOutput');
                            })
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Kegiatan,monira_data_dipa.Output
                            FROM monira_data_dipa
                                WHERE TA=$year AND IsActive=1
                                    GROUP BY Kegiatan,Output) as monira_data_dipa
                            "), function($join){
                                $join->on('monira_data_dipa.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                                $join->on('monira_data_dipa.Output', '=', 'monira_ref_output.KdOutput');
                            })
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Kegiatan,monira_data_belanja.Output
                            FROM monira_data_belanja
                                WHERE TA=$year AND Output<>'ZZZ'
                                    GROUP BY Kegiatan,Output) as monira_data_belanja
                            "), function($join){
                                $join->on('monira_data_belanja.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                                $join->on('monira_data_belanja.Output', '=', 'monira_ref_output.KdOutput');
                            })
                ->selectRaw("
                            monira_ref_kegiatan.KdKegiatan as KodeHeader,monira_ref_kegiatan.NamaKegiatan as NamaHeader,
                            monira_ref_output.KdOutput as Kode,monira_ref_output.NamaOutput as Keterangan,
                            IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                            ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                                (Realisasi/Pagu)*100 as Persen
                            ")
                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                ->orderby('KodeHeader','ASC')
                ->orderby('Persen','DESC')
                ->orderby('KdOutput','ASC')
                ->get();
            });

            $data = NestCollection($data,'2');
            $level = "Eselon 1";
            return view('apps.belanja-two-level',compact('data','segment','level'));

            break;
            case "akun":
                $data = Cache::remember('spending_akun', 3600, function () use ($year) {
                return DB::table('monira_ref_akun')
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Akun
                            FROM monira_data_dipa
                                WHERE TA=$year AND Revision=0
                                    GROUP BY Akun) as monira_data_dipa_awal
                            "),
                                'monira_data_dipa_awal.Akun', '=', 'monira_ref_akun.KdAkun' )
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Akun
                            FROM monira_data_dipa
                                WHERE TA=$year AND IsActive=1
                                    GROUP BY Akun) as monira_data_dipa
                            "),
                                'monira_data_dipa.Akun', '=', 'monira_ref_akun.KdAkun' )
                ->leftjoin(DB::raw("(
                        SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Akun
                            FROM monira_data_belanja
                                WHERE TA=$year AND Output<>'ZZZ' GROUP BY Akun) as monira_data_belanja
                            "),
                                'monira_data_belanja.Akun', '=', 'monira_ref_akun.KdAkun')
                ->selectRaw("
                            KdAkun as Kode,NamaAkun as Keterangan,
                            IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                            ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                                (Realisasi/Pagu)*100 as Persen
                            ")
                ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
                ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
                ->get();
                });
                // return response()->json($data);

                return view('apps.belanja-one-level',compact('data','segment'));

            break;
        }



    }

    public function propinsi($segment)
    {
        $year = $this->data['setyear'];

        switch($segment) {
        case "belanja":

            $data = Cache::remember('spending_belanja_wilayah', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Belanja
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,Belanja) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Belanja
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,Belanja) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.Belanja', '=', 'monira_data_dipa_awal.Belanja');
                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Belanja
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,Belanja) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.Belanja', '=', 'monira_data_dipa_awal.Belanja');
                        })
            ->leftjoin('monira_ref_belanja','id','monira_data_dipa_awal.Belanja')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_belanja.id as Kode, monira_ref_belanja.Belanja as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->orderBy('KodeHeader','ASC')
            ->get();
        });

        $data = NestCollection($data,'2');
        $level = "Propinsi";

        return view('apps.belanja-two-level',compact('data','segment','level'));

        break;

        case "kegiatan":

            $data = Cache::remember('spending_kegiatan_wilayah', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kegiatan
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,Kegiatan) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kegiatan
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,Kegiatan) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Kegiatan
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,Kegiatan) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                        })
            ->leftjoin('monira_ref_kegiatan','KdKegiatan','monira_data_dipa_awal.Kegiatan')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_kegiatan.KdKegiatan as Kode, monira_ref_kegiatan.NamaKegiatan as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->where('KdKegiatan','!=','ZZZZ')
            ->orderBy('KodeHeader','ASC')
            ->get();
        });

        $data = NestCollection($data,'2');
        $level = "Propinsi";

        return view('apps.belanja-two-level',compact('data','segment','level'));

        break;

        case "output":

            $data = Cache::remember('spending_output_wilayah', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Output
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,Output) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Output
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,Output) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.Output', '=', 'monira_data_dipa_awal.Output');
                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Output
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,Output) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.Output', '=', 'monira_data_dipa_awal.Output');
                        })
            ->leftjoin(DB::raw("(
                SELECT KdOutput,NamaOutput
                    FROM monira_ref_output
                        GROUP BY KdOutput) as monira_ref_output
                    "),'KdOutput','monira_data_dipa_awal.Output')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_output.KdOutput as Kode, monira_ref_output.NamaOutput as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->orderBy('KodeHeader','ASC')
            ->get();
        });

        $data = NestCollection($data,'2');
        $level = "Propinsi";

        return view('apps.belanja-two-level',compact('data','segment','level'));

        break;
        case "sumberdana":

            $data = Cache::remember('spending_sumberdana_wilayah', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.SumberDana
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,SumberDana) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.SumberDana
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,SumberDana) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.SumberDana
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,SumberDana) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                        })
            ->leftjoin('monira_ref_sumber_dana','KodeSumberDana','monira_data_dipa_awal.SumberDana')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_sumber_dana.KodeSumberDana as Kode, monira_ref_sumber_dana.NamaSumberDana as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->orderBy('KodeHeader','ASC')
            ->get();
        });

        $data = NestCollection($data,'2');
        $level = "Propinsi";

        return view('apps.belanja-two-level',compact('data','segment','level'));

        break;

        case "kewenangan":

            $data = Cache::remember('spending_kewenangan_wilayah', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kewenangan
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,Kewenangan) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kewenangan
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,Kewenangan) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Kewenangan
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,Kewenangan) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                        })
            ->leftjoin('monira_ref_kewenangan','IdKewenangan','monira_data_dipa_awal.Kewenangan')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_kewenangan.IdKewenangan as Kode, monira_ref_kewenangan.NamaKewenangan as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->orderBy('KodeHeader','ASC')
            ->get();
        });

        $data = NestCollection($data,'2');
        $level = "Propinsi";

        return view('apps.belanja-two-level',compact('data','segment','level'));

        break;

        case "kinerja":

            $data = Cache::remember('spending_kinerja_wilayah', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kegiatan,monira_data_dipa.Output
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,Kegiatan,Output) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kegiatan,monira_data_dipa.Output
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,Kegiatan,Output) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                            $join->on('monira_data_dipa.Output', '=', 'monira_data_dipa_awal.Output');
                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Kegiatan,monira_data_belanja.Output
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,Kegiatan,Output) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                            $join->on('monira_data_belanja.Output', '=', 'monira_data_dipa_awal.Output');
                        })
            ->leftjoin('monira_ref_output'
                            , function($join){
                                $join->on('monira_ref_output.KdOutput', '=', 'monira_data_dipa_awal.Output');
                                $join->on('monira_ref_output.KdKegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                            })
            ->leftjoin('monira_ref_kegiatan','monira_ref_kegiatan.KdKegiatan','monira_data_dipa_awal.Kegiatan')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_kegiatan.KdKegiatan as KodeSubHeader, monira_ref_kegiatan.NamaKegiatan as NamaSubHeader,
                        monira_ref_output.KdOutput AS Kode,monira_ref_output.NamaOutput as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->orderBy(DB::Raw("KodeHeader,KodeSubHeader,Kode"),'ASC')
            ->get();
        });


        $data = NestCollection($data,'3');
        $level = "Propinsi";

        return view('apps.belanja-three-level',compact('data','segment','level'));

        // return response()->jpson($data);


        break;

        case "akun":

            $data = Cache::remember('spending_akun_wilayah', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Akun
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,Akun) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Akun
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,Akun) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.Akun', '=', 'monira_data_dipa_awal.Akun');
                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Akun
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,Akun) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.Akun', '=', 'monira_data_dipa_awal.Akun');
                        })
            ->leftjoin('monira_ref_akun','KdAkun','monira_data_dipa_awal.Akun')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_akun.KdAkun as Kode, monira_ref_akun.NamaAkun as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->orderBy(DB::Raw("KodeHeader,KdAkun"),)
            ->get();
        });

        $data = NestCollection($data,'2');
        $level = "Propinsi";

        return view('apps.belanja-two-level',compact('data','segment','level'));

        break;

        }
    // return response()->json($data);

        // return view('apps.belanja-twolevel',compact('data','segment'));

    }

    public function satker($segment)
    {
        $year = $this->data['setyear'];

        switch($segment) {
        case "belanja":

            $data = Cache::remember('spending_belanja_satker', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Belanja
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,KdSatker,Belanja) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Belanja
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,KdSatker,Belanja) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_dipa.Belanja', '=', 'monira_data_dipa_awal.Belanja');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Belanja
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,KdSatker,Belanja) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_belanja.Belanja', '=', 'monira_data_dipa_awal.Belanja');
                        })
            ->leftjoin('monira_ref_belanja','id','monira_data_dipa_awal.Belanja')
            ->leftjoin('monira_ref_satker','KodeSatker','monira_data_dipa_awal.KdSatker')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_satker.KodeSatker as KodeSubHeader, monira_ref_satker.NamaSatuanKerja as NamaSubHeader,
                        monira_ref_belanja.id as Kode, monira_ref_belanja.Belanja as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy(DB::Raw("KodeHeader,KodeSubHeader,Kode"),'ASC')
            ->get();
                    });

                    $data = NestCollection($data,'3');
                    $level = "Satker";

                    return view('apps.belanja-three-level',compact('data','segment','level'));

        break;

        case "kegiatan":

            $data = Cache::remember('spending_kegiatan_satker', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kegiatan
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,KdSatker,Kegiatan) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kegiatan
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,KdSatker,Kegiatan) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_dipa.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Kegiatan
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,KdSatker,Kegiatan) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_belanja.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                        })
            ->leftjoin('monira_ref_kegiatan','KdKegiatan','monira_data_dipa_awal.Kegiatan')
            ->leftjoin('monira_ref_satker','KodeSatker','monira_data_dipa_awal.KdSatker')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_satker.KodeSatker as KodeSubHeader, monira_ref_satker.NamaSatuanKerja as NamaSubHeader,
                        monira_ref_kegiatan.KdKegiatan as Kode, monira_ref_kegiatan.NamaKegiatan as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy(DB::Raw("KodeHeader,KodeSubHeader,Kode"),'ASC')
            ->get();
                    });

                    $data = NestCollection($data,'3');
                    $level = "Satker";

                    return view('apps.belanja-three-level',compact('data','segment','level'));

        break;


        case "output":

            $data = Cache::remember('spending_output_satker', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Output
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,KdSatker,Output) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Output
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,KdSatker,Output) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_dipa.Output', '=', 'monira_data_dipa_awal.Output');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Output
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,KdSatker,Output) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_belanja.Output', '=', 'monira_data_dipa_awal.Output');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdOutput,NamaOutput
                        FROM monira_ref_output
                            GROUP BY KdOutput) as monira_ref_output
                        "),'KdOutput','monira_data_dipa_awal.Output')
            ->leftjoin('monira_ref_satker','KodeSatker','monira_data_dipa_awal.KdSatker')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_satker.KodeSatker as KodeSubHeader, monira_ref_satker.NamaSatuanKerja as NamaSubHeader,
                        monira_ref_output.KdOutput as Kode, monira_ref_output.NamaOutput as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy(DB::Raw("KodeHeader,KodeSubHeader,Kode"),'ASC')
            ->get();
                    });

                    $data = NestCollection($data,'3');
                    $level = "Satker";

                    return view('apps.belanja-three-level',compact('data','segment','level'));

        break;
        case "sumberdana":
            $data = Cache::remember('spending_sumberdana_satker', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.SumberDana
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,KdSatker,SumberDana) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.SumberDana
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,KdSatker,SumberDana) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_dipa.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.SumberDana
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,KdSatker,SumberDana) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_belanja.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                        })
            ->leftjoin('monira_ref_sumber_dana','KodeSumberDana','monira_data_dipa_awal.SumberDana')
            ->leftjoin('monira_ref_satker','KodeSatker','monira_data_dipa_awal.KdSatker')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_satker.KodeSatker as KodeSubHeader, monira_ref_satker.NamaSatuanKerja as NamaSubHeader,
                        monira_ref_sumber_dana.KodeSumberDana as Kode, monira_ref_sumber_dana.NamaSumberDana as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy(DB::Raw("KodeHeader,KodeSubHeader,Kode"),'ASC')
            ->get();
                    });

                    $data = NestCollection($data,'3');
                    $level = "Satker";

                    return view('apps.belanja-three-level',compact('data','segment','level'));

        break;

        case "kewenangan":
            $data = Cache::remember('spending_kewenangan_satker', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kewenangan
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,KdSatker,Kewenangan) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kewenangan
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,KdSatker,Kewenangan) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_dipa.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Kewenangan
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,KdSatker,Kewenangan) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_belanja.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                        })
            ->leftjoin('monira_ref_kewenangan','IdKewenangan','monira_data_dipa_awal.Kewenangan')
            ->leftjoin('monira_ref_satker','KodeSatker','monira_data_dipa_awal.KdSatker')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_satker.KodeSatker as KodeSubHeader, monira_ref_satker.NamaSatuanKerja as NamaSubHeader,
                        monira_ref_kewenangan.IdKewenangan as Kode, monira_ref_kewenangan.NamaKewenangan as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy(DB::Raw("KodeHeader,KodeSubHeader,Kode"),'ASC')
            ->get();
                    });

                    $data = NestCollection($data,'3');
                    $level = "Satker";

                    return view('apps.belanja-three-level',compact('data','segment','level'));

        break;

        case "kinerja":
            $data = Cache::remember('spending_kinerja_satker', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kegiatan,monira_data_dipa.Output
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,KdSatker,Kegiatan,Output) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Kegiatan,monira_data_dipa.Output
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,KdSatker,Kegiatan,Output) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_dipa.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                            $join->on('monira_data_dipa.Output', '=', 'monira_data_dipa_awal.Output');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Kegiatan,monira_data_belanja.Output
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,KdSatker,Kegiatan,Output) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_belanja.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                            $join->on('monira_data_belanja.Output', '=', 'monira_data_dipa_awal.Output');
                        })
            ->leftjoin('monira_ref_output'
                            , function($join){
                                $join->on('monira_ref_output.KdOutput', '=', 'monira_data_dipa_awal.Output');
                                $join->on('monira_ref_output.KdKegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                            })
            ->leftjoin('monira_ref_kegiatan','monira_ref_kegiatan.KdKegiatan','monira_data_dipa_awal.Kegiatan')
            ->leftjoin('monira_ref_satker','KodeSatker','monira_data_dipa_awal.KdSatker')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_satker.KodeSatker as KodeSubHeader, monira_ref_satker.NamaSatuanKerja as NamaSubHeader,
                        monira_ref_kegiatan.KdKegiatan as KodeSubHeaderSub, monira_ref_kegiatan.NamaKegiatan as NamaSubHeaderSub,
                        monira_ref_output.KdOutput AS Kode,monira_ref_output.NamaOutput as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy(DB::Raw("KodeHeader,KodeSubHeader,KodeSubHeaderSub,Kode"),'ASC')
            ->get();
                        });

                        $data = NestCollection($data,'3');
                        $level = "Satker";

                        return view('apps.belanja-three-level',compact('data','segment','level'));

        break;

        case "akun":
            $data = Cache::remember('spending_akun_satker', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Akun
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,KdSatker,Akun) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,monira_data_dipa.Akun
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,KdSatker,Akun) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_dipa.Akun', '=', 'monira_data_dipa_awal.Akun');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,monira_data_belanja.Akun
                        FROM monira_data_belanja
                            WHERE TA=$year AND Output<>'ZZZ'
                                GROUP BY KodeWilayah,KdSatker,Akun) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_belanja.Akun', '=', 'monira_data_dipa_awal.Akun');
                        })
            ->leftjoin('monira_ref_akun','KdAkun','monira_data_dipa_awal.Akun')
            ->leftjoin('monira_ref_satker','KodeSatker','monira_data_dipa_awal.KdSatker')
            ->selectRaw("
                        monira_ref_wilayah.KodeWilayah AS KodeHeader,WilayahName as NamaHeader,
                        monira_ref_satker.KodeSatker as KodeSubHeader, monira_ref_satker.NamaSatuanKerja as NamaSubHeader,
                        monira_ref_akun.KdAkun as Kode, monira_ref_akun.NamaAkun as Keterangan,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy(DB::Raw("KodeHeader,KodeSubHeader,Kode"),'ASC')
            // ->paginate(50);
            ->get();
                    });

                    $data = NestCollection($data,'3');
                    $level = "Satker";

                    return view('apps.belanja-three-level',compact('data','segment','level'));

        break;

        }

    }
}
