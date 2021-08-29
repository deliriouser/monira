<?php

namespace App\Http\Controllers\Satker;


use App\Http\Controllers\Controller;
use App\Models\BelanjaDipa;
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
use App\Models\DataMP;
use App\Models\DataRpdmp;
use App\Models\RefNamaBulan;
use App\Models\SnipperRefJabatan;
use App\Models\SnipperRefSk;
Use Illuminate\Support\Facades\Crypt;
use App\Models\BelanjaDipaCovid;
use App\Models\PaguDipaCovid;


class MPController extends Controller
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

    public function index()
    {

        $year = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;

        $data = RefNamaBulan::with(['rpd' => function($query) use($year,$KdSatker) {
            $query->where('tahun',$year);
            $query->where('kode_satker',$KdSatker);
        }])
        ->with(['semp' => function($query) use($year) {
            $query->where('TA',$year);
        }])
        ->with(['mp' => function($query) use($year,$KdSatker) {
            $query->where('TA',$year);
            $query->where('KdSatker',$KdSatker);
        }])
        ->with(['dsa' => function($query) use($year,$KdSatker) {
            $query->where('TA',$year);
            $query->where('KdSatker',$KdSatker);
            $query->where('SumberDana','D');
            $query->orwhere('SumberDana','F');
        }])
        ->get();
        $paguPNBP = PaguDipa::where('KdSatker',$KdSatker)->where('TA',$year)->whereRaw(DB::Raw("(SumberDana='D' OR SumberDana='F') AND isActive=1"))->sum('Amount');
        $alokasiMP = DataMP::where('KdSatker',$KdSatker)->where('TA',$year)->sum('Amount');
        // return response()->json($data);
        return view('apps.mppnbp',compact('data','paguPNBP','alokasiMP'));
    }

    public function openmodal($what,$id)
    {
        $year     = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;

        switch ($what) {
            case 'create':
                $get_id = explode(".",$id);
                $id     = $get_id[0];
                $sisa   = $get_id[1];

                $data = PaguDipa::with('keterangan')->where('isActive','1')->where('TA',$year)->where('KdSatker',$KdSatker)->where('SumberDana','D')->groupby('Akun')->orderby('Akun','ASC')->select('Akun')->get();
                // return response()->json($data);
                return view('apps.data-modal-rpd',compact('what','id','data','sisa'));
            break;
            case 'read':
                $data = DataRpdmp::with('ketAkun')->where('kode_satker',$KdSatker)->where('tahun',$year)->where('bulan',$id)->orderby('prioritas','desc')->get();
                // return response()->json($data);
                return view('apps.data-modal-rpd',compact('what','id','data'));
            break;
        }
    }

    public function post(Request $request)
    {
        // return $request->all();

        $post = DataRpdmp::create([
            'kode_satker' => Auth:: user()->kdsatker,
            'bulan'       => request('bulan'),
            'akun'        => request('akun'),
            'jumlah'      => onlynumber(request('jumlah')),
            'keterangan'  => request('keterangan'),
            'prioritas'   => request('prioritas'),
            'tahun'       => $this->data['setyear'],
        ]);
    }

    public function status($status,$id,$what)
    {
        $findID = Crypt::decrypt($id);
        switch ($what) {
            case 'rpd':
                $data = DataRpdmp::where('id',$findID)->delete();
                break;
        }
    }
}
