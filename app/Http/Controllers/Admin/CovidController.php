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


class CovidController extends Controller
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
                'today'   => DATE ('Y')
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


    public function index($unit,$segment,$month)
    {
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
                        return view('apps.covid-one-level',compact('data','unit','segment'));
                    break;
                case 'propinsi':
                        $data = NestCollection($object,'2');
                        return view('apps.covid-two-level',compact('data','unit','segment'));
                    break;
                case 'satker':
                        if($segment=='volume') {
                            $data = NestCollection($object,'RincianCovid');
                            // return response()->json($data);
                            return view('apps.covid-multi-level',compact('data','unit','segment'));

                        } else {
                            $data = NestCollection($object,'3');
                            return view('apps.covid-three-level',compact('data','unit','segment'));
                        }
                    break;
            }


    }


}
