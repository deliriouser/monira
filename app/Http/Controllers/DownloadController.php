<?php

namespace App\Http\Controllers;

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
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExcell;
use App\Exports\ReportExcellSpending;
use App\Exports\ReportExcellPrognosa;
use Illuminate\Support\Carbon;
use App\Models\DataMessage;
use App\Models\DataMessageAttachment;
use App\Models\DataMessageSatker;
use App\Models\RefNamaBulan;
use App\Models\DataMP;
use App\Models\SnipperPejabat;
use Illuminate\Support\Facades\Crypt;

class DownloadController extends Controller
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
                'setyear' => $year,
                'today'   => DATE ('Y'),
                ));


            return $next($request);
        });

    }

    public function index($id)
    {
        $filename = Crypt::decrypt($id);
        // echo $filename;
        return response()->file(storage_path("app/{$filename}"));
    }

    public function load($what)
    {
        $year = $this->data['setyear'];

        switch ($what) {
            case 'snipper':
                $item = SnipperPejabat::with(['detiljabatan' => function($query) use($year) {
                    $query->where(DB::Raw("YEAR(tmt_jabatan)"),$year);
                }])
                ->where('kode_satker',Auth::user()->kdsatker)->where('TA',$year)->where('status','1')->orderby('status','Desc')->orderby('jabatan','Asc')->get();
                // return response()->json($item);
                // $pdf = PDF::loadView('reports.report-pdf-snipper', compact('item'));
                return view('reports.report-pdf-snipper',compact('item'));

                // return $pdf->stream("REKAP PEJABAT PERBENDAHARAAN ".Auth::user()->satker->NamaSatuanKerja.".pdf");
            break;

            case 'rpd':
                $KdSatker = Auth:: user()->kdsatker;
                $data = RefNamaBulan::with(['rpd' => function($query) use($year,$KdSatker) {
                    $query->where('tahun',$year);
                    $query->where('kode_satker',$KdSatker);
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
                $pdf = PDF::loadView('reports.report-pdf-rpd', compact('data','paguPNBP','alokasiMP','year'));
                return $pdf->stream("REKAP RPD MP PNBP ".Auth::user()->satker->NamaSatuanKerja.".pdf");

            break;
        }
        // dd(Auth::user());

    }

}
