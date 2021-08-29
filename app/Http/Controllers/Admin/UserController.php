<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\DB;
use App\Models\RefWilayah;
use App\Models\DataProfileSatker;
use App\Models\DataMessage;
use App\Models\DataMessageAttachment;
use App\Models\DataMessageSatker;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Carbon;
use Storage;

class UserController extends Controller
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

    public function index($what)
    {
        switch ($what) {
            case 'userlist':
                $dataSQL = Cache::remember('user_list', 1, function () {
                    return DB::select(DB::raw("
                        SELECT
                            Id,
                            IFNULL(KodeSatker,'001') AS KodeSatker,
                            IFNULL(NamaSatuanKerja,'Admin') as NamaSatker,
                            IFNULL(IdWilayah,0) as KodeWilayah,
                            IFNULL(WilayahName,'DJPL') as NamaWilayah,
                            name,
                            email,
                            last_seen
                        FROM monira_users
                        LEFT JOIN
                            monira_ref_satker
                                ON monira_ref_satker.KodeSatker=monira_users.kdsatker
                        LEFT JOIN
                            monira_ref_wilayah
                            ON monira_ref_wilayah.KodeWilayah=monira_ref_satker.KodeWilayah
                        WHERE active=1
                        ORDER BY IdWilayah,KodeSatker,email ASC
                    "));
                    });
                    // return response()->json($dataSQL);

                    $data = NestCollection($dataSQL,'user');
                    return view('apps.table-user',compact('data'));
                break;

            case 'message':
                $data = DataMessage::with('attachment')->get();
                // return response()->json($data);
                return view('apps.message',compact('data'));
                break;
            case 'compose':
                return view('apps.compose',compact('what'));
                break;
            case 'region':
                $data = RefWilayah::all();
                return view('apps.compose',compact('data','what'));
                break;
            case 'unit':
                $data = DataProfileSatker::whereNotNull('KodeWilayah')->get();
                return view('apps.compose',compact('data','what'));
                break;
            case 'filter':
                return view('apps.compose',compact('what'));
            break;

            default:
            break;
        }

    }

    public function postmessage(Request $request)
    {
        // return request()->all();
        $type = request('type');
        $id   = Uuid::uuid4();

        if ($request->has('file')) {
            $files = $request->file('file');
            // dd($files);
            foreach($files as $item) {
                $fileName = Uuid::uuid4().'.'.$item->extension();
                $item->move(storage_path('app/upload'), $fileName);
                $data_files[] = [
                    'IdMessage'  => $id,
                    'FileName'   => 'upload/'.$fileName,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
            $DataPostFiles = DataMessageAttachment::insert($data_files);
        }

        switch ($type) {
            case 'all':
                $data = DataMessage::create([
                    'Id' => $id,
                    'Type'    => request('type'),
                    'Subject' => request('subject'),
                    'Message' => request('message')
                ]);
                $data = DataProfileSatker::whereNotNull('KodeWilayah')->get();
                foreach($data as $item){
                    $data_insert[] = [
                        'KdSatker'   => $item->KodeSatker,
                        'IdMessage'  => $id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                $DataPostSatker = DataMessageSatker::insert($data_insert);
                break;
            case 'region':
                $counter = count(request('wilayah'));
                $KodeWilayah = array();
                while (--$counter) {
                    $KodeWilayah[] = request('wilayah');
                }
                $data = DataMessage::create([
                    'Id' => $id,
                    'Type'    => request('type'),
                    'Subject' => request('subject'),
                    'Message' => request('message')
                ]);
                if($counter>1) {
                    $data = DataProfileSatker::whereIn('KodeWilayah',$KodeWilayah)->get();
                } else {
                    $data = DataProfileSatker::where('KodeWilayah',request('wilayah'))->get();
                }
                foreach($data as $item){
                    $data_insert[] = [
                        'KdSatker'   => $item->KodeSatker,
                        'IdMessage'  => $id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                $DataPostSatker = DataMessageSatker::insert($data_insert);
                break;

            case 'unit':
                $data = DataMessage::create([
                    'Id' => $id,
                    'Type'    => request('type'),
                    'Subject' => request('subject'),
                    'Message' => request('message')
                ]);

                $data = request('satker');
                foreach($data as $item){
                    $data_insert[] = [
                        'KdSatker'   => $item,
                        'IdMessage'  => $id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }
                $DataPostSatker = DataMessageSatker::insert($data_insert);
                break;
        }


    }

    public function openmessage($id)
    {
        // echo $id;
        $data = DataMessage::where('Id',$id)->with('attachment')->first();
        // return response()->json($data);
        return view('apps.open-message',compact('data'));

    }

    public function reset($id,$kdsatker)
    {
        if($kdsatker==0) {
            $kdsatker='123456';
        }
        $data = User::where('id',$id)->update([
            'password' => Hash::make($kdsatker),
        ]);
    }
}
