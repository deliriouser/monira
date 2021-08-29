<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefWilayah;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Support\Facades\Crypt;
use App\Models\SnipperCertificate;
use App\Models\SnipperFileSK;
use App\Models\SnipperHistory;
use App\Models\SnipperPejabat;
use App\Models\SnipperProfile;
use App\Models\SnipperRefJabatan;
use App\Models\SnipperTalent;
use Illuminate\Support\Carbon;
use Storage;
use Image;
use Ramsey\Uuid\Uuid;

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

            View:: share(array(
                'profile' => Auth:: user()->username,
                'setyear' => $year,
                'today'   => DATE ('Y'),
                ));
            return $next($request);
        });

    }

    public function index($what)
    {
        $year = $this->data['setyear'];

        switch ($what) {
            case 'filesk':
                $data = RefWilayah::with('satker')
                ->with(['satker.files' => function($query) use($year) {
                    $query->where('tahun',$year);
                }])
                ->get();
                return view('apps.snipper-daftar-sk',compact('data'));
                // return response()->json($data);
                break;

        }

    }

    public function rekap(Request $request)
    {

        $year = $this->data['setyear'];

        $what = request('what');
        switch ($what) {
            case 'propinsi':
                $data = RefWilayah::where('KodeWilayah',request('id'))->with('satker')
                ->with(['satker.pejabat' => function($query) use($year) {
                    $query->where('TA',$year);
                }])
                ->first();
                // return response()->json($data);
                return view('apps.snipper-daftar-pejabat',compact('data'));
                break;
            }
    }
    public function action($status,$id,$what)
    {
        $findID = Crypt::decrypt($id);
        switch ($what) {
            case 'fileSK':
                $berkas = SnipperFileSK::where('id_berkas',$findID)->first();
                $delete = Storage::delete($berkas->path_berkas);
                $delete_db = SnipperFileSK::where('id_berkas',$findID)->delete();
            break;
            case 'pejabat':
                $data = SnipperPejabat::where('pejabat_id',$findID)->delete();
            break;
            case 'inactive':
                $data = SnipperPejabat::where('pejabat_id',$findID)->update([
                    'status' => $status,
                ]);
            break;


        }
    }

    public function openmodal($what,$id)
    {
        $year = $this->data['setyear'];
        switch ($what) {
            case 'propinsi':
                $data = RefWilayah::get();
                return view('apps.data-modal-snipper',compact('what','data'));
                break;
            case 'edit':
                $id_pejabat = explode(".",$id);
                $id         = $id_pejabat[0];
                $id_riwayat = $id_pejabat[1];
                $KdSatker   = $id_pejabat[3];
                // dd($KdSatker);
                $data = SnipperPejabat::with('sertifikasi')
                ->with(['detiljabatan' => function($query) use($year,$KdSatker) {
                    $query->where('TA',$year);
                    $query->where('kode_satker',$KdSatker);
                }])
                ->where('pejabat_id',$id)->first();
                // dd($data);
                return view('apps.data-modal-snipper',compact('what','data'));
            break;
            case 'editSK':
                $data = SnipperFileSK::where('id_berkas',$id)->first();
                return view('apps.data-modal-snipper',compact('data','what'));
                break;
            case 'read':
                $data = SnipperPejabat::with('sertifikasi','riwayat')->where('pejabat_id',$id)->first();
                return view('apps.data-modal-snipper',compact('data','what'));
            break;

        }
    }


    public function status($status,$id,$what)
    {
        $findID = Crypt::decrypt($id);
        // dd($findID);

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

    public function post(Request $request)
    {
        // return $request->all();
        $year = $this->data['setyear'];
        $status = request('status');
        switch ($status) {


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
                    'kode_satker'        => $id[3],
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
