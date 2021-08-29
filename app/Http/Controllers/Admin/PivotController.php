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
        $data = Cache::remember('pivot_data', 3600, function () use ($year) {

            return DB::select(DB::raw("
                SELECT
                CONCAT(monira_ref_wilayah.KodeWilayah,'.',monira_ref_wilayah.WilayahName) as Propinsi,
                CONCAT(monira_ref_satker.KodeSatker,'.',monira_ref_satker.NamaSatuanKerja) as Satker,
                CONCAT(monira_ref_belanja.id,'.',monira_ref_belanja.Belanja) as Belanja,
                CONCAT(monira_ref_kegiatan.KdKegiatan,'.',monira_ref_kegiatan.NamaKegiatan) as Kegiatan,
                CONCAT(monira_ref_output.KdOutput,'.',monira_ref_output.NamaOutput) as Output,
                CONCAT(monira_ref_kewenangan.IdKewenangan,'.',monira_ref_kewenangan.NamaKewenangan) as Kewenangan,
                CONCAT(monira_ref_akun.KdAkun,'.',monira_ref_akun.NamaAkun) as Akun,
                monira_ref_sumber_dana.ShortKode as SumberDana,
                Bulan,
                sum(PaguAwal) as PaguAwal,
                sum(Pagu) as Pagu,
                sum(Realisasi) as Realisasi,
                sum(Prognosa) as Prognosa,
                sum(Pagu)-Sum(Realisasi) as SisaRealisasi,
                sum(Prognosa)-Sum(Realisasi) as SisaPrognosa,
                sum(Realisasi)/Sum(pagu)*100 as PersenRealisasi,
                sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa
                FROM
                (
                (
                SELECT KdSatker,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah, monira_data_dipa.Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana,sum(monira_data_dipa.Amount) as PaguAwal, 0 as Pagu, 0 as Realisasi, 0 as Prognosa, 0 as Bulan
                FROM monira_data_dipa
                WHERE TA=$year AND Revision=0
                GROUP BY KodeWilayah,KdSatker, Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana,Bulan
                )
                UNION ALL
                (
                SELECT KdSatker,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah, monira_data_dipa.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi, 0 as Prognosa, 0 as Bulan
                FROM monira_data_dipa
                WHERE TA=$year AND IsActive=1
                GROUP BY KodeWilayah,KdSatker, Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana,Bulan
                )
                UNION ALL
                (
                SELECT KdSatker,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah, monira_data_belanja.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi, 0 as Prognosa,MONTH(Tanggal) as Bulan
                FROM monira_data_belanja
                WHERE TA=$year AND Output<>'ZZZ'
                GROUP BY KodeWilayah,KdSatker, Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana,Bulan
                )
                UNION ALL
                (
                SELECT KdSatker,SUBSTR(monira_data_prognosa.Lokasi,1,2) as KodeWilayah, monira_data_prognosa.Belanja, Kegiatan,Output,Kewenangan,Akun,SumberDana,0 as PaguAwal, 0 as Pagu, 0 as Realisasi,sum(monira_data_prognosa.Amount) as Prognosa, Bulan
                FROM monira_data_prognosa
                WHERE TA=$year AND Output<>'ZZZ'
                GROUP BY KodeWilayah,KdSatker, Belanja,Kegiatan,Output,Kewenangan,Akun,SumberDana, Bulan
                )
                ) as UnionData
                LEFT JOIN monira_ref_belanja ON monira_ref_belanja.id=UnionData.Belanja
                LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = UnionData.KodeWilayah LEFT JOIN monira_ref_satker ON monira_ref_satker.KodeSatker=UnionData.KdSatker
                LEFT JOIN monira_ref_sumber_dana ON monira_ref_sumber_dana.KodeSumberDana=UnionData.SumberDana
                LEFT JOIN monira_ref_kegiatan ON monira_ref_kegiatan.KdKegiatan=UnionData.Kegiatan
                LEFT JOIN (SELECT KdOutput,NamaOutput FROM monira_ref_output GROUP BY KdOutput) as monira_ref_output ON monira_ref_output.KdOutput=UnionData.Output
                LEFT JOIN monira_ref_kewenangan ON monira_ref_kewenangan.IdKewenangan=UnionData.Kewenangan
                LEFT JOIN monira_ref_akun ON monira_ref_akun.KdAkun=UnionData.Akun
                WHERE (PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0 OR Prognosa <> 0)
                GROUP BY
                monira_ref_wilayah.KodeWilayah,
                monira_ref_satker.KodeSatker,
                monira_ref_belanja.Belanja,
                monira_ref_kegiatan.KdKegiatan,
                monira_ref_output.KdOutput,
                monira_ref_kewenangan.IdKewenangan,
                monira_ref_akun.KdAkun,
                monira_ref_sumber_dana.ShortKode,
                Bulan
                ORDER BY monira_ref_wilayah.KodeWilayah,monira_ref_satker.KodeSatker ASC
            "));



        });

        // return response()->json($data);

        return view('apps.data-pivot',compact('data'));


    }
}
