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
Use Illuminate\Support\Facades\Crypt;


class PrognosaController extends Controller
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


    public function index($unit,$segment)
    {
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
                        return view('apps.prognosa-one-level',compact('data','unit','segment'));
                    break;
                case 'propinsi':
                        $data = NestCollection($object,'2');
                        return view('apps.prognosa-two-level',compact('data','unit','segment'));
                    break;
                case 'satker':
                        $data = NestCollection($object,'3');
                        return view('apps.prognosa-three-level',compact('data','unit','segment'));
                    break;
            }

    }

    public function locking()
    {

        $year = $this->data['setyear'];
        $dataSQL = DB::select(DB::raw("
                SELECT
                    monira_ref_wilayah.KodeWilayah as KodeHeader, monira_ref_wilayah.WilayahName as NamaHeader,monira_ref_satker.KodeSatker as KodeSubHeader,monira_ref_satker.NamaSatuanKerja as NamaSubHeader,
                    sum(Prognosa) as Prognosa,
                    sum(Pagu) as Pagu,
                    sum(Realisasi) as Realisasi,
                    sum(Pagu)-Sum(Realisasi) as Sisa,
                    sum(Realisasi)/Sum(pagu)*100 as Persen,
                    sum(Prognosa)/Sum(pagu)*100 as PersenPrognosa,
                    monira_ref_satker.IsLockPrognosa
                FROM
                (
                    (
                        SELECT KdSatker,monira_data_prognosa.Lokasi as KodeWilayah,sum(monira_data_prognosa.Amount) as Prognosa, 0 as Pagu, 0 as Realisasi
                                FROM monira_data_prognosa
                                    WHERE TA=$year
                                        GROUP BY KodeWilayah,KdSatker
                    )
                    UNION ALL
                    (
                        SELECT KdSatker,SUBSTR(monira_data_dipa.Lokasi,1,2) as KodeWilayah, 0 as Prognosa, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND IsActive=1
                                        GROUP BY KodeWilayah,KdSatker
                    )
                    UNION ALL
                    (
                        SELECT KdSatker,SUBSTR(monira_data_belanja.Lokasi,1,2) as KodeWilayah,  0 as Prognosa, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND Output<>'ZZZ'
                                        GROUP BY KodeWilayah,KdSatker
                    )
                ) as UnionData
                LEFT JOIN monira_ref_wilayah ON monira_ref_wilayah.KodeWilayah = UnionData.KodeWilayah
                LEFT JOIN monira_ref_satker ON monira_ref_satker.KodeSatker=UnionData.KdSatker
                GROUP BY
                monira_ref_wilayah.KodeWilayah,monira_ref_satker.KodeSatker
                ORDER BY KodeHeader,KodeSubHeader ASC
                "));

                $data = collect($dataSQL)->map(function($data){
                   return [
                    "KodeHeader"     => $data->KodeHeader ?? '',
                    "NamaHeader"     => $data->NamaHeader ?? '',
                    "KodeSubHeader"  => $data->KodeSubHeader ?? '',
                    "NamaSubHeader"  => $data->NamaSubHeader ?? '',
                    "Kode"           => $data->Kode ?? '',
                    "Keterangan"     => $data->Keterangan ?? '',
                    "Prognosa"       => $data->Prognosa,
                    "Pagu"           => $data->Pagu,
                    "Realisasi"      => $data->Realisasi,
                    "Sisa"           => $data->Sisa,
                    "Persen"         => $data->Persen,
                    "PersenPrognosa" => $data->PersenPrognosa,
                    "IsLockPrognosa" => $data->IsLockPrognosa,
                   ];
                });
                $object = json_decode(json_encode($data), FALSE);
                $data = NestCollection($object,'locking');
                // return response()->json($data);
                return view('apps.prognosa-locking',compact('data'));

    }

    public function status($status,$id,$what)
    {
        $findID = Crypt::decrypt($id);
        switch ($what) {
            case 'prognosa':
                $data = DataProfileSatker::where('KodeSatker',$findID)->update([
                    'IsLockPrognosa' => $status,
                ]);
                break;
            case 'wilayah':
                $data = DataProfileSatker::where('KodeWilayah',$findID)->update([
                    'IsLockPrognosa' => $status,
                ]);
                break;
            case 'eselon1':
                $data = DataProfileSatker::where('KodeWilayah','!=','NULL')->update([
                    'IsLockPrognosa' => $status,
                ]);
                break;
            }
    }


}
