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


class PivotController extends Controller
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

    public function pivot()
    {
        $year = $this->data['setyear'];
        $data = Cache::remember('spending_akun_satker', 3600, function () use ($year) {
            return DB::table('monira_ref_wilayah')
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as PaguAwal,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,Belanja,Kegiatan,Output,Akun,SumberDana,Id
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0
                                GROUP BY KodeWilayah,KdSatker,Belanja,Kegiatan,Output,Akun,SumberDana,Id) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.KodeWilayah', '=', 'monira_ref_wilayah.KodeWilayah' )
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_dipa.Amount) as Pagu,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah,Belanja,Kegiatan,Output,Akun,SumberDana,Id
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1
                                GROUP BY KodeWilayah,KdSatker,Belanja,Kegiatan,Output,Akun,SumberDana,Id) as monira_data_dipa
                        "), function($join){
                            $join->on('monira_data_dipa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_dipa.Belanja', '=', 'monira_data_dipa_awal.Belanja');
                            $join->on('monira_data_dipa.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                            $join->on('monira_data_dipa.Output', '=', 'monira_data_dipa_awal.Output');
                            $join->on('monira_data_dipa.Akun', '=', 'monira_data_dipa_awal.Akun');
                            $join->on('monira_data_dipa.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                            $join->on('monira_data_dipa.Id', '=', 'monira_data_dipa_awal.Id');
                        })
            ->leftjoin(DB::raw("(
                    SELECT KdSatker,sum(monira_data_belanja.Amount) as Realisasi,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,Belanja,Kegiatan,Output,Akun,SumberDana,Id
                        FROM monira_data_belanja
                            WHERE TA=$year
                                GROUP BY KodeWilayah,KdSatker,Belanja,Kegiatan,Output,Akun,SumberDana,Id) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                            $join->on('monira_data_belanja.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                            $join->on('monira_data_belanja.Belanja', '=', 'monira_data_dipa_awal.Belanja');
                            $join->on('monira_data_belanja.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                            $join->on('monira_data_belanja.Output', '=', 'monira_data_dipa_awal.Output');
                            $join->on('monira_data_belanja.Akun', '=', 'monira_data_dipa_awal.Akun');
                            $join->on('monira_data_belanja.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                            $join->on('monira_data_belanja.Id', '=', 'monira_data_dipa_awal.Id');
                        })
            ->leftjoin(DB::raw("(
                SELECT KdSatker,sum(monira_data_prognosa.Amount) as Prognosa,Lokasi as KodeWilayah,Belanja,Kegiatan,Output,Akun,SumberDana,id
                    FROM monira_data_prognosa
                        WHERE TA=$year
                                GROUP BY KodeWilayah,KdSatker,Belanja,Kegiatan,Output,Akun,SumberDana,id) as monira_data_prognosa
                    "), function($join){
                        $join->on('monira_data_prognosa.KodeWilayah', '=', 'monira_data_dipa_awal.KodeWilayah');
                        $join->on('monira_data_prognosa.KdSatker', '=', 'monira_data_dipa_awal.KdSatker');
                        $join->on('monira_data_prognosa.Belanja', '=', 'monira_data_dipa_awal.Belanja');
                        $join->on('monira_data_prognosa.Kegiatan', '=', 'monira_data_dipa_awal.Kegiatan');
                        $join->on('monira_data_prognosa.Output', '=', 'monira_data_dipa_awal.Output');
                        $join->on('monira_data_prognosa.Akun', '=', 'monira_data_dipa_awal.Akun');
                        $join->on('monira_data_prognosa.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                        $join->on('monira_data_prognosa.id', '=', 'monira_data_dipa_awal.Id');
                    })
            ->leftjoin('monira_ref_output','KdOutput','monira_data_dipa_awal.Output')
            ->leftjoin('monira_ref_kegiatan','monira_ref_kegiatan.KdKegiatan','monira_data_dipa_awal.Kegiatan')
            ->leftjoin('monira_ref_belanja','monira_ref_belanja.id','monira_data_dipa_awal.Belanja')
            ->leftjoin('monira_ref_akun','KdAkun','monira_data_dipa_awal.Akun')
            ->leftjoin('monira_ref_sumber_dana','KodeSumberDana','monira_data_dipa_awal.SumberDana')
            ->leftjoin('monira_ref_satker','KodeSatker','monira_data_dipa_awal.KdSatker')
            ->selectRaw("
                        CONCAT(monira_ref_wilayah.KodeWilayah,'.',monira_ref_wilayah.WilayahName) as Propinsi,
                        CONCAT(monira_ref_satker.KodeSatker,'.',monira_ref_satker.NamaSatuanKerja) as Satker,
                        CONCAT(monira_ref_belanja.id,'.',monira_ref_belanja.Belanja) as Belanja,
                        CONCAT(monira_ref_kegiatan.KdKegiatan,'.',monira_ref_kegiatan.NamaKegiatan) as Kegiatan,
                        CONCAT(monira_ref_output.KdOutput,'.',monira_ref_output.NamaOutput) as Output,
                        CONCAT(monira_ref_akun.KdAkun,'.',monira_ref_akun.NamaAkun) as Akun,
                        monira_ref_sumber_dana.ShortKode as SumberDana,
                        PaguAwal,Pagu,Realisasi,Prognosa
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy(DB::Raw("
                        monira_ref_wilayah.KodeWilayah,
                        monira_ref_satker.KodeSatker,
                        monira_ref_belanja.id,
                        monira_ref_kegiatan.KdKegiatan,
                        monira_ref_output.KdOutput,
                        monira_ref_akun.KdAkun,
                        monira_ref_sumber_dana.KodeSumberDana
                        "),'ASC')
            // ->limit(10)
            ->get();


        });

        return view('apps.data-pivot',compact('data'));

                // return response()->json($data);

    }
}
