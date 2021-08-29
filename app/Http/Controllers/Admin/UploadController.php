<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Illuminate\Support\Facades\Auth;
use App\Models\DataProfileUser;
use Maatwebsite\Excel\Facades\Excel;
use App\Import;
use App\Models\PaguDipa;
use App\Models\DataPrognosa;
use App\Models\TempPaguDipa;
use App\Models\BelanjaDipa;
use App\Models\DataKomitmen;
use App\Models\TempSpanDipa;
use App\Models\PendapatanDipa;
use App\Models\DataMP;
use Illuminate\Support\Facades\DB;
use App\Models\DataRevisi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use App\ImportPrognosa;
use App\Models\DataSEMP;
use App\Models\RefNamaBulan;
use Ramsey\Uuid\Uuid;

class UploadController extends Controller
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

    public function upload($what)
    {
        $year      = $this->data['setyear'];

        switch ($what) {
            case 'dipa':
                $revisi = DataRevisi::
                    with(['data_revisi' => function($query) use($year) {
                    $query->where('TA',$year);
                    }])
                    ->where('tahun',$year)
                    ->orderby('revisi','desc')
                    ->get();
                // return response()->json($revisi);
                return view('apps.upload-dipa',compact('what','revisi'));
                break;

            case 'realisasi':
                $data = BelanjaDipa::selectraw('Belanja,sum(amount) as jumlah')->groupby('Belanja')->where('TA',$year)->get();
                return view('apps.upload-realisasi',compact('what','data'));
                break;
            case 'penerimaan':
                $data = PendapatanDipa::selectraw('MONTH(tanggal) as month,sum(amount) as jumlah')->groupby('month')->orderby('month','desc')->where('TA',$year)->get();
                // return response()->json($data);
                return view('apps.upload-penerimaan',compact('what','data'));
                break;
             case 'mp':
                $data = DataMP::selectraw('tahap,sum(Amount) as jumlah')->groupby('tahap')->orderby('tahap','desc')->where('TA',$year)->get();
                        // return response()->json($data);
                return view('apps.upload-mp',compact('what','data'));
                break;
             case 'prognosa':
                $data = DataKomitmen::selectraw('sum(Amount) as jumlah')->groupby('TA')->where('TA',$year)->first();
                        // return response()->json($data);
                return view('apps.upload-komitmen',compact('what','data'));
                break;
             case 'semp':
                $data = DataSEMP::where('TA',$year)->orderby('Bulan','Desc')->get();
                $bulan = RefNamaBulan::get();
                        // return response()->json($data);
                return view('apps.upload-semp',compact('what','data','bulan'));
                break;
        }

    }

    public function SaveDataUpload(Request $request)
    {
        $year = $this->data['setyear'];
        $what = request('what');

        switch ($what) {
            case 'dipa':

                $db_revisi = DataRevisi::where('tahun',$year)->count();
                if(request('revisi')>0) {
                    $revisi = request('revisi');
                    $delete = PaguDipa::where('Revision',$revisi)->where('TA',$year)->delete();
                } elseif(request('revisi')=="0") {
                    $revisi = 0;
                    $delete = PaguDipa::where('Revision',$revisi)->where('TA',$year)->delete();
                    $insert_revisi = DataRevisi::updateOrCreate([
                        'revisi' => $revisi,
                        'tahun' =>$year,
                    ], [
                        'revisi' => $revisi,
                        'tahun' =>$year,
                    ]);
                } else {
                    $revisi = $db_revisi;
                    $insert_revisi = DataRevisi::updateOrCreate([
                        'revisi' => $revisi,
                        'tahun' =>$year,
                    ], [
                        'revisi' => $revisi,
                        'tahun' =>$year,
                    ]);
                }

                $tanggal = Carbon::createFromFormat('d/m/Y', request('tanggal'))->format('Y-m-d');
                $filename = request()->file('file')->getPathName();
                $query="LOAD DATA LOCAL INFILE '$filename' INTO TABLE monira_data_dipa_temp
                FIELDS TERMINATED BY ','
                ENCLOSED BY '\"'
                LINES TERMINATED BY '\n'
                IGNORE 1 LINES"."(kdsatker,ba,baes1,akun,program,kegiatan,output,kewenangan,sumber_dana,cara_tarik,kdregister,lokasi,budget_type,amount) SET tahun='$year'";
                $result = DB::connection()->getpdo()->exec($query);
                $set_inactive = PaguDipa::where('TA',$year)->update([
                    'IsActive' => '0',
                ]);

                $copy = "INSERT INTO monira_data_dipa(TA,Id,Belanja,Ba,BaEs1,KdSatker,Program,Kegiatan,Output,Akun,Kewenangan,SumberDana,CaraTarik,KdRegister,Lokasi,BudgetType,Amount,Revision,created_at) SELECT tahun,CONCAT(kegiatan,'.',output,'.',akun,'.',sumber_dana),substr(akun,1,2),ba,baes1,kdsatker,program,kegiatan,output,akun,kewenangan,sumber_dana,cara_tarik,kdregister,lokasi,budget_type,amount,'$revisi','$tanggal' FROM monira_data_dipa_temp";
                $execute = DB::statement($copy);
                $delete_temp = TempPaguDipa::truncate();

                $DipaNull = DB::table('monira_data_prognosa')
                    ->leftjoin(DB::raw("(
                            SELECT KdSatker,Id, sum(Amount) as Dipa
                                FROM monira_data_dipa
                                    WHERE monira_data_dipa.TA=$year AND monira_data_dipa.IsActive=1
                                        GROUP BY KdSatker,Id) as monira_data_dipa
                                "), function($join){
                                    $join->on('monira_data_dipa.Id', '=', 'monira_data_prognosa.id');
                                    $join->on('monira_data_dipa.KdSatker', '=', 'monira_data_prognosa.KdSatker');
                                })
                    ->where(DB::Raw("Dipa"))
                    ->groupby(DB::Raw("KdSatker,Id"))
                    ->selectRaw("
                                    TA,
                                    monira_data_prognosa.KdSatker,
                                    monira_data_prognosa.id,
                                    sum(monira_data_prognosa.Amount) as Prognosa,
                                    Dipa
                                ")
                ->get();

                foreach ($DipaNull as $item) {
                    $delete = DataPrognosa::where('KdSatker',$item->KdSatker)
                                ->where('id',$item->id)
                                ->where('TA',$item->TA)
                                ->delete();
                }

                break;
            case 'realisasi':
                $delete_old = BelanjaDipa::where('TA',$year)->delete();

                $filename = request()->file('file')->getPathName();
                $query="LOAD DATA LOCAL INFILE '$filename' INTO TABLE monira_data_span_temp
                FIELDS TERMINATED BY ','
                ENCLOSED BY '\"'
                LINES TERMINATED BY '\n'
                IGNORE 1 LINES"."(kdsatker,ba,baes1,akun,program,kegiatan,output,kewenangan,sumber_dana,cara_tarik,kdregister,lokasi,budget_type,tanggal,amount) SET tahun='$year'";
                $result = DB::connection()->getpdo()->exec($query);

                $copy = "INSERT INTO monira_data_belanja(TA,Id,Belanja,Ba,BaEs1,KdSatker,Program,Kegiatan,Output,Akun,Kewenangan,SumberDana,CaraTarik,KdRegister,Lokasi,BudgetType,Tanggal,Amount,Bulan) SELECT tahun,CONCAT(kegiatan,'.',output,'.',akun,'.',sumber_dana),substr(akun,1,2),ba,baes1,kdsatker,program,kegiatan,output,akun,kewenangan,sumber_dana,cara_tarik,kdregister,lokasi,budget_type,DATE_FORMAT(STR_TO_DATE(tanggal, '%d/%m/%Y'), '%Y-%m-%d'),amount,substr(tanggal,4,2) FROM monira_data_span_temp";
                $execute = DB::statement($copy);
                $delete_temp = TempSpanDipa::truncate();

            break;
            case 'penerimaan':
                $delete_old = PendapatanDipa::where('TA',$year)->delete();

                $filename = request()->file('file')->getPathName();
                $query="LOAD DATA LOCAL INFILE '$filename' INTO TABLE monira_data_span_temp
                FIELDS TERMINATED BY ','
                ENCLOSED BY '\"'
                LINES TERMINATED BY '\n'
                IGNORE 1 LINES"."(kdsatker,ba,baes1,akun,program,kegiatan,output,kewenangan,sumber_dana,cara_tarik,kdregister,lokasi,budget_type,tanggal,amount) SET tahun='$year'";
                $result = DB::connection()->getpdo()->exec($query);

                $copy = "INSERT INTO monira_data_pendapatan(TA,Ba,BaEs1,KdSatker,Program,Kegiatan,Output,Akun,Kewenangan,SumberDana,CaraTarik,KdRegister,Lokasi,BudgetType,Tanggal,Amount,Bulan) SELECT tahun,ba,baes1,kdsatker,program,kegiatan,output,akun,kewenangan,sumber_dana,cara_tarik,kdregister,lokasi,budget_type,DATE_FORMAT(STR_TO_DATE(tanggal, '%d/%m/%Y'), '%Y-%m-%d'),amount,substr(tanggal,4,2) FROM monira_data_span_temp";
                $execute = DB::statement($copy);
                $delete_temp = TempSpanDipa::truncate();

            break;

            case 'mp':
                $tahap = request('tahap')+1;
                $filename = request()->file('file')->getPathName();
                $query="LOAD DATA LOCAL INFILE '$filename' INTO TABLE monira_data_mp
                FIELDS TERMINATED BY ','
                ENCLOSED BY '\"'
                LINES TERMINATED BY '\rw'
                IGNORE 1 LINES"."(KdSatker,Amount) SET TA='$year',Tahap='$tahap'";
                $result = DB::connection()->getpdo()->exec($query);
                echo $query;
                // $delete_temp = TempSpanDipa::truncate();
            break;
            case 'prognosa':
                DataKomitmen::where('TA',$year)->delete();
                Excel::import(new ImportPrognosa($year), request()->file('file'));
            break;
            case 'semp':
                $id   = Uuid::uuid4();
                if ($request->has('file')) {
                    $files = $request->file('file');
                    $fileName = Uuid::uuid4().'.'.$request->file->extension();
                    $request->file->move(storage_path('app/upload'), $fileName);
                    $post = DataSEMP::create([
                        'Bulan'     => request('bulan'),
                        'Tahap'     => request('tahap'),
                        'path_file' => 'upload/'.$fileName,
                        'TA'        => $year
                    ]);
                }

            break;

        }

        Cache::flush();


    }

}
