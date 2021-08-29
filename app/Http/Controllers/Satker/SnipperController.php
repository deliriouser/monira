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
use App\Models\SnipperCertificate;
use App\Models\SnipperFileSK;
use App\Models\SnipperHistory;
use App\Models\SnipperPejabat;
use App\Models\SnipperProfile;
use App\Models\SnipperRefJabatan;
use App\Models\SnipperTalent;
Use Illuminate\Support\Facades\Crypt;
use SoapClient;
use Storage;
use Image;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Carbon;
use App\Models\SnipperRefSk;
use App\Models\BelanjaDipa;
use App\Models\BelanjaDipaCovid;
use App\Models\PaguDipaCovid;


class SnipperController extends Controller
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


    public function index($type)
    {
        $year = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;
        switch ($type) {
            case 'pejabat':
                $data = SnipperPejabat::with(['detiljabatan' => function($query) use($year,$KdSatker) {
                    $query->where('TA',$year);
                    $query->where('kode_satker',$KdSatker);
                }])
                ->where('kode_satker',Auth::user()->kdsatker)->where('TA',$year)->orderby('status','Desc')->orderby('jabatan','Asc')->get();
                // return response()->json($data);
                return view('apps.daftar',compact('data'));
                break;
            case 'berkas':
                $data = SnipperFileSK::where('kode_satker',Auth::user()->kdsatker)->where('tahun',$year)->latest()->get();
                // return response()->json($data);
                return view('apps.daftar-berkas-sk',compact('data'));
                break;
            case 'calon':
                $data = SnipperTalent::where('kode_satker',Auth::user()->kdsatker)->latest()->get();
                $refdata_jabatan = SnipperRefJabatan::where('sertifikasi','1')->get();
                // return response()->json($data);
                return view('apps.daftar-calon-pejabat',compact('data','refdata_jabatan'));
                break;
        }
    }

    public function status($status,$id,$what)
    {
        $findID = Crypt::decrypt($id);
        switch ($what) {
            case 'user':
                $data = SnipperPejabat::where('pejabat_id',$findID)->update([
                    'status' => $status,
                ]);
                break;
            case 'fileSK':
                $berkas = SnipperFileSK::where('id_berkas',$findID)->first();
                $delete = Storage::delete($berkas->path_berkas);
                $delete_db = SnipperFileSK::where('id_berkas',$findID)->delete();
                break;
            case 'calon':
                $delete_db = SnipperTalent::where('pejabat_id',$findID)->delete();
                break;
            case 'sertifikat':
                $delete_db = SnipperCertificate::where('sertifikat_id',$findID)->delete();
                break;
            case 'riwayat':
                $delete_db = SnipperHistory::where('detil_id',$findID)->delete();
                break;
            case 'deleteuser':
                $data = SnipperPejabat::where('pejabat_id',$findID)->delete();
                break;
            case 'aktivasiriwayat':
                $getNIP = SnipperHistory::where('detil_id',$findID)->first();
                $disabled = SnipperHistory::where('nip_pejabat',$getNIP->nip_pejabat)->where('kode_satker',$getNIP->kode_satker)->update([
                    'status_pejabat' => '0',
                ]);
                $active_db = SnipperHistory::where('detil_id',$findID)->update([
                    'status_pejabat' => '1',
                ]);
                break;

        }
    }


    public function openmodal($what,$id)
    {
        $year = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;

        switch ($what) {
            case 'add':
                return view('apps.data-modal-snipper',compact('what'));
                break;
            case 'edit':
                $id_pejabat = explode(".",$id);
                $id = $id_pejabat[0];
                $id_riwayat = $id_pejabat[1];
                $data = SnipperPejabat::with('sertifikasi')
                ->with(['detiljabatan' => function($query) use($year,$KdSatker) {
                    $query->where('TA',$year);
                    $query->where('kode_satker',$KdSatker);
                }])
                ->where('pejabat_id',$id)->first();
                return view('apps.data-modal-snipper',compact('what','data'));
                break;
            case 'read':
                $data = SnipperPejabat::with('sertifikasi','riwayat')->where('pejabat_id',$id)->first();
                return view('apps.data-modal-snipper',compact('data','what'));
                break;
            case 'editSK':
                $data = SnipperFileSK::where('id_berkas',$id)->first();
                // return response()->json($data);
                return view('apps.data-modal-snipper',compact('data','what'));
                break;
        }
    }

    public function getdata($nip,$what)
    {
        switch ($what) {
            case 'InsertModal':
                $check = SnipperProfile::where('nip',$nip)->first();
                if($check) {
                } else {
                    $client         = new SoapClient("https://sik.dephub.go.id/api/index.php/soap_services/sik_api?wsdl",array('login' => "getdatasik",'password'=> "123456"));
                    $params_pegawai = array("nip" => $nip);
                    $response_pegawai = $client->__soapCall("get_pegawai_by_nip", $params_pegawai);
                    $status_pegawai   = $response_pegawai['status']->status;
                    if($status_pegawai==0){
                        $warning = "Data Tidak Ditemukan";
                    } else {
                        $data     = (array)$response_pegawai['return'][0];
                        $img = Image::make($data['foto'])->resize(400, 500);
                        $img->save(storage_path('app/images/avatar/'.$nip.'.jpg'));
                        if($img) {
                            $foto = 'images/avatar/'.$nip.'.jpg';
                        } else {
                            $foto = '';
                        }
                    $insert = SnipperProfile::create([
                    'user_id'                => $data['user_id'],
                    'nip'                    => $data['nip'],
                    'nik'                    => $data['nik'],
                    'tanggal_lahir'          => $data['tanggal_lahir'],
                    'tempat_lahir'           => $data['tempat_lahir'],
                    'nama'                   => $data['nama'],
                    'jenis_kelamin'          => $data['jenis_kelamin'],
                    'karpeg'                 => $data['karpeg'],
                    'unit'                   => $data['unit'],
                    'kode_unit'              => $data['kode_unit'],
                    'kode_unit_lama'         => $data['kode_unit_lama'],
                    'kantor'                 => $data['kantor'],
                    'kode_kantor'            => $data['kode_kantor'],
                    'kode_kantor_lama'       => $data['kode_kantor_lama'],
                    'subkantor'              => $data['subkantor'],
                    'kode_subkantor'         => $data['kode_subkantor'],
                    'tmt_golongan'           => $data['tmt_golongan'],
                    'golongan'               => $data['golongan'],
                    'pangkat'                => $data['pangkat'],
                    'kode_eselon'            => $data['kode_eselon'],
                    'eselon'                 => $data['eselon'],
                    'jabatan_struktural'     => $data['jabatan_struktural'],
                    'tmt_jabatan_struktural' => $data['tmt_jabatan_struktural'],
                    'jabatan_fungsional'     => $data['jabatan_fungsional'],
                    'tmt_jabatan_fungsional' => $data['tmt_jabatan_fungsional'],
                    'email'                  => $data['email'],
                    'telepon'                => $data['telepon'],
                    'tmt_cpns'               => $data['tmt_cpns'],
                    'tmt_pns'                => $data['tmt_pns'],
                    'pendidikan_terakhir'    => $data['pendidikan_terakhir'],
                    'foto'                   => $foto,
                    ]);
                    }
                }
                $data = SnipperProfile::where('nip',$nip)->first();
                if($data) {
                    $status = "readonly";
                    $jabatan = SnipperRefJabatan::get();
                    $what ='getdata';
                    return view('apps.data-modal-snipper',compact('jabatan','data','what','status'));

                } else {
                    $status = "";
                    $jabatan = SnipperRefJabatan::get();
                    $what ='getdatalengkap';
                    return view('apps.data-modal-snipper',compact('jabatan','data','what','status'));

                }
            break;


            case 'InsertTalent':
                $check = SnipperProfile::where('nip',$nip)->first();
                if($check) {
                } else {
                    $client         = new SoapClient("https://sik.dephub.go.id/api/index.php/soap_services/sik_api?wsdl",array('login' => "getdatasik",'password'=> "123456"));
                    $params_pegawai = array("nip" => $nip);
                    $response_pegawai = $client->__soapCall("get_pegawai_by_nip", $params_pegawai);
                    $status_pegawai   = $response_pegawai['status']->status;
                    if($status_pegawai==0){
                        $warning = "Data Tidak Ditemukan";
                    } else {
                        $data     = (array)$response_pegawai['return'][0];
                        $img = Image::make($data['foto'])->resize(400, 500);
                        $img->save(storage_path('app/images/avatar/'.$nip.'.jpg'));
                        if($img) {
                            $foto = 'images/avatar/'.$nip.'.jpg';
                        } else {
                            $foto = '';
                        }
                    $insert = SnipperProfile::create([
                    'user_id'                => $data['user_id'],
                    'nip'                    => $data['nip'],
                    'nik'                    => $data['nik'],
                    'tanggal_lahir'          => $data['tanggal_lahir'],
                    'tempat_lahir'           => $data['tempat_lahir'],
                    'nama'                   => $data['nama'],
                    'jenis_kelamin'          => $data['jenis_kelamin'],
                    'karpeg'                 => $data['karpeg'],
                    'unit'                   => $data['unit'],
                    'kode_unit'              => $data['kode_unit'],
                    'kode_unit_lama'         => $data['kode_unit_lama'],
                    'kantor'                 => $data['kantor'],
                    'kode_kantor'            => $data['kode_kantor'],
                    'kode_kantor_lama'       => $data['kode_kantor_lama'],
                    'subkantor'              => $data['subkantor'],
                    'kode_subkantor'         => $data['kode_subkantor'],
                    'tmt_golongan'           => $data['tmt_golongan'],
                    'golongan'               => $data['golongan'],
                    'pangkat'                => $data['pangkat'],
                    'kode_eselon'            => $data['kode_eselon'],
                    'eselon'                 => $data['eselon'],
                    'jabatan_struktural'     => $data['jabatan_struktural'],
                    'tmt_jabatan_struktural' => $data['tmt_jabatan_struktural'],
                    'jabatan_fungsional'     => $data['jabatan_fungsional'],
                    'tmt_jabatan_fungsional' => $data['tmt_jabatan_fungsional'],
                    'email'                  => $data['email'],
                    'telepon'                => $data['telepon'],
                    'tmt_cpns'               => $data['tmt_cpns'],
                    'tmt_pns'                => $data['tmt_pns'],
                    'pendidikan_terakhir'    => $data['pendidikan_terakhir'],
                    'foto'                   => $foto,
                    ]);
                    }
                }
                $data = SnipperProfile::where('nip',$nip)->first();
                // dd($data);
                $what ='insertname';
                return view('apps.data-modal-snipper',compact('data','what'));
            break;
            }


    }

    public function getcertificate($nip,$jabatan)
    {

        if($jabatan=='2') {
            $jenis = "Barang dan Jasa";
            $data = SnipperProfile::where('nip',$nip)->with('barjas')
            ->first();
        }elseif($jabatan=='4' OR $jabatan=='5') {
            $data = SnipperProfile::where('nip',$nip)
            ->with(['bnt' => function($query) use($jabatan) {
                $query->where('jabatan',$jabatan);
            }])
            ->first();
            $jenis = "Bendahara Negara Tersertifikasi (BNT)";
        } else {
            return false;
        }
        $what = 'certificate';
        // echo $what;
        return view('apps.data-modal-snipper',compact('jabatan','data','jenis','what'));

    }

    public function post(Request $request)
    {
        // return $request->all();
        $year = $this->data['setyear'];
        $KdSatker = Auth:: user()->kdsatker;

        $status = request('status');
        switch ($status) {
            case 'exist':
                $insert_pejabat = SnipperPejabat::updateorcreate([
                    'TA'          => $this->data['setyear'],
                    'nip'         => request('nip'),
                    'kode_satker' => Auth::user()->kdsatker,
                    'jabatan'     => request('jabatan'),
                ],
                [
                    'TA'          => $this->data['setyear'],
                    'nip'         => request('nip'),
                    'kode_satker' => Auth::user()->kdsatker,
                    'jabatan'     => request('jabatan'),
                    'status'      => 1,
                ]);

                $update = SnipperProfile::where('nip',request('nip'))->update([
                    'telepon' => request('telepon')
                ]);

                $insert_history = SnipperHistory::create([
                    'TA'                 => $this->data['setyear'],
                    'kode_satker'        => Auth::user()->kdsatker,
                    'nip_pejabat'        => request('nip'),
                    'jabatan_id'         => request('jabatan'),
                    'notmt_jabatan'      => request('nosk'),
                    'tmt_jabatan'        => Carbon::createFromFormat('d/m/Y',$request->post('tgl_sk'))->format('Y-m-d'),
                    'tmt_awal'           => Carbon::createFromFormat('d/m/Y',$request->post('tmt_awal'))->format('Y-m-d'),
                    'keterangan_pejabat' => request('keterangan') ?? '',
                    'status_pejabat'     => 1
                ]);

                if ($request->has('file')) {
                        $fileName = Uuid::uuid4().'.'.$request->file->extension();
                        $request->file->move(storage_path('app/sertifikat'), $fileName);

                    $insert_certificate = SnipperCertificate::Create([
                            'nip'              => request('nip'),
                            'jenis_sertifikat' => request('jabatan'),
                            'path_file'        => 'sertifikat/'.$fileName ?? '',
                            'nomor_sertifikat' => request('sertifikat'),
                        ]);
                }

                break;
            case 'noexist':

                $insert_pejabat = SnipperPejabat::updateorcreate([
                    'TA'          => $this->data['setyear'],
                    'nip'         => request('nip'),
                    'kode_satker' => Auth::user()->kdsatker,
                    'jabatan'     => request('jabatan'),
                ],
                [
                    'TA'          => $this->data['setyear'],
                    'nip'         => request('nip'),
                    'kode_satker' => Auth::user()->kdsatker,
                    'jabatan'     => request('jabatan'),
                    'status'      => 1,
                ]);

                $panggol = explode('_',request('golongan'));

                $insert_profile = SnipperProfile::create([
                    'nip'      => request('nip'),
                    'nama'     => request('nama'),
                    'golongan' => $panggol[1],
                    'pangkat'  => $panggol[0],
                    'telepon'  => request('telepon'),
                    'pendidikan_terakhir' => request('pendidikan'),

                ]);

                $insert_history = SnipperHistory::create([
                    'TA'          => $this->data['setyear'],
                    'kode_satker'        => Auth::user()->kdsatker,
                    'nip_pejabat'        => request('nip'),
                    'jabatan_id'         => request('jabatan'),
                    'notmt_jabatan'      => request('nosk'),
                    'tmt_jabatan'        => Carbon::createFromFormat('d/m/Y',$request->post('tgl_sk'))->format('Y-m-d'),
                    'tmt_awal'           => Carbon::createFromFormat('d/m/Y',$request->post('tmt_awal'))->format('Y-m-d'),
                    'keterangan_pejabat' => request('keterangan') ?? '',
                    'status_pejabat'     => 1
                ]);


                break;
            case 'fileSK':
                $data = DataProfileSatker::with('wilayah')->where('KodeSatker',Auth::user()->kdsatker)->first();
                $tanggal = Carbon::now()->format('d-m-Y H-i');
                if ($request->has('file')) {
                    $fileName = 'File Surat Keputusan TA '.$request->post('tahun').' '.$data->wilayah->WilayahName.' '.$data->NamaSatuanKerja.' '.$tanggal.' - '.$request->post('jenis').'.'.$request->file->extension();
                    $request->file->move(storage_path('app/filesk'), $fileName);
                    $berkas = SnipperFileSK::create([
                        'kode_satker' => Auth::user()->kdsatker,
                        'nama_berkas' => $fileName,
                        'path_berkas' => 'filesk/'.$fileName,
                        'tahun'       => $request->post('tahun'),
                        'jenis'       => $request->post('jenis'),
                        ]);
                    }
            break;
            case 'calon':
                $insertTalent = SnipperTalent::updateOrCreate(
                    [
                        'nip'           => request('nip'),
                        'kode_satker'   => Auth::user()->kdsatker,
                        'jabatan'       => request('jenis'),
                    ],
                    [
                        'nip'           => request('nip'),
                        'kode_satker'   => Auth::user()->kdsatker,
                        'jabatan'       => request('jenis'),
                        'status'        => '1'
                    ]);

                    if ($request->has('file')) {
                        $fileName = Uuid::uuid4().'.'.$request->file->extension();
                        $request->file->move(storage_path('app/sertifikat'), $fileName);
                    $insert_certificate = SnipperCertificate::updateOrCreate(
                        [
                            'nip'              => request('nip'),
                            'jenis_sertifikat' => request('jenis'),
                        ],
                        [
                            'nip'              => request('nip'),
                            'jenis_sertifikat' => request('jenis'),
                            'path_file'        => 'sertifikat/'.$fileName ?? '',
                            'nomor_sertifikat' => request('sertifikat'),
                        ]);
                }

            break;

            case 'edit':
                // return request()->all();
                $id = explode(".",request('id'));
                $update_profile = SnipperProfile::where('nip',request('nip'))->update([
                    'telepon' => request('telepon')
                ]);

                $update_jabatan = SnipperHistory::updateorcreate([
                    'detil_id' => $id[1],
                ],[
                    'TA'                 => $year,
                    'kode_satker'        => $KdSatker,
                    'nip_pejabat'        => request('nip'),
                    'jabatan_id'         => $id[2],
                    'notmt_jabatan'      => request('nosk'),
                    'tmt_jabatan'        => Carbon::createFromFormat('d/m/Y',$request->post('tgl_sk'))->format('Y-m-d'),
                    'tmt_awal'           => Carbon::createFromFormat('d/m/Y',$request->post('tmt_awal'))->format('Y-m-d'),
                    'keterangan_pejabat' => request('keterangan'),
                    'status_pejabat'     => '1',
                ]);

                if ($request->has('file_barjas')) {
                    $fileName = Uuid::uuid4().'.'.$request->file_barjas->extension();
                    $request->file_barjas->move(storage_path('app/sertifikat'), $fileName);
                $insert_certificate = SnipperCertificate::updateOrCreate(
                    [
                        'nip'              => request('nip'),
                        'jenis_sertifikat' => '2',
                    ],
                    [
                        'nip'              => request('nip'),
                        'jenis_sertifikat' => '2',
                        'path_file'        => 'sertifikat/'.$fileName ?? '',
                        'nomor_sertifikat' => request('sertifikat_barjas'),
                    ]);
                }

                if ($request->has('file_bnt')) {
                    $fileName = Uuid::uuid4().'.'.$request->file_bnt->extension();
                    $request->file_bnt->move(storage_path('app/sertifikat'), $fileName);
                $insert_certificate = SnipperCertificate::updateOrCreate(
                    [
                        'nip'              => request('nip'),
                        'jenis_sertifikat' => '2',
                    ],
                    [
                        'nip'              => request('nip'),
                        'jenis_sertifikat' => '2',
                        'path_file'        => 'sertifikat/'.$fileName ?? '',
                        'nomor_sertifikat' => request('sertifikat_bnt'),
                    ]);
                }

                break;
                case 'editSK':
                    $data = SnipperFileSK::where('id_berkas',request('id'))->update([
                        'tahun'       => $request->post('tahun'),
                        'jenis'       => $request->post('jenis'),
                    ]);
                break;


        }
    }


}
