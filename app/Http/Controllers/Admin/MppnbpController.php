<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\RefWilayah;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\DataProfileSatker;
use Illuminate\Support\Facades\Cache;

class MppnbpController extends Controller
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

    public function index()
    {
        $year = $this->data['setyear'];
        $data = RefWilayah::
        with(['satker.pagupnbp' => function($query) use($year) {
            $query->where('TA',$year);
        }])
        ->with(['satker.belanjapnbp' => function($query) use($year) {
            $query->where('TA',$year);
        }])
        ->with(['satker.rpdpnbp' => function($query) use($year) {
            $query->where('tahun',$year);
        }])
        ->with(['satker.mp' => function($query) use($year) {
            $query->where('TA',$year);
        }])
        ->get();
        // return response()->json($data);
        return view('apps.monitoring-rpd-mp',compact('data'));

    }
}
