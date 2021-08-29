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

class DashboardController extends Controller
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

    public function belanja()
    {
        $year = $this->data['setyear'];
        $DashBelanja = Cache::remember('DashBelanja',3600, function () use ($year) {
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
                        id as Belanja,
                        ifnull(PaguAwal,0) as PaguAwal,ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->get();
        });

        $DashKegiatan = Cache::remember('DashKegiatan',3600, function () use ($year) {
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
                        KdKegiatan,NamaKegiatan,
                        ifnull(PaguAwal,0) as PaguAwal,ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->get();
            });

            $DashSDana = Cache::remember('DashSDana',3600, function () use ($year) {
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
                        KodeSumberDana,NamaSumberDana,
                        IFNULL(PaguAwal,0) as PaguAwal,IFNULL(Pagu,0) as Pagu,IFNULL(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        IFNULL( TRUNCATE ( ( Realisasi/Pagu ) * 100, 2 ), 0.00 ) as Persen
                        ")
            ->whereRaw('(PaguAwal IS NOT NULL OR Pagu IS NOT NULL OR Realisasi IS NOT NULL)')
            ->whereRaw('(PaguAwal <> 0 OR Pagu <> 0 OR Realisasi <> 0)')
            ->get();
            });


            // return response()->json($DashCovid);

            $previusYear = $year-1;

            $DataBelanja = Cache::remember('DataBelanja',3600, function () use ($year,$previusYear) {
                return BelanjaDipa::selectRaw('TA,MONTH(tanggal) as bulan, sum(Amount) as DSA')
                ->groupby(DB::Raw("bulan,TA"))
                ->orderby(DB::Raw("bulan"),'ASC')
                ->whereRaw('(TA='.$year.' OR TA='.$previusYear.')')
                ->where('Output','<>','ZZZ')
                ->get();
            });

            $DataPrognosa = Cache::remember('DataPrognosa',3600, function () use ($year) {
                return DataPrognosa::selectRaw('Bulan, sum(Amount) as Amount')
                ->groupby(DB::Raw("bulan,TA"))
                ->orderby(DB::Raw("bulan"),'ASC')
                ->where('TA',$year)
                ->get();
                });

            $DataPagu = Cache::remember('DataPagu',3600, function () use ($year,$previusYear) {
                return PaguDipa::selectRaw('TA,sum(Amount) as DIPA')
                ->groupby(DB::Raw("TA"))
                ->whereRaw('(TA='.$year.' OR TA='.$previusYear.')')
                ->where('IsActive','1')
                ->get();
            });

            $data = PrepareChart($DataPagu,$DataBelanja,$year);
            $data_prognosa = PrepareSingleChart($DashBelanja->sum('Pagu'),$DataPrognosa);

            // return response()->json($DataPrognosa);

            $chartDSA = LarapexChart::linechart()
            ->setHeight(250)
            ->addData('PROGNOSA '.$year, $data_prognosa)
            ->addData('REALISASI '.$year, $data['Current'])
            ->addData('REALISASI '.$previusYear, $data['Previus'])
            ->setXAxis(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'])
            ->setColors(['#ff455f','#008FFB', '#00E396'])
            ->setToolbar('show','enabled')
            ->setGrid(false, '#3F51B5', 0.1)
            // ->setDataLabels();
            ->setMarkers(['#ff455f','#008FFB', '#00E396'], 7, 10);


            return view('dashboard.belanja',compact('DashBelanja','DashKegiatan','DashSDana','chartDSA'));

    }

    public function covid()
    {
        $year = $this->data['setyear'];

        $DashBelanja = Cache::remember('DashBelanjaCovid', 3600, function () use ($year) {
            return DB::select(DB::raw("
                SELECT
                monira_ref_belanja.id as Belanja,monira_ref_belanja.Belanja as NamaBelanja,
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

        $DashKegiatan = Cache::remember('DashKegiatanCovid', 3600, function () use ($year) {
            return DB::select(DB::raw("
                SELECT
                monira_ref_belanja.id as KdBelanja,monira_ref_belanja.Belanja as NamaBelanja,
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
                monira_ref_kegiatan.KdKegiatan
            "));
        });

        $DashSDana = Cache::remember('DashSDanaCovid', 3600, function () use ($year) {
            return DB::select(DB::raw("
                SELECT
                monira_ref_belanja.id as KdBelanja,monira_ref_belanja.Belanja as NamaBelanja,
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
                monira_ref_sumber_dana.ShortKode
                ORDER BY monira_ref_sumber_dana.KodeSumberDana ASC
            "));
        });

                // monira_ref_belanja.Belanja,
                // monira_ref_kegiatan.KdKegiatan,
                // monira_ref_output.KdOutput,
                // monira_ref_kewenangan.IdKewenangan,
                // monira_ref_akun.KdAkun,
                // monira_ref_sumber_dana.ShortKode



        return view('dashboard.covid',compact('DashBelanja','DashKegiatan','DashSDana'));

    }


    public function penerimaan()
    {
        $year = $this->data['setyear'];
        $data = SimpleCurl::get('http://datacenter.keuanganhubla.com/api/api/get_data_e1_bytahun/'.$year)->getResponseAsCollection();

        $PaguPNBP = Cache::remember('PaguPNBP',3600, function () use ($year) {
        return DB::table('monira_ref_sumber_dana')
        ->leftjoin(DB::raw("(
                SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.SumberDana
                    FROM monira_data_dipa
                        WHERE TA=$year AND IsActive=1
                            GROUP BY SumberDana) as monira_data_dipa
                    "),
                        'monira_data_dipa.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana' )
        ->leftjoin(DB::raw("(
                SELECT sum(monira_data_mp.Amount) as MP,monira_data_mp.SumberDana
                    FROM monira_data_mp
                        WHERE TA=$year GROUP BY SumberDana) as monira_data_mp
                    "),
                        'monira_data_mp.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana')

        ->leftjoin(DB::raw("(
                SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.SumberDana
                    FROM monira_data_belanja
                        WHERE TA=$year GROUP BY SumberDana) as monira_data_belanja
                    "),
                        'monira_data_belanja.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana')
        ->selectRaw("
                    KodeSumberDana,NamaSumberDana,
                    ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,ifnull(MP,0) as MP,
                    (Realisasi/Pagu)*100 as Persen
                    ")
        ->where('KodeSumberDana','=','D')
        ->first();
        });

        $previusYear = $year-1;

        $PNBPmonth = Cache::remember('PNBPmonth',3600, function () use ($year,$previusYear) {
            return PendapatanDipa::selectRaw('TA,MONTH(tanggal) as bulan, sum(Amount) as PNBP')
            ->groupby(DB::Raw("bulan,TA"))
            ->orderby(DB::Raw("bulan"),'ASC')
            ->where('TA',$year)
            ->orwhere('TA',$previusYear)
            ->get();
            });



        $CurPNBP = array();
        $PrevPNBP = array();
        foreach($PNBPmonth as $item) {
            if($item->TA==$year) {
                $CurPNBP[] = number_format($item->PNBP/1000000000,0);
            } else {
                $PrevPNBP[] = number_format($item->PNBP/1000000000,0);
            }
        }

        $chart = LarapexChart::areaChart()
        ->setHeight(200)
        ->addData('PNBP TA '.$year, $CurPNBP)
        ->addData('PNBP TA '.$previusYear, $PrevPNBP)
        ->setXAxis(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'])
        ->setColors(['#008FFB', '#00E396'])
        ->setToolbar('show','enabled')
        ->setMarkers(['#008FFB', '#00E396'], 7, 10);
        return view('dashboard.penerimaan',compact('data','PaguPNBP','chart'));

    }
}
