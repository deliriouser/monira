<?php

namespace App\Http\Controllers\Satker;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\RefWilayah;
use App\Models\DataProfileSatker;
use Illuminate\Support\Facades\Cache;
use App\Models\PaguDipa;
use App\Models\DataPrognosa;
use App\Models\DataMessage;
use App\Models\DataMessageAttachment;
use App\Models\DataMessageSatker;
use App\Models\SnipperRefSk;
use App\Models\BelanjaDipa;
use App\Models\BelanjaDipaCovid;
use App\Models\PaguDipaCovid;

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

            $dipa = count(PaguDipa::where('KdSatker',Auth::user()->kdsatker)
                                ->where('TA',$year)
                                ->where('IsActive','1')
                                ->groupby(DB::raw("Kegiatan,Output,Akun,SumberDana"))
                                ->get());
            // $countDipa = count($dipa);

            $prognosa = count(DataPrognosa::where('KdSatker',Auth::user()->kdsatker)
                                ->where('TA',$year)
                                ->groupby(DB::raw("Kegiatan,Output,Akun,SumberDana"))
                                ->get());
            $notifPrognosa = $dipa-$prognosa;
            $dataMessage   = DataMessageSatker::with('message')->where('KdSatker',Auth::user()->kdsatker)->where('IsRead','0')->get();
            $notifMessage  = count($dataMessage);
            // dd($dataMessage);
            $belanjaCovid = BelanjaDipa::where('KdSatker',Auth::user()->kdsatker)
                                ->where('TA',$year)
                                ->wherehas('isCovid')
                                ->get()->sum('Amount');
            $dipaCovidVol = PaguDipaCovid::where('KdSatker',Auth::user()->kdsatker)
                                ->where('TA',$year)
                                ->get()->sum('Amount');
            $belanjaCovidVol = BelanjaDipaCovid::where('KdSatker',Auth::user()->kdsatker)
                                ->where('TA',$year)
                                ->get()->sum('Amount');

            if($dipaCovidVol==0) {
                $notifdipacovid = 1;
            } else {
                $notifdipacovid = 0;
            }
            if($belanjaCovidVol!=$belanjaCovid) {
                $notifbelanjacovid = 1;
            } else {
                $notifbelanjacovid = 0;
            }

            // return response()->json($belanjaCovidVol);

            // dd($dataMessage);
            $dataSK   = SnipperRefSk::whereDoesntHave('filesk',function($query) use($year) {
                $query->where('tahun',$year);
                $query->where('kode_satker',Auth::user()->kdsatker);
                })->get();
            View:: share(array(
                'profile'           => Auth:: user()->username,
                'setyear'           => $year,
                'today'             => DATE ('Y'),
                'prognosa'          => $notifPrognosa,
                'message'           => $notifMessage,
                'dataMessage'       => $dataMessage,
                'dataSK'            => count($dataSK),
                'dataMessageSK'     => $dataSK,
                'notifdipacovid'    => $notifdipacovid,
                'notifbelanjacovid' => $notifbelanjacovid,
            ));
            return $next($request);
        });

    }



    public function index($segment,$month)
    {
        $year     = $this->data['setyear'];
        $KdSatker = Auth::user()->kdsatker;
        $sql      = SqlGroup($unit='satker');
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
                                    WHERE TA=$year AND KdSatker=$KdSatker AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Belanja, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Belanja
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Belanja, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker AND Revision=0
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.SumberDana, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." SumberDana
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.SumberDana, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kegiatan, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kegiatan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kegiatan, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Output, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Output
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Output, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker AND KdSatker=$KdSatker AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Kewenangan, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Kewenangan
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Kewenangan, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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
                                    WHERE TA=$year AND KdSatker=$KdSatker AND Revision=0
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerPagu']." monira_data_dipa.Akun, 0 as PaguAwal, sum(monira_data_dipa.Amount) as Pagu, 0 as Realisasi
                                FROM monira_data_dipa
                                    WHERE TA=$year AND KdSatker=$KdSatker AND IsActive=1
                                        GROUP BY ".$sql['groupBy']." Akun
                    )
                    UNION ALL
                    (
                        SELECT ".$sql['selectInnerBelanja']." monira_data_belanja.Akun, 0 as PaguAwal, 0 as Pagu, sum(monira_data_belanja.Amount) as Realisasi
                                FROM monira_data_belanja
                                    WHERE TA=$year AND KdSatker=$KdSatker AND MONTH(monira_data_belanja.tanggal)<=$month AND Output<>'ZZZ'
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


                        // $data = NestCollection($object,'1');
                            // return response()->json($data);

                        return view('apps.belanja-satker-one-level',compact('data','segment'));


    }


}
