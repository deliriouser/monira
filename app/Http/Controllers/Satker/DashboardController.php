<?php

namespace App\Http\Controllers\Satker;

use App\Http\Controllers\Controller;
use App\Models\BelanjaDipa;
use App\Models\BelanjaDipaCovid;
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
use App\Models\DataPrognosa;
use App\Models\DataMessage;
use App\Models\DataMessageAttachment;
use App\Models\DataMessageSatker;
use App\Models\PaguDipaCovid;
use App\Models\SnipperRefJabatan;
use App\Models\SnipperRefSk;

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

            // $dipaCovid = PaguDipa::where('KdSatker',Auth::user()->kdsatker)
            //                     ->where('TA',$year)
            //                     ->where('IsActive','1')
            //                     ->wherehas('isCovid')
            //                     ->get()->sum('Amount');
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

    public function setyear(Request $request, $tahun)
    {
        $request->session()->put('setyear', $tahun);
        Cache::flush();
        return redirect()->route('/');
    }

    public function belanja()
    {
        $year     = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;


        $DashBelanja = Cache::remember('DashBelanja_'.Auth:: user()->kdsatker,3600, function () use ($year,$KdSatker) {
            return DB::table('monira_ref_belanja')
                ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Belanja
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0 AND KdSatker=$KdSatker
                                GROUP BY Belanja) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.Belanja', '=', 'monira_ref_belanja.id' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Belanja
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1 AND KdSatker=$KdSatker
                                GROUP BY Belanja) as monira_data_dipa
                        "),
                            'monira_data_dipa.Belanja', '=', 'monira_ref_belanja.id' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Belanja
                        FROM monira_data_belanja
                            WHERE TA=$year AND KdSatker=$KdSatker GROUP BY Belanja) as monira_data_belanja
                        "),
                            'monira_data_belanja.Belanja', '=', 'monira_ref_belanja.id')
            ->selectRaw("
                        id as Belanja,
                        ifnull(PaguAwal,0) as PaguAwal,ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->get();

        });

        $DashKegiatan = Cache::remember('DashKegiatan_'.Auth:: user()->kdsatker, 3600, function () use ($year,$KdSatker) {
            return DB::table('monira_ref_kegiatan')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Kegiatan
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0 AND KdSatker=$KdSatker
                                GROUP BY Kegiatan) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.Kegiatan
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1 AND KdSatker=$KdSatker
                                GROUP BY Kegiatan) as monira_data_dipa
                        "),
                            'monira_data_dipa.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Kegiatan
                        FROM monira_data_belanja
                            WHERE TA=$year AND KdSatker=$KdSatker GROUP BY Kegiatan) as monira_data_belanja
                        "),
                            'monira_data_belanja.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan')
            ->selectRaw("
                        KdKegiatan,NamaKegiatan,
                        ifnull(PaguAwal,0) as PaguAwal,ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('pagu','!=','null')
            ->get();


        });

        $DashSDana = Cache::remember('DashSDana_'.Auth:: user()->kdsatker,3600, function () use ($year,$KdSatker) {
        return DB::table('monira_ref_sumber_dana')
        ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.SumberDana
                        FROM monira_data_dipa
                            WHERE TA=$year AND Revision=0 AND KdSatker=$KdSatker
                                GROUP BY SumberDana) as monira_data_dipa_awal
                        "),
                            'monira_data_dipa_awal.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.SumberDana
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1 AND KdSatker=$KdSatker
                                GROUP BY SumberDana) as monira_data_dipa
                        "),
                            'monira_data_dipa.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana' )
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.SumberDana
                        FROM monira_data_belanja
                            WHERE TA=$year AND KdSatker=$KdSatker
                                GROUP BY SumberDana) as monira_data_belanja
                        "),
                            'monira_data_belanja.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana')
            ->selectRaw("
                        KodeSumberDana,NamaSumberDana,
                        ifnull(PaguAwal,0) as PaguAwal,ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,
                        ifnull(Pagu,0)-ifnull(Realisasi,0) as Sisa,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('pagu','!=','null')
            ->get();

        });

        $previusYear = $year-1;

        $DataBelanja = Cache::remember('DataBelanja_'.Auth:: user()->kdsatker,3600, function () use ($year,$previusYear,$KdSatker) {
            return BelanjaDipa::selectRaw('TA,MONTH(tanggal) as bulan, sum(Amount) as DSA')
            ->groupby(DB::Raw("bulan,TA"))
            ->orderby(DB::Raw("bulan"),'ASC')
            ->whereRaw('(TA='.$year.' OR TA='.$previusYear.')')
            ->where('KdSatker',$KdSatker)
            ->get();
        });

        $DataPrognosa = Cache::remember('DataPrognosa_'.Auth:: user()->kdsatker,3600, function () use ($year,$KdSatker) {
            return DataPrognosa::selectRaw('Bulan, sum(Amount) as Amount')
            ->groupby(DB::Raw("bulan,TA"))
            ->orderby(DB::Raw("bulan"),'ASC')
            ->where('TA',$year)
            ->where('KdSatker',$KdSatker)
            ->get();
            });



        $DataPagu = Cache::remember('DataPagu_'.Auth:: user()->kdsatker,3600, function () use ($year,$previusYear,$KdSatker) {
            return PaguDipa::selectRaw('TA,sum(Amount) as DIPA')
            ->groupby(DB::Raw("TA"))
            ->whereRaw('(TA='.$year.' OR TA='.$previusYear.')')
            ->where('KdSatker',$KdSatker)
            ->where('IsActive','1')
            ->get();
        });

        $data = PrepareChart($DataPagu,$DataBelanja,$year);
        $data_prognosa = PrepareSingleChart($DashBelanja->sum('Pagu'),$DataPrognosa);

        // return response()->json($DashBelanja->sum('Pagu'));

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

    public function penerimaan()
    {
        $KdSatker = Auth:: user()->kdsatker;
        $year = $this->data['setyear'];
        $data = SimpleCurl::get('http://datacenter.keuanganhubla.com/api/api/get_data_satker_bytahun/'.$year.'/'.$KdSatker)->getResponseAsCollection();
        // return response()->json($data);
        $PaguPNBP = Cache::remember('PaguPNBP_'.Auth:: user()->kdsatker,3600, function () use ($year,$KdSatker) {
            return DB::table('monira_ref_sumber_dana')
            ->leftjoin(DB::raw("(
                SELECT sum(monira_data_dipa.Amount) as Pagu,monira_data_dipa.SumberDana
                    FROM monira_data_dipa
                        WHERE TA=$year AND IsActive=1 AND KdSatker=$KdSatker
                            GROUP BY SumberDana) as monira_data_dipa
                    "),
                        'monira_data_dipa.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana' )
        ->leftjoin(DB::raw("(
                SELECT sum(monira_data_mp.Amount) as MP,monira_data_mp.SumberDana
                    FROM monira_data_mp
                        WHERE TA=$year AND KdSatker=$KdSatker GROUP BY SumberDana) as monira_data_mp
                    "),
                        'monira_data_mp.SumberDana', '=', 'monira_ref_sumber_dana.KodeSumberDana')

        ->leftjoin(DB::raw("(
                SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.SumberDana
                    FROM monira_data_belanja
                        WHERE TA=$year AND KdSatker=$KdSatker GROUP BY SumberDana) as monira_data_belanja
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
        $PNBPmonth = Cache::remember('PNBPmonth_'.Auth:: user()->kdsatker,3600, function () use ($year,$previusYear,$KdSatker) {
            return PendapatanDipa::selectRaw('TA,MONTH(tanggal) as bulan, sum(Amount) as PNBP')
        ->groupby(DB::Raw("bulan,TA"))
        ->orderby(DB::Raw("bulan"),'ASC')
        ->whereRaw('(TA='.$year.' OR TA='.$previusYear.')')
        ->where('KdSatker',$KdSatker)
        ->get();
    });

            // return response()->json($PNBPmonth);


        $CurPNBP = array();
        $PrevPNBP = array();
        foreach($PNBPmonth as $item) {
            if($item->TA==$year) {
                $CurPNBP[] = ($item->PNBP);
            } else {
                $PrevPNBP[] = ($item->PNBP);
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
