<?php

namespace App\Http\Controllers\Satker;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\BelanjaDipaCovid;
use App\Models\BelanjaDipaPadatKarya;
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
use App\Models\PaguAkunPadatKarya;
use App\Models\PaguDipaCovid;
use App\Models\PaguDipaPadatKarya;
use App\Models\RefAkun;
use App\Models\RefDesa;
use App\Models\RefNamaBulan;
use App\Models\RefSatuan;
use App\Models\SnipperRefSk;
use Ramsey\Uuid\Uuid;
Use Illuminate\Support\Facades\Crypt;
use App\Models\BelanjaDipa;
use App\Models\DataSPDPadatKarya;
use Illuminate\Support\Carbon;


class MonitoringController extends Controller
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

        switch ($segment) {
            case 'covid':
                $dataSQL = Cache::remember('dipa_covid_satker'.Auth:: user()->kdsatker, 1, function () use ($year,$KdSatker) {
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
                            SELECT monira_data_dipa_covid.BudgetType,monira_data_dipa_covid.idtable,monira_data_dipa_covid.guid,monira_data_dipa_covid.Amount,monira_data_dipa_covid.Uraian,monira_data_dipa_covid.Volume,monira_data_dipa_covid.Satuan,monira_data_dipa_covid.Kegiatan,monira_data_dipa_covid.Output,monira_data_dipa_covid.Akun,monira_data_dipa_covid.SumberDana,monira_data_dipa_covid.Kewenangan,monira_data_dipa_covid.Program
                                FROM monira_data_dipa_covid
                                    WHERE TA=$year AND KdSatker=$KdSatker ORDER BY monira_data_dipa_covid.idtable ASC) as monira_data_dipa_covid
                                "), function($join){
                                    $join->on('monira_data_dipa_covid.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                                    $join->on('monira_data_dipa_covid.Output', '=', 'monira_ref_output.KdOutput');
                                    $join->on('monira_data_dipa_covid.Akun', '=', 'monira_data_dipa_awal.Akun');
                                    $join->on('monira_data_dipa_covid.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                                    $join->on('monira_data_dipa_covid.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                                    $join->on('monira_data_dipa_covid.Program', '=', 'monira_data_dipa_awal.Program');

                                })
                    ->leftjoin(DB::raw("(
                        SELECT monira_data_belanja_covid.idtable,monira_data_belanja_covid.guid,sum(monira_data_belanja_covid.Amount) as pencairan,monira_data_belanja_covid.Uraian,sum(monira_data_belanja_covid.Volume) as Volume,monira_data_belanja_covid.Satuan,monira_data_belanja_covid.Kegiatan,monira_data_belanja_covid.Output,monira_data_belanja_covid.Akun,monira_data_belanja_covid.SumberDana,monira_data_belanja_covid.Kewenangan,monira_data_belanja_covid.Program
                            FROM monira_data_belanja_covid
                                WHERE TA=$year AND KdSatker=$KdSatker GROUP BY guid ORDER BY monira_data_belanja_covid.idtable ASC) as monira_data_belanja_covid
                            "), function($join){
                                $join->on('monira_data_belanja_covid.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                                $join->on('monira_data_belanja_covid.Output', '=', 'monira_ref_output.KdOutput');
                                $join->on('monira_data_belanja_covid.Akun', '=', 'monira_data_dipa_awal.Akun');
                                $join->on('monira_data_belanja_covid.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                                $join->on('monira_data_belanja_covid.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                                $join->on('monira_data_belanja_covid.Program', '=', 'monira_data_dipa_awal.Program');
                                $join->on('monira_data_belanja_covid.guid', '=', 'monira_data_dipa_covid.guid');

                            })
                    ->leftjoin(DB::raw("(
                    SELECT sum(monira_data_belanja.Amount) as Realisasi,monira_data_belanja.Kegiatan,monira_data_belanja.Output,monira_data_belanja.Akun,monira_data_belanja.SumberDana,monira_data_belanja.Kewenangan,monira_data_belanja.Program
                        FROM monira_data_belanja
                            WHERE TA=$year AND KdSatker=$KdSatker
                                GROUP BY Kegiatan,Output,Akun,SumberDana,Kewenangan,Program) as monira_data_belanja
                        "), function($join){
                            $join->on('monira_data_belanja.Kegiatan', '=', 'monira_ref_kegiatan.KdKegiatan');
                            $join->on('monira_data_belanja.Output', '=', 'monira_ref_output.KdOutput');
                            $join->on('monira_data_belanja.Akun', '=', 'monira_data_dipa_awal.Akun');
                            $join->on('monira_data_belanja.SumberDana', '=', 'monira_data_dipa_awal.SumberDana');
                            $join->on('monira_data_belanja.Kewenangan', '=', 'monira_data_dipa_awal.Kewenangan');
                            $join->on('monira_data_belanja.Program', '=', 'monira_data_dipa_awal.Program');

                        })
                    ->leftjoin(DB::RAW("(SELECT * FROM monira_ref_akun WHERE isCovid=1) as monira_ref_akun"),
                                function($join){
                                    $join->on('monira_ref_akun.KdAkun', '=', 'monira_data_dipa_awal.Akun');
                                })
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
                                monira_data_dipa_covid.guid,
                                monira_data_dipa_covid.Amount as PaguKegiatan,
                                monira_data_belanja_covid.pencairan as BelanjaKegiatan,
                                monira_data_dipa_covid.Uraian,
                                monira_data_dipa_covid.BudgetType as Catatan,
                                monira_data_dipa_covid.Volume as VolumePagu,
                                monira_data_belanja_covid.Volume as VolumeBelanja,
                                monira_data_dipa_covid.Satuan as SatuanPagu,
                                monira_data_belanja_covid.Satuan as SatuanBelanja,
                                ifnull(PaguAwal,0) as Pagu,
                                ifnull(Realisasi,0) as Realisasi,
                                (Realisasi/PaguAwal)*100 as Persen
                                ")
                    ->where('PaguAwal','!=','null')
                    ->whereNotNull('monira_ref_akun.KdAkun')
                    ->orderby('KodeHeader','ASC')
                    ->orderby('KdOutput','ASC')
                    ->orderby('KdAkun','ASC')
                    ->orderby('monira_data_dipa_covid.idtable','ASC')
                    ->get();
                });


                $data = NestCollection($dataSQL,'covid');
                // return response()->json($data);

                $bulan = RefNamaBulan::get();
                return view('apps.monitoring-covid',compact('data','bulan'));
                break;


            case 'padatkarya':
                $bulan = RefNamaBulan::get();
                $akun = PaguDipa::where('Belanja','52')->where('TA',$year)->where('isActive','1')->where('KdSatker',Auth:: user()->kdsatker)->groupby('Id')->with('keterangan')->get();
                $data = PaguDipaPadatKarya::with('akun.akun')->with('sumrealisasi')->with('akun')->with('realisasi.akun')->where('TA',$year)->where('KdSatker',Auth:: user()->kdsatker)
                ->leftjoin(DB::RAW("(SELECT nama as Propinsi,kode FROM monira_ref_desa) as monira_ref_desa_prop"),'monira_ref_desa_prop.kode',DB::raw("substr(KdLokasi,1,2)"))
                ->leftjoin(DB::RAW("(SELECT nama as Kabupaten,kode FROM monira_ref_desa) as monira_ref_desa_kab"),'monira_ref_desa_kab.kode',DB::raw("substr(KdLokasi,1,5)"))
                ->leftjoin(DB::RAW("(SELECT nama as Kecamatan,kode FROM monira_ref_desa) as monira_ref_desa_kec"),'monira_ref_desa_kec.kode',DB::raw("substr(KdLokasi,1,8)"))
                ->leftjoin(DB::RAW("(SELECT nama as Desa,kode FROM monira_ref_desa) as monira_ref_desa_nama"),'monira_ref_desa_nama.kode',DB::raw("substr(KdLokasi,1,13)"))
                ->get();
                                // return response()->json($data);


                // $response = \GoogleMaps::load('geocoding')->setParam (['address' =>])->get();


                return view('apps.monitoring-padat-karya',compact('bulan','akun','data'));
                break;
        }
    }

    public function modal($action,$id)
    {
        $year     = $this->data['setyear'];
        $kdsatker = Auth:: user()->kdsatker;
        switch ($action) {
            case 'insertKegiatanCovid':
                $satuan    = RefSatuan::get();
                $titleHead = "Input Data Kegiatan Penanganan Covid-19";
                return view('apps.data-modal-monitoring',compact('action','titleHead','satuan'));
                break;
            case 'updateKegiatanCovid':
                $titleHead = "Update Data Kegiatan Penanganan Covid-19";
                $data      = PaguDipaCovid::where('guid',$id)->first();
                $satuan    = RefSatuan::get();
                // dd($data);
                // return response()->json($data);
                return view('apps.data-modal-monitoring',compact('satuan','data','action','titleHead','satuan'));
                break;
            case 'insertRealisasiCovid':
                $bulan     = RefNamaBulan::get();
                $titleHead = "Input Data Realisasi Penanganan Covid-19";
                $satuan    = RefSatuan::get();
                return view('apps.data-modal-monitoring',compact('action','titleHead','satuan','bulan'));
                break;
            case 'dataRealisasiCovid':
                $titleHead = "Data Realisasi Penanganan Covid-19";
                $data      = BelanjaDipaCovid::where('guid',$id)->get();
                return view('apps.data-modal-monitoring',compact('data','action','titleHead'));
                break;
            case 'insertPadatKarya':
                $titleHead  = "Input Data Kegiatan Padat Karya";
                $KdPropinsi = Auth:: user()->satker->wilayah->KodeDB;
                $akun       = PaguDipa::where('Belanja','52')->where('TA',$year)->where('isActive','1')->where('KdSatker',Auth:: user()->kdsatker)->groupby('Id')->with('keterangan')->get();
                // return response()->json($Akun);
                $lokasi = RefDesa::selectraw('*')->whereraw(DB::RAW("kode LIKE CONCAT($KdPropinsi,'%')"))->get();
                return view('apps.data-modal-monitoring',compact('akun','lokasi','action','titleHead'));
                break;
            case 'editKegiatanPadatKarya':
                $titleHead  = "Edit Data Kegiatan Padat Karya";
                $KdPropinsi = Auth:: user()->satker->wilayah->KodeDB;
                $akun       = PaguDipa::where('Belanja','52')->where('TA',$year)->where('isActive','1')->where('KdSatker',Auth:: user()->kdsatker)->groupby('Id')->with('keterangan')->get();
                // return response()->json($Akun);
                $lokasi = RefDesa::selectraw('*')->whereraw(DB::RAW("kode LIKE CONCAT($KdPropinsi,'%')"))->get();
                $akunDB = PaguAkunPadatKarya::where('guid',$id)->get();
                $dataDB = PaguDipaPadatKarya::where('guid',$id)->first();

                return view('apps.data-modal-monitoring',compact('akunDB','dataDB','akun','lokasi','action','titleHead'));
                break;
            case 'realisasiPadatKarya':
                $data      = PaguAkunPadatKarya::where('guid',$id)->get();
                // dd($data);
                $titleHead = "Input Realisasi Kegiatan Padat Karya";
                return view('apps.data-modal-monitoring',compact('data','action','titleHead'));
                break;
            case 'editRPadatKarya':
                $item      = BelanjaDipaPadatKarya::where('guid_sppd',$id)->first();
                // $data      = PaguAkunPadatKarya::where('guid',$id)->get();
                // dd($item);
                $titleHead = "Edit Realisasi Kegiatan Padat Karya";
                return view('apps.data-modal-monitoring',compact('item','action','titleHead'));
                break;
            case 'showpaguakun':
                $data      = PaguAkunPadatKarya::where('KdSatker',Auth:: user()->kdsatker)->where('Id',$id)->first();
                // dd($data);
                return view('apps.data-modal-monitoring',compact('data','action'));
                break;

        }
    }

    public function post(Request $request)
    {
        // return $request->all();
        $year         = $this->data['setyear'];
        $id           = explode(".",request('id'));
        $KdKegiatan   = $id[0];
        $KdOutput     = $id[1];
        $KdAkun       = $id[2];
        $KdDana       = $id[3];
        $KdKewenangan = $id[4];
        $KdProgram    = $id[5];
        $Belanja      = substr($KdAkun,0,2);
        $idInsert     = substr(request('id'),0,-10);
        $guid         = Uuid::uuid4();
        $type         = request('type');
        switch ($type) {
            case 'kegiatan':
                $insert    = PaguDipaCovid::create([
                    'guid'       => $guid,
                    'TA'         => $year,
                    'id'         => $idInsert,
                    'Belanja'    => $Belanja,
                    'Ba'         => '022',
                    'BaEs1'      => '02204',
                    'KdSatker'   => Auth:: user()->kdsatker,
                    'Lokasi'     => Auth:: user()->satker->wilayah->KodeWilayah,
                    'Program'    => $KdProgram,
                    'Kegiatan'   => $KdKegiatan,
                    'Output'     => $KdOutput,
                    'Akun'       => $KdAkun,
                    'Kewenangan' => $KdKewenangan,
                    'SumberDana' => $KdDana,
                    'BudgetType' => request('catatan'),
                    'Uraian'     => request('kegiatan'),
                    'Volume'     => request('volume'),
                    'Satuan'     => request('satuan'),
                    'Amount'     => onlynumber(request('pagu')),
                ]);
                break;
            case 'updatekegiatan':
                    $insert    = PaguDipaCovid::where('guid',request('guid'))->update([
                        'BudgetType' => request('catatan'),
                        'Uraian'     => request('kegiatan'),
                        'Volume'     => request('volume'),
                        'Satuan'     => request('satuan'),
                        'Amount'     => onlynumber(request('pagu')),
                    ]);
                    break;
            case 'realisasi':
                $data = PaguDipaCovid::where('guid',request('guid'))->first();
                $insert    = BelanjaDipaCovid::create([
                    'guid'       => request('guid'),
                    'TA'         => $year,
                    'id'         => $idInsert,
                    'Belanja'    => $Belanja,
                    'Ba'         => '022',
                    'BaEs1'      => '02204',
                    'KdSatker'   => Auth:: user()->kdsatker,
                    'Lokasi'     => Auth:: user()->satker->wilayah->KodeWilayah,
                    'Nosp2d'     => onlynumber(request('nosp2d')),
                    'Program'    => $KdProgram,
                    'Kegiatan'   => $KdKegiatan,
                    'Output'     => $KdOutput,
                    'Akun'       => $KdAkun,
                    'Kewenangan' => $KdKewenangan,
                    'SumberDana' => $KdDana,
                    'Uraian'     => $data->Uraian,
                    'Volume'     => request('volume'),
                    'Satuan'     => $data->Satuan,
                    'Amount'     => onlynumber(request('realisasi')),
                    'Bulan'      => Carbon::createFromFormat('d/m/Y',$request->post('bulan'))->format('Y-m-d'),
                ]);
                break;
            case 'padatkarya':

                $result = array_map(null,request('akun'),request('pagukegiatan'),request('kegiatan'));
                foreach($result as $data){
                    $id           = explode(".",$data[0]);
                    $KdKegiatan   = $id[0];
                    $KdOutput     = $id[1];
                    $KdAkun       = $id[2];
                    $KdDana       = $id[3];
                    $KdKewenangan = $id[4];
                    $KdProgram    = $id[5];
                    $Belanja      = substr($KdAkun,0,2);

                    $data_insert[] = [
                        'guid'       => $guid,
                        'TA'         => $year,
                        'Id'         => $data[0],
                        'Belanja'    => $Belanja,
                        'Ba'         => '022',
                        'BaEs1'      => '02204',
                        'KdSatker'   => Auth:: user()->kdsatker,
                        'Lokasi'     => Auth:: user()->satker->wilayah->KodeWilayah,
                        'Program'    => $KdProgram,
                        'Kegiatan'   => $KdKegiatan,
                        'Output'     => $KdOutput,
                        'Akun'       => $KdAkun,
                        'Kewenangan' => $KdKewenangan,
                        'SumberDana' => $KdDana,
                        'Uraian'     => $data[2],
                        'Amount'     => onlynumber($data[1]),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                $post = PaguAkunPadatKarya::insert($data_insert);

                $TotalBiayaUpah  = onlynumber(request('jumlahorang'))*onlynumber(request('jumlahhari'))*onlynumber(request('upahharian'));
                $TotalBiayaLain  = onlynumber(request('totalpagu'))-$TotalBiayaUpah;
                $PersenBiayaUpah = ($TotalBiayaUpah/onlynumber(request('totalpagu')))*100;
                $JumlahOrangHari = onlynumber(request('jumlahorang'))*onlynumber(request('jumlahhari'));
                $insert_pagu     = PaguDipaPadatKarya::create([
                        'guid'            => $guid,
                        'TA'              => $year,
                        'KdSatker'        => Auth:: user()->kdsatker,
                        'KdLokasi'        => request('kdlokasi'),
                        'Lokasi'          => Auth:: user()->satker->wilayah->KodeWilayah,
                        'Jadwal'          => request('jadwal'),
                        'Mekanisme'       => request('mekanisme'),
                        'JumlahOrang'     => onlynumber(request('jumlahorang')),
                        'JumlahHari'      => onlynumber(request('jumlahhari')),
                        'JumlahOrangHari' => $JumlahOrangHari,
                        'UpahHarian'      => onlynumber(request('upahharian')),
                        'TotalBiayaUpah'  => $TotalBiayaUpah,
                        'TotalBiayaLain'  => $TotalBiayaLain,
                        'PersenBiayaUpah' => $PersenBiayaUpah,
                        'TotalPagu'       => onlynumber(request('totalpagu')),
                ]);

                break;


            case 'editpadatkarya':

                $result = array_map(null,request('akun'),request('pagukegiatan'),request('kegiatan'),request('idtable'));
                foreach($result as $data){
                    $id           = explode(".",$data[0]);
                    $KdKegiatan   = $id[0];
                    $KdOutput     = $id[1];
                    $KdAkun       = $id[2];
                    $KdDana       = $id[3];
                    $KdKewenangan = $id[4];
                    $KdProgram    = $id[5];
                    $Belanja      = substr($KdAkun,0,2);

                    $insert_sppd    = PaguAkunPadatKarya::updateorcreate([
                        'idtable' => $data[3],
                        'guid'    => request('guid'),

                    ],
                    [
                        'guid'       => request('guid'),
                        'TA'         => $year,
                        'Id'         => $data[0],
                        'Belanja'    => $Belanja,
                        'Ba'         => '022',
                        'BaEs1'      => '02204',
                        'KdSatker'   => Auth:: user()->kdsatker,
                        'Lokasi'     => Auth:: user()->satker->wilayah->KodeWilayah,
                        'Program'    => $KdProgram,
                        'Kegiatan'   => $KdKegiatan,
                        'Output'     => $KdOutput,
                        'Akun'       => $KdAkun,
                        'Kewenangan' => $KdKewenangan,
                        'SumberDana' => $KdDana,
                        'Uraian'     => $data[2],
                        'Amount'     => onlynumber($data[1]),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                $TotalBiayaUpah  = onlynumber(request('jumlahorang'))*onlynumber(request('jumlahhari'))*onlynumber(request('upahharian'));
                $TotalBiayaLain  = onlynumber(request('totalpagu'))-$TotalBiayaUpah;
                $PersenBiayaUpah = ($TotalBiayaUpah/onlynumber(request('totalpagu')))*100;
                $JumlahOrangHari = onlynumber(request('jumlahorang'))*onlynumber(request('jumlahhari'));
                $insert_pagu     = PaguDipaPadatKarya::where('guid',request('guid'))->update([
                    'TA'              => $year,
                    'KdSatker'        => Auth:: user()->kdsatker,
                    'KdLokasi'        => request('kdlokasi'),
                    'Lokasi'          => Auth:: user()->satker->wilayah->KodeWilayah,
                    'Jadwal'          => request('jadwal'),
                    'Mekanisme'       => request('mekanisme'),
                    'JumlahOrang'     => onlynumber(request('jumlahorang')),
                    'JumlahHari'      => onlynumber(request('jumlahhari')),
                    'JumlahOrangHari' => $JumlahOrangHari,
                    'UpahHarian'      => onlynumber(request('upahharian')),
                    'TotalBiayaUpah'  => $TotalBiayaUpah,
                    'TotalBiayaLain'  => $TotalBiayaLain,
                    'PersenBiayaUpah' => $PersenBiayaUpah,
                    'TotalPagu'       => onlynumber(request('totalpagu')),
                ]);

                break;
            case 'realisasipadatkarya':
                // return $request->all();
                $TotalBiayaUpah  = onlynumber(request('jumlahorang'))*onlynumber(request('jumlahhari'))*onlynumber(request('upahharian'));
                // $TotalBiayaLain  = onlynumber(request('totalpagu'))-$TotalBiayaUpah;
                $PersenBiayaUpah = ($TotalBiayaUpah/onlynumber(request('pagu')))*100;
                $JumlahOrangHari = onlynumber(request('jumlahorang'))*onlynumber(request('jumlahhari'));


                $id           = explode(".",request('id'));
                $KdKegiatan   = $id[0];
                $KdOutput     = $id[1];
                $KdAkun       = $id[2];
                $KdDana       = $id[3];
                $KdKewenangan = $id[4];
                $KdProgram    = $id[5];
                $Belanja      = substr($KdAkun,0,2);

                $data_guid = explode(".",request('guid'));
                $idtable   = $data_guid[0];
                $guid_akun = $data_guid[1];

                $data      = PaguDipaPadatKarya::where('guid',$guid_akun)->first();


                $insert_pagu     = BelanjaDipaPadatKarya::create([
                    'idtable'         => $idtable,
                    'guid'            => $guid_akun,
                    'guid_sppd'       => $guid,
                    'Id'              => request('akun'),
                    'TA'              => $year,
                    'Belanja'         => $Belanja,
                    'Ba'              => '022',
                    'BaEs1'           => '02204',
                    'Program'         => $KdProgram,
                    'Kegiatan'        => $KdKegiatan,
                    'Output'          => $KdOutput,
                    'Akun'            => $KdAkun,
                    'Kewenangan'      => $KdKewenangan,
                    'SumberDana'      => $KdDana,
                    'KdSatker'        => Auth:: user()->kdsatker,
                    'KdLokasi'        => $data->KdLokasi,
                    'Lokasi'          => Auth:: user()->satker->wilayah->KodeWilayah,
                    'Jadwal'          => request('jadwal'),
                    'Mekanisme'       => request('mekanisme'),
                    'JumlahOrang'     => onlynumber(request('jumlahorang')),
                    'JumlahHari'      => onlynumber(request('jumlahhari')),
                    'JumlahOrangHari' => $JumlahOrangHari,
                    'UpahHarian'      => onlynumber(request('upahharian')),
                    'TotalBiayaUpah'  => $TotalBiayaUpah,
                    'TotalBiayaLain'  => onlynumber(request('totalbiayalain')),
                    'PersenBiayaUpah' => $PersenBiayaUpah,
                    'TotalPagu'       => onlynumber(request('jumlahRealisasi')),
                    'TotalPaguDipa'   => onlynumber(request('pagu')),
                    'Keterangan'      => request('keterangan') ?? '',
                 ]);

                 $result = array_map(null,request('tanggal'),request('nosp2d'));
                 foreach($result as $data){
                     $data_insert[] = [
                         'Id'         => $guid,
                         'tanggal'    => Carbon::createFromFormat('d/m/Y',$data[0])->format('Y-m-d'),
                         'nosppd'     => $data[1],
                         'created_at' => date('Y-m-d H:i:s'),
                         'updated_at' => date('Y-m-d H:i:s')
                     ];
                 }
                 $post = DataSPDPadatKarya::insert($data_insert);

                break;
            case 'editrealisasipadatkarya':
                // return $request->all();
                $TotalBiayaUpah  = onlynumber(request('jumlahorang'))*onlynumber(request('jumlahhari'))*onlynumber(request('upahharian'));
                // $TotalBiayaLain  = onlynumber(request('totalpagu'))-$TotalBiayaUpah;
                $PersenBiayaUpah = ($TotalBiayaUpah/onlynumber(request('pagu')))*100;
                $JumlahOrangHari = onlynumber(request('jumlahorang'))*onlynumber(request('jumlahhari'));

                // $data      = BelanjaDipaPadatKarya::where('guid_sppd',request('guid'))->first();

                $insert_pagu     = BelanjaDipaPadatKarya::where('guid_sppd',request('guid'))->update([
                    'Jadwal'          => request('jadwal'),
                    'Mekanisme'       => request('mekanisme'),
                    'JumlahOrang'     => onlynumber(request('jumlahorang')),
                    'JumlahHari'      => onlynumber(request('jumlahhari')),
                    'JumlahOrangHari' => $JumlahOrangHari,
                    'UpahHarian'      => onlynumber(request('upahharian')),
                    'TotalBiayaUpah'  => $TotalBiayaUpah,
                    'TotalBiayaLain'  => onlynumber(request('totalbiayalain')),
                    'PersenBiayaUpah' => $PersenBiayaUpah,
                    'TotalPagu'       => onlynumber(request('jumlahRealisasi')),
                    'Keterangan'      => request('keterangan') ?? '',
                 ]);

                 $result = array_map(null,request('idtable'),request('tanggal'),request('nosp2d'));
                //  dd($result);
                 foreach($result as $data){
                    $insert_sppd    = DataSPDPadatKarya::updateorcreate([
                        'idtable' => $data[0],
                        'Id'      => request('guid_sppd'),
                    ],
                    [
                        'Id'      => request('guid_sppd'),
                        'tanggal' => Carbon::createFromFormat('d/m/Y',$data[1])->format('Y-m-d'),
                        'nosppd'  => $data[2],
                    ]);
                 }

                break;

        }

    }

    public function status($status,$id,$what)
    {
        switch ($what) {
            case 'kegiatan':
                $data_pagu    = PaguDipaCovid::where('guid',$id)->delete();
                $data_belanja = BelanjaDipaCovid::where('guid',$id)->delete();
                break;
            case 'realisasi':
                $findID = Crypt::decrypt($id);
                $data_belanja = BelanjaDipaCovid::where('idtable',$findID)->delete();
                break;
            case 'realisasiPK':
                $data_belanja = BelanjaDipaPadatKarya::where('guid_sppd',$id)->delete();
                $data_belanja = DataSPDPadatKarya::where('Id',$id)->delete();
                break;
            case 'kegiatanPK':
                $data_belanja = PaguDipaPadatKarya::where('guid',$id)->delete();
                $data_belanja = PaguAkunPadatKarya::where('guid',$id)->delete();
                $data_belanja = BelanjaDipaPadatKarya::where('guid',$id)->first();
                $data_spd     = DataSPDPadatKarya::where('Id',$data_belanja->guid_sppd)->delete();
                $data_belanja_delete = BelanjaDipaPadatKarya::where('guid',$id)->delete();
                break;
        }
    }

}
