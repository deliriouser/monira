<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    if (Auth::check()) {
        switch (Auth::user()->level_id) {
            case 3:
                return redirect('satker/dashboard/belanja');
                break;
            case 2:
            case 4:
            case 5:
                return redirect('admin/dashboard/belanja');
            break;
            default:
                return redirect('/login');
        }
    }
    return view('authentication.login-two');
})->name('/');

Route::get('login', 'login\LoginController@login')->name('login');
Route::post('postlogin', 'login\LoginController@postlogin')->name('postlogin');
Route::get('logout', 'login\LoginController@logout')->name('logout');

Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin'], function(){
    //dashboard
    Route::get('/dashboard', 'Admin\DashboardController@belanja')->name('admin/dashboard');
    Route::get('/dashboard/belanja', 'Admin\DashboardController@belanja')->name('admin/dashboard/belanja');
    Route::get('/dashboard/penerimaan', 'Admin\DashboardController@penerimaan')->name('admin/dashboard/penerimaan');
    Route::get('setyear/{tahun}', 'Admin\DashboardController@setyear')->name('admin/setyear');
    //covid
    Route::get('/dashboard/covid', 'Admin\DashboardController@covid')->name('admin/dashboard/covid');

    //rangking
    Route::get('/rangking/satker', 'Admin\RangkingController@satker')->name('admin/rangking/satker');
    Route::get('/rangking/propinsi', 'Admin\RangkingController@propinsi')->name('admin/rangking/propinsi');
    Route::get('/rangking/pivotsatker', 'Admin\RangkingController@pivotsatker')->name('admin/rangking/pivotsatker');
    Route::get('/rangking/harian/{top}/{bottom}', 'Admin\RangkingController@harian')->name('admin/rangking/harian');
    Route::get('/rangking/filter', 'Admin\RangkingController@filter')->name('admin/rangking/filter');

    //Spending
    Route::get('/belanja/{unit}/{segment}/{month}', 'Admin\SpendingController@index')->name('admin/belanja');

    //covid
    Route::get('/covid/{unit}/{segment}/{month}', 'Admin\CovidController@index')->name('admin/covid');

    //padat karya
    Route::get('/padatkarya/{unit}/{segment}/{month}', 'Admin\PadatKaryaController@index')->name('admin/padatkarya');


    //Prognosa
    Route::get('/prognosa/{unit}/{segment}', 'Admin\PrognosaController@index')->name('admin/prognosa');
    Route::get('/prognosa/locking', 'Admin\PrognosaController@locking')->name('admin/prognosa/locking');
    Route::get('/prognosa/status/{status}/{id}/{what}', 'Admin\PrognosaController@status')->name('admin/prognosa/status');

    //pivot
    Route::get('/profile', 'Admin\ProfileController@profile')->name('admin/profile');

    //profile
    Route::get('/pivot', 'Admin\PivotController@pivot')->name('admin/pivot');

    //profile
    Route::get('/mppnbp', 'Admin\MppnbpController@index')->name('admin/mppnbp');

    //snipper
    Route::get('/snipper/{what}', 'Admin\SnipperController@index')->name('admin/snipper');
    Route::get('/snipper/action/{status}/{id}/{what}', 'Admin\SnipperController@action')->name('admin/snipper/action');
    Route::get('/snipper/openmodal/{what}/{id}', 'Admin\SnipperController@openmodal')->name('admin/snipper/openmodal');
    Route::post('/snipper/post/pejabat', 'Admin\SnipperController@post')->name('admin/snipper/post/pejabat');
    Route::get('/snipper/rekap/pejabat', 'Admin\SnipperController@rekap')->name('admin/snipper/rekap/pejabat');
    Route::get('/snipper/user/{status}/{id}/{what}', 'Admin\SnipperController@status')->name('admin/snipper/user');
    Route::get('/reportSnipper/{type}/{unit}/{segment}', 'Admin\ReportController@snipper')->name('admin/reportSnipper');

    //report
    Route::get('/report/{type}/{unit}/{segment}', 'Admin\ReportController@index')->name('admin/report');
    Route::get('/reportSpending/{type}/{unit}/{segment}/{month}', 'Admin\ReportController@spending')->name('admin/reportSpending');
    Route::get('/reportPrognosa/{type}/{unit}/{segment}', 'Admin\ReportController@prognosa')->name('admin/reportPrognosa');
    Route::get('/reportCovid/{type}/{unit}/{segment}/{month}', 'Admin\ReportController@covid')->name('admin/reportCovid');
    Route::get('/reportPadatKarya/{type}/{unit}/{segment}/{month}', 'Admin\ReportController@padatkarya')->name('admin/reportPadatKarya');
    Route::get('/reportHarian/{type}/{top}/{bottom}', 'Admin\ReportController@harian')->name('admin/reportHarian');

    // Route::get('/pdftes', 'Admin\ReportController@pdftes')->name('admin/pdftes');
    // user management
    Route::get('/utility/{what}', 'Admin\UserController@index')->name('admin/utility');
    Route::get('/resetpassword/{id}/{kdsatker}', 'Admin\UserController@reset')->name('admin/resetpassword');
    Route::post('/post/message', 'Admin\UserController@postmessage')->name('admin/post/message');
    Route::get('/openmessage/{id}', 'Admin\UserController@openmessage')->name('admin/openmessage');

    //upload ADK
    Route::get('/upload/{what}', 'Admin\UploadController@upload')->name('/upload');
    Route::post('save/upload', 'Admin\UploadController@SaveDataUpload')->name('save/upload');

});

Route::post('save/data/profile', 'General\GeneralController@SaveDataProfile')->name('save/data/profile');

Route::group(['prefix' => 'satker', 'middleware' => 'isSatker'], function(){

    Route::get('setyear/{tahun}', 'Satker\DashboardController@setyear')->name('satker/setyear');

    //dashboard
    Route::get('/dashboard', 'Satker\DashboardController@belanja')->name('satker/dashboard');
    Route::get('/dashboard/belanja', 'Satker\DashboardController@belanja')->name('satker/dashboard/belanja');
    Route::get('/dashboard/penerimaan', 'Satker\DashboardController@penerimaan')->name('satker/dashboard/penerimaan');

    Route::get('/dashboard/covid', 'Satker\DashboardController@penerimaan')->name('satker/dashboard/covid');


    //ranking
    Route::get('/rangking/satker', 'Satker\RangkingController@satker')->name('satker/rangking/satker');

    Route::get('/belanja/{segment}/{month}', 'Satker\SpendingController@index')->name('satker/belanja');

    //prognosa
    Route::get('/prognosa', 'Satker\PrognosaController@satker')->name('satker/prognosa');
    Route::get('/prognosa/modal/{action}/{id}', 'Satker\PrognosaController@modal')->name('satker/prognosa/modal');
    Route::post('/post/prognosa', 'Satker\PrognosaController@post')->name('satker/post/prognosa');
    Route::get('/prognosa/status/{status}/{id}/{what}', 'Satker\PrognosaController@status')->name('satker/prognosa/status');

    //report
    Route::get('/report/{type}/{unit}/{segment}', 'Satker\ReportController@index')->name('satker/report');
    Route::get('/reportSpending/{type}/{unit}/{segment}/{month}', 'Satker\ReportController@spending')->name('satker/reportSpending');
    Route::get('/reportPrognosa/{type}/{unit}/{segment}', 'Satker\ReportController@prognosa')->name('satker/reportPrognosa');

    //utility
    Route::get('/utility/{type}', 'Satker\UtilityController@index')->name('satker/utility');
    Route::get('/openmessage/{id}', 'Satker\UtilityController@openmessage')->name('satker/openmessage');

    //snipper
    Route::get('/snipper/daftar/{type}', 'Satker\SnipperController@index')->name('satker/snipper/daftar');
    Route::get('/snipper/user/{status}/{id}/{what}', 'Satker\SnipperController@status')->name('satker/snipper/user');
    Route::get('/snipper/openmodal/{what}/{id}', 'Satker\SnipperController@openmodal')->name('satker/snipper/openmodal');
    Route::get('/snipper/getdata/{nip}/{what}', 'Satker\SnipperController@getdata')->name('satker/snipper/getdata');
    Route::get('/snipper/getcertificate/{nip}/{jabatan}', 'Satker\SnipperController@getcertificate')->name('satker/snipper/getcertificate');
    Route::post('/snipper/post/pejabat', 'Satker\SnipperController@post')->name('satker/snipper/post/pejabat');

    //MP
    Route::get('/mppnbp', 'Satker\MPController@index')->name('satker/mppnbp');
    Route::get('/mppnbp/openmodal/{what}/{id}', 'Satker\MPController@openmodal')->name('satker/mppnbp/openmodal');
    Route::post('/mppnbp/post/rpd', 'Satker\MPController@post')->name('satker/mppnbp/post/rpd');
    Route::get('/mppnbp/rpd/{status}/{id}/{what}', 'Satker\MPController@status')->name('satker/mppnbp/rpd');

    //Monitoring Covdi
    Route::get('/monitoring/{segment}/{month}', 'Satker\MonitoringController@index')->name('satker/monitoring');
    Route::get('/monitoring/modal/{action}/{id}', 'Satker\MonitoringController@modal')->name('satker/monitoring/modal');
    Route::post('/post/data', 'Satker\MonitoringController@post')->name('satker/post/data');
    Route::get('/monitoring/status/{status}/{id}/{what}', 'Satker\MonitoringController@status')->name('satker/monitoring/status');
    Route::get('/reporting/{type}/{unit}/{segment}/{month}', 'Satker\ReportController@reporting')->name('satker/reporting');


    //profile
    Route::get('/profile', 'Satker\ProfileController@profile')->name('satker/profile');
});


Route::get('download/{id}', 'DownloadController@index')->name('download');
Route::get('load/pdf/{what}', 'DownloadController@load')->name('satker/load/pdf');

