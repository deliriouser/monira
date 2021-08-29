<?php

namespace App\Http\Controllers\Satker;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\RefWilayah;
use App\Models\DataProfileSatker;
use App\Models\PaguDipa;
use App\Models\DataPrognosa;
use Illuminate\Support\Facades\Cache;
use App\Models\DataMessage;
use App\Models\DataMessageAttachment;
use App\Models\DataMessageSatker;
use App\Models\SnipperRefSk;
use App\Models\BelanjaDipa;
use App\Models\BelanjaDipaCovid;
use App\Models\PaguDipaCovid;

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
                            WHERE TA=$year GROUP BY KdSatker) as monira_data_belanja
                        "),
                            'monira_data_belanja.KdSatker', '=', 'monira_ref_satker.KodeSatker' )
            ->leftjoin('monira_ref_wilayah','monira_ref_wilayah.KodeWilayah','=','monira_ref_satker.KodeWilayah')
            ->selectRaw("

                        KodeSatker,NamaSatuanKerja,WilayahName,
                        ifnull(PaguAwal,0) as PaguAwal,ifnull(Pagu,0) as Pagu,ifnull(Realisasi,0) as Realisasi,
                        (Realisasi/Pagu)*100 as Persen
                        ")
            ->where('PaguAwal','!=','NULL')
            ->orderBy('Persen','DESC')
            ->get();
            });
        // return response()->json($rankSatker);
        return view('apps.ranking-satker',compact('data'));


    }

}
