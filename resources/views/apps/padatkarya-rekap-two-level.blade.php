@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Padat Karya</li>
<li class="breadcrumb-item">{{ucfirst($unit ?? '')}}</li>
<li class="breadcrumb-item active">{{$segment}}</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header card-no-border">
                    <div class="header-top">
                      <h5 class="m-0">{{$segment}} Padat Karya Per {{$unit ?? ''}}
                      </h5>
                      <div class="card-header-right-icon">
                        <div class="row">

                            <div class="col-2">
                                <div class="export p-1">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                    <div class="export-content">
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportPadatKarya',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Excell</a>
                                        <a target="_blank" href="{{route(Auth::user()->ba.'/reportPadatKarya',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment'),'month'=>Request::route('month')])}}">Pdf</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
                        <div class="table-responsive">
                            <table class="table table-sm sticky-header" id="card" data-show-columns="true">
                                <thead class="bg-primary">
                                    <tr class="bg-primary">
                                        <th class="text-center" valign="middle" rowspan="2">KODE</th>
                                        <th class="text-center" valign="middle" rowspan="2">PROPINSI <br> KAB / KOTA</br> KEC</br>DESA / KEL</th>
                                        <th class="text-start" valign="middle" rowspan="2">SATKER</th>
                                        <th class="text-center" colspan="5">TARGET</th>
                                        <th class="text-start" valign="middle" rowspan="2"></th>
                                        <th class="text-center" colspan="5">REALISASI</th>
                                    </tr>
                                    <tr>
                                        <th class="col-1 text-center">TOTAL PAGU</th>
                                        <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
                                        <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                                        <th class="col-1 text-center">TOTAL REALISASI</th>
                                        <th class="col-1 text-center">PAGU KEGIATAN PENDUKUNG</th>
                                        <th class="col-1 text-center">TOTAL BIAYA UPAH</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG)</th>
                                        <th class="col-1 text-center">PENYERAPAN TENAGA KERJA (ORANG HARI)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $SumTotTarget_TotalBiayaLain  = 0;
                                    $SumTotTarget_TotalBiayaUpah  = 0;
                                    $SumTotTarget_JumlahOrang     = 0;
                                    $SumTotTarget_JumlahOrangHari = 0;
                                    $SumTotTarget_TotalPagu       = 0;
                                    $SumTotDaser_TotalBiayaLain   = 0;
                                    $SumTotDaser_TotalBiayaUpah   = 0;
                                    $SumTotDaser_JumlahOrang      = 0;
                                    $SumTotDaser_JumlahOrangHari  = 0;
                                    $SumTotDaser_TotalPagu        = 0;
                                    @endphp

                                    @foreach ($data as $wilayah)
                                    <tr class="table-danger">
                                        <th class="text-center">{{$wilayah->KodeWilayah}}</th>
                                        <th class="text-start" colspan="13">PROP. {{$wilayah->NamaWilayah}}</th>
                                    </tr>

                                    @php
                                        $SumPropTarget_TotalBiayaLain  = 0;
                                        $SumPropTarget_TotalBiayaUpah  = 0;
                                        $SumPropTarget_JumlahOrang     = 0;
                                        $SumPropTarget_JumlahOrangHari = 0;
                                        $SumPropTarget_TotalPagu       = 0;
                                        $SumPropDaser_TotalBiayaLain   = 0;
                                        $SumPropDaser_TotalBiayaUpah   = 0;
                                        $SumPropDaser_JumlahOrang      = 0;
                                        $SumPropDaser_JumlahOrangHari  = 0;
                                        $SumPropDaser_TotalPagu        = 0;
                                    @endphp

                                    @foreach ($wilayah->Kabupaten as $kabupaten)
                                    <tr class="table-warning">
                                        <th class="text-center">.</th>
                                        <th class="text-start" colspan="13" style="padding-left:20px;">{{$kabupaten->Kabupaten}}</th>
                                    </tr>
                                    @php
                                        $SumKabTarget_TotalBiayaLain  = 0;
                                        $SumKabTarget_TotalBiayaUpah  = 0;
                                        $SumKabTarget_JumlahOrang     = 0;
                                        $SumKabTarget_JumlahOrangHari = 0;
                                        $SumKabTarget_TotalPagu       = 0;
                                        $SumKabDaser_TotalBiayaLain   = 0;
                                        $SumKabDaser_TotalBiayaUpah   = 0;
                                        $SumKabDaser_JumlahOrang      = 0;
                                        $SumKabDaser_JumlahOrangHari  = 0;
                                        $SumKabDaser_TotalPagu        = 0;
                                    @endphp

                                    @foreach ($kabupaten->Kecamatan as $kecamatan)
                                    <tr class="table-success">
                                        <th class="text-center">..</th>
                                        <th class="text-start" colspan="13" style="padding-left:40px;">KEC. {{$kecamatan->Kecamatan}}</th>
                                    </tr>
                                    @php
                                        $SumKecTarget_TotalBiayaLain  = 0;
                                        $SumKecTarget_TotalBiayaUpah  = 0;
                                        $SumKecTarget_JumlahOrang     = 0;
                                        $SumKecTarget_JumlahOrangHari = 0;
                                        $SumKecTarget_TotalPagu       = 0;
                                        $SumKecDaser_TotalBiayaLain   = 0;
                                        $SumKecDaser_TotalBiayaUpah   = 0;
                                        $SumKecDaser_JumlahOrang      = 0;
                                        $SumKecDaser_JumlahOrangHari  = 0;
                                        $SumKecDaser_TotalPagu        = 0;
                                    @endphp

                                    @foreach ($kecamatan->Desa as $item)
                                    <tr>
                                        <td class="text-center">...</td>
                                        <td class="text-start" style="padding-left:60px;">DES. {{$item->Desa}}</td>
                                        <td class="text-start">{{$item->NamaSatker}}</td>
                                        <td class="text-end">{{RP($item->Target_TotalPagu)}}</td>
                                        <td class="text-end">{{RP($item->Target_TotalBiayaLain)}}</td>
                                        <td class="text-end">{{RP($item->Target_TotalBiayaUpah)}}</td>
                                        <td class="text-center">{{RP($item->Target_JumlahOrang)}}</td>
                                        <td class="text-center">{{RP($item->Target_JumlahOrangHari)}}</td>
                                        <td class="text-end"></td>
                                        <td class="text-end">{{RP($item->Daser_TotalPagu)}}</td>
                                        <td class="text-end">{{RP($item->Daser_TotalBiayaLain)}}</td>
                                        <td class="text-end">{{RP($item->Daser_TotalBiayaUpah)}}</td>
                                        <td class="text-center">{{RP($item->Daser_JumlahOrang)}}</td>
                                        <td class="text-center">{{RP($item->Daser_JumlahOrangHari)}}</td>

                                    </tr>
                                    @php
                                        $SumKecTarget_TotalPagu       += $item->Target_TotalPagu;
                                        $SumKecTarget_TotalBiayaLain  += $item->Target_TotalBiayaLain;
                                        $SumKecTarget_TotalBiayaUpah  += $item->Target_TotalBiayaUpah;
                                        $SumKecTarget_JumlahOrang     += $item->Target_JumlahOrang;
                                        $SumKecTarget_JumlahOrangHari += $item->Target_JumlahOrangHari;
                                        $SumKecDaser_TotalPagu        += $item->Daser_TotalPagu;
                                        $SumKecDaser_TotalBiayaLain   += $item->Daser_TotalBiayaLain;
                                        $SumKecDaser_TotalBiayaUpah   += $item->Daser_TotalBiayaUpah;
                                        $SumKecDaser_JumlahOrang      += $item->Daser_JumlahOrang;
                                        $SumKecDaser_JumlahOrangHari  += $item->Daser_JumlahOrangHari;
                                    @endphp
                                    @endforeach
                                    <tr class="table-success">
                                        <th class="text-center">...</th>
                                        <th colspan="2" class="text-start" style="padding-left:40px;">JUMLAH KEC. {{$kecamatan->Kecamatan}}</th>
                                        <th class="text-end">{{RP($SumKecTarget_TotalPagu)}}</th>
                                        <th class="text-end">{{RP($SumKecTarget_TotalBiayaLain)}}</th>
                                        <th class="text-end">{{RP($SumKecTarget_TotalBiayaUpah)}}</th>
                                        <th class="text-center">{{RP($SumKecTarget_JumlahOrang)}}</th>
                                        <th class="text-center">{{RP($SumKecTarget_JumlahOrangHari)}}</th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{RP($SumKecDaser_TotalPagu)}}</th>
                                        <th class="text-end">{{RP($SumKecDaser_TotalBiayaLain)}}</th>
                                        <th class="text-end">{{RP($SumKecDaser_TotalBiayaUpah)}}</th>
                                        <th class="text-center">{{RP($SumKecDaser_JumlahOrang)}}</th>
                                        <th class="text-center">{{RP($SumKecDaser_JumlahOrangHari)}}</th>
                                    </tr>
                                    @php
                                    $SumKabTarget_TotalPagu       += $SumKecTarget_TotalPagu;
                                    $SumKabTarget_TotalBiayaLain  += $SumKecTarget_TotalBiayaLain;
                                    $SumKabTarget_TotalBiayaUpah  += $SumKecTarget_TotalBiayaUpah;
                                    $SumKabTarget_JumlahOrang     += $SumKecTarget_JumlahOrang;
                                    $SumKabTarget_JumlahOrangHari += $SumKecTarget_JumlahOrangHari;
                                    $SumKabDaser_TotalPagu        += $SumKecDaser_TotalPagu;
                                    $SumKabDaser_TotalBiayaLain   += $SumKecDaser_TotalBiayaLain;
                                    $SumKabDaser_TotalBiayaUpah   += $SumKecDaser_TotalBiayaUpah;
                                    $SumKabDaser_JumlahOrang      += $SumKecDaser_JumlahOrang;
                                    $SumKabDaser_JumlahOrangHari  += $SumKecDaser_JumlahOrangHari;
                                    @endphp

                                    @endforeach
                                    <tr class="table-warning">
                                        <th class="text-center">..</th>
                                        <th colspan="2" class="text-start" style="padding-left:20px;">JUMLAH {{$kabupaten->Kabupaten}}</th>
                                        <th class="text-end">{{RP($SumKabTarget_TotalPagu)}}</th>
                                        <th class="text-end">{{RP($SumKabTarget_TotalBiayaLain)}}</th>
                                        <th class="text-end">{{RP($SumKabTarget_TotalBiayaUpah)}}</th>
                                        <th class="text-center">{{RP($SumKabTarget_JumlahOrang)}}</th>
                                        <th class="text-center">{{RP($SumKabTarget_JumlahOrangHari)}}</th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{RP($SumKabDaser_TotalPagu)}}</th>
                                        <th class="text-end">{{RP($SumKabDaser_TotalBiayaLain)}}</th>
                                        <th class="text-end">{{RP($SumKabDaser_TotalBiayaUpah)}}</th>
                                        <th class="text-center">{{RP($SumKabDaser_JumlahOrang)}}</th>
                                        <th class="text-center">{{RP($SumKabDaser_JumlahOrangHari)}}</th>
                                    </tr>
                                    @php
                                    $SumPropTarget_TotalPagu       += $SumKabTarget_TotalPagu;
                                    $SumPropTarget_TotalBiayaLain  += $SumKabTarget_TotalBiayaLain;
                                    $SumPropTarget_TotalBiayaUpah  += $SumKabTarget_TotalBiayaUpah;
                                    $SumPropTarget_JumlahOrang     += $SumKabTarget_JumlahOrang;
                                    $SumPropTarget_JumlahOrangHari += $SumKabTarget_JumlahOrangHari;
                                    $SumPropDaser_TotalPagu        += $SumKabDaser_TotalPagu;
                                    $SumPropDaser_TotalBiayaLain   += $SumKabDaser_TotalBiayaLain;
                                    $SumPropDaser_TotalBiayaUpah   += $SumKabDaser_TotalBiayaUpah;
                                    $SumPropDaser_JumlahOrang      += $SumKabDaser_JumlahOrang;
                                    $SumPropDaser_JumlahOrangHari  += $SumKabDaser_JumlahOrangHari;
                                    @endphp


                                    @endforeach
                                    <tr class="table-danger">
                                        <th class="text-center"></th>
                                        <th colspan="2" class="text-start" style="padding-left:0px;">TOTAL {{$wilayah->NamaWilayah}}</th>
                                        <th class="text-end">{{RP($SumPropTarget_TotalPagu)}}</th>
                                        <th class="text-end">{{RP($SumPropTarget_TotalBiayaLain)}}</th>
                                        <th class="text-end">{{RP($SumPropTarget_TotalBiayaUpah)}}</th>
                                        <th class="text-center">{{RP($SumPropTarget_JumlahOrang)}}</th>
                                        <th class="text-center">{{RP($SumPropTarget_JumlahOrangHari)}}</th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{RP($SumPropDaser_TotalPagu)}}</th>
                                        <th class="text-end">{{RP($SumPropDaser_TotalBiayaLain)}}</th>
                                        <th class="text-end">{{RP($SumPropDaser_TotalBiayaUpah)}}</th>
                                        <th class="text-center">{{RP($SumPropDaser_JumlahOrang)}}</th>
                                        <th class="text-center">{{RP($SumPropDaser_JumlahOrangHari)}}</th>
                                    </tr>
                                    @php
                                    $SumTotTarget_TotalPagu       += $SumPropTarget_TotalPagu;
                                    $SumTotTarget_TotalBiayaLain  += $SumPropTarget_TotalBiayaLain;
                                    $SumTotTarget_TotalBiayaUpah  += $SumPropTarget_TotalBiayaUpah;
                                    $SumTotTarget_JumlahOrang     += $SumPropTarget_JumlahOrang;
                                    $SumTotTarget_JumlahOrangHari += $SumPropTarget_JumlahOrangHari;
                                    $SumTotDaser_TotalPagu        += $SumPropDaser_TotalPagu;
                                    $SumTotDaser_TotalBiayaLain   += $SumPropDaser_TotalBiayaLain;
                                    $SumTotDaser_TotalBiayaUpah   += $SumPropDaser_TotalBiayaUpah;
                                    $SumTotDaser_JumlahOrang      += $SumPropDaser_JumlahOrang;
                                    $SumTotDaser_JumlahOrangHari  += $SumPropDaser_JumlahOrangHari;
                                    @endphp

                                    @endforeach
                                    <tfoot>
                                    <tr class="table-primary">
                                        <th class="text-center"></th>
                                        <th colspan="2" class="text-start" style="padding-left:0px;">JUMLAH RAYA</th>
                                        <th class="text-end">{{RP($SumTotTarget_TotalPagu)}}</th>
                                        <th class="text-end">{{RP($SumTotTarget_TotalBiayaLain)}}</th>
                                        <th class="text-end">{{RP($SumTotTarget_TotalBiayaUpah)}}</th>
                                        <th class="text-center">{{RP($SumTotTarget_JumlahOrang)}}</th>
                                        <th class="text-center">{{RP($SumTotTarget_JumlahOrangHari)}}</th>
                                        <th class="text-end"></th>
                                        <th class="text-end">{{RP($SumTotDaser_TotalPagu)}}</th>
                                        <th class="text-end">{{RP($SumTotDaser_TotalBiayaLain)}}</th>
                                        <th class="text-end">{{RP($SumTotDaser_TotalBiayaUpah)}}</th>
                                        <th class="text-center">{{RP($SumTotDaser_JumlahOrang)}}</th>
                                        <th class="text-center">{{RP($SumTotDaser_JumlahOrangHari)}}</th>
                                    </tr>
                                    </tfoot>
                                </tbody>
                            </table>
                </div>

			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
