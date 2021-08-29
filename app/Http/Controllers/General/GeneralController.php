<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\DataProfileSatker;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DataProfileUser;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\DataMessage;

class GeneralController extends Controller
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

        Session::set('dark', true);
    }

    public function setyear(Request $request, $tahun)
    {
        //set year
        $request->session()->put('setyear', $tahun);
        return redirect()->route('/');
    }

    public function SaveDataProfile(Request $request)
    {

        // return $request->all();

        //create or update data user
        $profil = DataProfileUser::updateOrCreate(
            ['id' => Auth::user()->id],
            [
                'address' => request('address'),
                'phone'   => request('phone'),
                'email'   => request('email'),
            ]);


        //update name user
        $name = User::where('id',Auth:: user()->id)->update([
            'name' => $request->post('name'),
        ]);

        //update password if not empty
        if(!empty($request->post('password'))) {
            $user = User::where('id',Auth:: user()->id)->update([
                'password' => Hash::make($request->post('password')),
            ]);
        }
    }

    public function openmessage($id)
    {
        // echo $id;
        $data = DataMessage::where('Id',$id)->with('attachment')->first();
        // return response()->json($data);
        return view('apps.open-message',compact('data'));

    }

}
