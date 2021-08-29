<?php

namespace App\Http\Controllers\Satker
;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\DataJustifikasi;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\RefWilayah;
use App\Models\DataProfileSatker;
use Illuminate\Support\Facades\Cache;
use App\Models\DataPrognosa;
use App\Models\User;
use App\Models\PaguDipa;
use App\Models\BelanjaDipa;
use App\Models\DataMessage;
use App\Models\DataMessageAttachment;
use App\Models\DataMessageSatker;
use App\Models\SnipperRefSk;
use App\Models\BelanjaDipaCovid;
use App\Models\PaguDipaCovid;

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
        $KdSatker = Auth:: user()->kdsatker;

        $dataSQL = Cache::remember('prognosa_satker'.Auth:: user()->kdsatker, 3600, function () use ($year,$KdSatker) {
            return DB::table('monira_ref_output')
            ->leftjoin('monira_ref_kegiatan','monira_ref_output.KdKegiatan','monira_ref_kegiatan.KdKegiatan')
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_dipa.Amount) as PaguAwal,monira_data_dipa.Kegiatan,monira_data_dipa.Output,monira_data_dipa.Akun,monira_data_dipa.SumberDana,monira_data_dipa.Kewenangan,monira_data_dipa.Program
                        FROM monira_data_dipa
                            WHERE TA=$year AND IsActive=1 AND KdSatker=$KdSatker
                                GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_dipa_awal
                        "), function($join){
                            $join->on('monira_data_dipa_awal.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                            $join->on('monira_data_dipa_awal.Output', '=', 'monira_ref_output.KdOutput');

                        })
            ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_prognosa.Amount) as Prognosa,monira_data_prognosa.Kegiatan,monira_data_prognosa.Output,monira_data_prognosa.Akun,monira_data_prognosa.SumberDana,monira_data_prognosa.Kewenangan,monira_data_prognosa.Program
                        FROM monira_data_prognosa
                            WHERE TA=$year AND KdSatker=$KdSatker
                                GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_prognosa
                        "), function($join){
                            $join->on('monira_data_prognosa.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                            $join->on('monira_data_prognosa.Output', '=', 'monira_ref_output.KdOutput');
                            $join->on('monira_data_prognosa.Akun', '=', 'monira_data_dipa_awal.Akun');
                            $join->on('monira_data_prognosa.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                            $join->on('monira_data_prognosa.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                            $join->on('monira_data_prognosa.Program', '=', 'monira_data_dipa_awal.Program');

                        })
            ->leftjoin(DB::raw("(
                    SELECT monira_data_justifikasi.Justifikasi,monira_data_justifikasi.Kegiatan,monira_data_justifikasi.Output,monira_data_justifikasi.Akun,monira_data_justifikasi.SumberDana,monira_data_justifikasi.Kewenangan,monira_data_justifikasi.Program
                        FROM monira_data_justifikasi
                            WHERE TA=$year AND KdSatker=$KdSatker
                                GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_justifikasi
                        "), function($join){
                            $join->on('monira_data_justifikasi.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                            $join->on('monira_data_justifikasi.Output', '=', 'monira_ref_output.KdOutput');
                            $join->on('monira_data_justifikasi.Akun', '=', 'monira_data_dipa_awal.Akun');
                            $join->on('monira_data_justifikasi.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                            $join->on('monira_data_justifikasi.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                            $join->on('monira_data_justifikasi.Program', '=', 'monira_data_dipa_awal.Program');
                        })
            ->leftjoin('monira_ref_akun','KdAkun','monira_data_dipa_awal.Akun')
            ->leftjoin('monira_ref_sumber_dana','KodeSumberDana','monira_data_dipa_awal.SumberDana')
            ->leftjoin('monira_ref_kewenangan','IdKewenangan','monira_data_dipa_awal.Kewenangan')
            ->leftjoin('monira_ref_program','monira_ref_program.KdProgram','monira_data_dipa_awal.Program')
            ->selectRaw("
                        monira_ref_kegiatan.KdKegiatan as KodeHeader,monira_ref_kegiatan.NamaKegiatan as NamaHeader,
                        monira_ref_output.KdOutput as KodeSubHeader,monira_ref_output.NamaOutput as NamaSubHeader,
                        monira_ref_sumber_dana.KodeSumberDana, monira_ref_sumber_dana.ShortKode,
                        monira_ref_akun.KdAkun as Kode, monira_ref_akun.NamaAkun as Keterangan,
                        monira_ref_kewenangan.IdKewenangan as KodeKewenangan, monira_ref_kewenangan.NamaKewenangan,
                        monira_ref_program.KdProgram as KodeProgram, monira_ref_program.NamaProgram,
                        ifnull(PaguAwal,0) as Pagu,
                        (monira_data_prognosa.Prognosa/PaguAwal)*100 as Persen,
                        ifnull(monira_data_prognosa.Prognosa,0) as Prognosa,
                        monira_data_justifikasi.Justifikasi
                        ")
            ->where('PaguAwal','!=','null')
            ->orderby('KodeHeader','ASC')
            ->orderby('KdOutput','ASC')
            ->orderby('KdAkun','ASC')
            ->get();
        });


        $data    = NestCollection($dataSQL,'prognosa');

        // return response()->json($data);

        $locking = Auth:: user()->satker->IsLockPrognosa;
        $level   = "Propinsi";
        return view('apps.prognosa-satker',compact('data','locking'));

    }


    public function modal($action,$id)
    {
        $year     = $this->data['setyear'];
        $id       = substr($id,0,-10);
        $kdsatker = Auth:: user()->kdsatker;
        switch ($action) {
            case 'insertPrognosa':
            $data =  DB::select("SELECT
                IFNULL(MAX(CASE WHEN bulan=1 THEN DSA ELSE 0 END),0) AS 'JAN',
                IFNULL(MAX(CASE WHEN bulan=2 THEN DSA ELSE 0 END),0) AS 'FEB',
                IFNULL(MAX(CASE WHEN bulan=3 THEN DSA ELSE 0 END),0) AS 'MAR',
                IFNULL(MAX(CASE WHEN bulan=4 THEN DSA ELSE 0 END),0) AS 'APR',
                IFNULL(MAX(CASE WHEN bulan=5 THEN DSA ELSE 0 END),0) AS 'MEI',
                IFNULL(MAX(CASE WHEN bulan=6 THEN DSA ELSE 0 END),0) AS 'JUN',
                IFNULL(MAX(CASE WHEN bulan=7 THEN DSA ELSE 0 END),0) AS 'JUL',
                IFNULL(MAX(CASE WHEN bulan=8 THEN DSA ELSE 0 END),0) AS 'AGS',
                IFNULL(MAX(CASE WHEN bulan=9 THEN DSA ELSE 0 END),0) AS 'SEP',
                IFNULL(MAX(CASE WHEN bulan=10 THEN DSA ELSE 0 END),0) AS 'OKT',
                IFNULL(MAX(CASE WHEN bulan=11 THEN DSA ELSE 0 END),0) AS 'NOV',
                IFNULL(MAX(CASE WHEN bulan=12 THEN DSA ELSE 0 END),0) AS 'DES'
            FROM
                (SELECT
                    MONTH(Tanggal) as Bulan,
                    Sum(Amount) as DSA
                FROM monira_data_belanja
                WHERE
                    id = '$id' AND
                    KdSatker = $kdsatker AND
                    TA = $year
                GROUP BY MONTH(Tanggal) ) as Final");

                // return response()->json($data);

                $titleHead = "Input Data Prognosa";
                return view('apps.data-modal',compact('action','titleHead','data'));
                break;
            case 'updatePrognosa':
                $titleHead = "Update Data Prognosa";
                $data      = DataPrognosa::where('minggu','0')
                    ->with(['mingguan' => function($query) use($year,$id,$kdsatker) {
                        $query->where('TA',$year);
                        $query->where('id',$id);
                        $query->where('KdSatker',$kdsatker);
                        }])
                    ->where('KdSatker',Auth::user()->kdsatker)
                    ->where('TA',$this->data['setyear'])
                    ->where('id',$id)
                    ->get();


                $justifikasi = DataJustifikasi::where('KdSatker',Auth::user()->kdsatker)
                    ->where('TA',$this->data['setyear'])
                    ->whereraw(DB::RAW("id LIKE '%$id%'"))
                    ->first();

                    // return response()->json($justifikasi);

                $data_dsa =  DB::select("SELECT
                    IFNULL(MAX(CASE WHEN bulan=1 THEN DSA ELSE 0 END),0) AS 'JAN',
                    IFNULL(MAX(CASE WHEN bulan=2 THEN DSA ELSE 0 END),0) AS 'FEB',
                    IFNULL(MAX(CASE WHEN bulan=3 THEN DSA ELSE 0 END),0) AS 'MAR',
                    IFNULL(MAX(CASE WHEN bulan=4 THEN DSA ELSE 0 END),0) AS 'APR',
                    IFNULL(MAX(CASE WHEN bulan=5 THEN DSA ELSE 0 END),0) AS 'MEI',
                    IFNULL(MAX(CASE WHEN bulan=6 THEN DSA ELSE 0 END),0) AS 'JUN',
                    IFNULL(MAX(CASE WHEN bulan=7 THEN DSA ELSE 0 END),0) AS 'JUL',
                    IFNULL(MAX(CASE WHEN bulan=8 THEN DSA ELSE 0 END),0) AS 'AGS',
                    IFNULL(MAX(CASE WHEN bulan=9 THEN DSA ELSE 0 END),0) AS 'SEP',
                    IFNULL(MAX(CASE WHEN bulan=10 THEN DSA ELSE 0 END),0) AS 'OKT',
                    IFNULL(MAX(CASE WHEN bulan=11 THEN DSA ELSE 0 END),0) AS 'NOV',
                    IFNULL(MAX(CASE WHEN bulan=12 THEN DSA ELSE 0 END),0) AS 'DES'
                FROM
                    (SELECT
                        MONTH(Tanggal) as Bulan,
                        Sum(Amount) as DSA
                    FROM monira_data_belanja
                    WHERE
                        id = '$id' AND
                        KdSatker = $kdsatker AND
                        TA = $year
                    GROUP BY MONTH(Tanggal) ) as Final");
                return view('apps.data-modal',compact('action','titleHead','data','justifikasi','data_dsa'));
                break;
                case 'locking':
                    return view('apps.data-modal',compact('action'));
                break;
        }
    }

    public function post(Request $request)
    {

        // return $request->all();

        $year          = $this->data['setyear'];
        $profileSatker = User::with('profile')->where('kdsatker',Auth:: user()->kdsatker)->first();


        $bulan        = count(request('bulan'));
        $id           = explode(".",request('id'));
        $KdKegiatan   = $id[0];
        $KdOutput     = $id[1];
        $KdAkun       = $id[2];
        $KdDana       = $id[3];
        $KdKewenangan = $id[4];
        $KdProgram    = $id[5];
        $Belanja      = substr($KdAkun,0,2);
        $idInsert     = substr(request('id'),0,-10);
        foreach (request('bulan') as $key => $jumlah) {
            $bulan      = $key+1;
            $data       = DataPrognosa::updateOrCreate([
                'TA'       => $year,
                'id'       => $idInsert,
                'Bulan'    => $bulan,
                'KdSatker' => Auth:: user()->kdsatker,
            ], [
                'TA'         => $year,
                'id'         => $idInsert,
                'Bulan'      => $bulan,
                'BulanChild' => '0',
                'Minggu'     => request('minggu') ?? '0',
                'Belanja'    => $Belanja,
                'KdSatker'   => Auth:: user()->kdsatker,
                'Program'    => $KdProgram,
                'Kegiatan'   => $KdKegiatan,
                'Output'     => $KdOutput,
                'Akun'       => $KdAkun,
                'Kewenangan' => $KdKewenangan,
                'SumberDana' => $KdDana,
                'Lokasi'     => $profileSatker->satker->KodeWilayah,
                'Amount'     => onlynumber($jumlah),
            ]);
        }

        for($i=1; $i<=12; $i++) {
            if(!empty($request->has('bulan_'.$i.'_minggu'))) {
            foreach (request('bulan_'.$i.'_minggu') as $key => $jumlah) {
                $minggu = $key+1;
                $data       = DataPrognosa::updateOrCreate([
                    'TA'       => $year,
                    'id'       => $idInsert,
                    'Bulan'    => $i,
                    'Minggu'   => $minggu,
                    'KdSatker' => Auth:: user()->kdsatker,
                ], [
                    'TA'         => $year,
                    'id'         => $idInsert,
                    'Bulan'      => $i,
                    'BulanChild' => $i,
                    'Minggu'     => $minggu,
                    'Belanja'    => $Belanja,
                    'KdSatker'   => Auth:: user()->kdsatker,
                    'Program'    => $KdProgram,
                    'Kegiatan'   => $KdKegiatan,
                    'Output'     => $KdOutput,
                    'Akun'       => $KdAkun,
                    'Kewenangan' => $KdKewenangan,
                    'SumberDana' => $KdDana,
                    'Lokasi'     => $profileSatker->satker->KodeWilayah,
                    'Amount'     => onlynumber($jumlah),
                ]);
                }
            }
        }

        $justifikasi = DataJustifikasi::updateOrCreate([
            'TA'       => $year,
            'id'       => substr(request('id'),0,-7),
            'KdSatker' => Auth:: user()->kdsatker,
        ], [
            'TA'          => $year,
            'id'          => substr(request('id'),0,-7),
            'Bulan'       => '',
            'Minggu'      => '',
            'Belanja'     => $Belanja,
            'KdSatker'    => Auth:: user()->kdsatker,
            'Program'     => $KdProgram,
            'Kegiatan'    => $KdKegiatan,
            'Output'      => $KdOutput,
            'Akun'        => $KdAkun,
            'Kewenangan'  => $KdKewenangan,
            'SumberDana'  => $KdDana,
            'Lokasi'      => $profileSatker->satker->KodeWilayah,
            'Justifikasi' => request('justifikasi'),
        ]);

        Cache::forget('prognosa_satker'.Auth:: user()->kdsatker);

    }

    public function status($status,$id,$what)
    {
        switch ($what) {
            case 'reset':
                $data = DataPrognosa::where('id',$id)->where('KdSatker',Auth:: user()->kdsatker)->delete();
                Cache::forget('prognosa_satker'.Auth:: user()->kdsatker);
                break;
        }
    }

}
