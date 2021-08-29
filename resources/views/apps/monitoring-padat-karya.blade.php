@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/tour.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>Prognosa Satker</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Monitoring Covid</li>
<li class="breadcrumb-item active">Satker</li>
@endsection


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h5>Monitoring Kegiatan Padat Karya</h5>
                        </div>
                        <div class="col-4 text-end dropup-basic">
                            <a data-intro="Tambah Kegiatan" title="Tambah Data" href="#" class="p-20 text-primary open-modal-monitoring" action="insertPadatKarya" id="0"><i data-feather="plus-circle"></i></a>
                            {{-- <div class="export"> --}}
                                <a target="_blank" href="{{route(Auth::user()->ba.'/reporting',['type'=>'pdf','unit'=>'satker','segment'=>Request::route('segment'), 'month'=>'1'])}}">
                                    <i data-feather="printer" class="exportbtn text-primary"></i>
                                </a>
                            {{-- </div> --}}

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="loadrpd">
                    @foreach ($data as $item)

                    <div class="row">
                        <div class="col-10 mb-3">
                            <h6>KEGIATAN PADAT KARYA</h6>
                            DESA {{strtoupper($item->Desa)}}<br>
                            KECAMATAN {{strtoupper($item->Kecamatan)}}<br>
                            {{$item->Kabupaten}}<br>
                            PROPINSI {{$item->Propinsi}}
                        </div>
                        <div class="col-2 border-1 text-end">
                        <a data-intro="Edit Data" title="Edit Data Kegiatan" class="open-modal-monitoring static text-primary" id="{{$item->guid}}" action="editKegiatanPadatKarya" href="#" output="" pagu=""><i class="icofont icofont-info-circle fa-2x"></i></a>
                        <a data-intro="Hapus Kegiatan" title="Hapus Data Kegiatan" class="static text-danger" onclick="confirmation_disabled(event)" href="{{route('satker/monitoring/status',['status'=>'0', 'id'=> $item->guid,'what'=>'kegiatanPK'])}}"><i class="icofont icofont-close-circled fa-2x"></i></a></div>

                    <ul class="list-group mb-5">
                        <li class="list-group-item bg-primary"><i class="icon-location-pin"></i>LOKASI KEGIATAN PADAT KARYA</li>
                        <li class="list-group-item">
                            @php
                                $lokasi = "Desa ".$item->Desa. ", Kecamatan ".$item->Kecamatan. ", Kabupaten".$item->Kabupaten.", Propinsi".$item->Kecamatan;
                            @endphp
                            <iframe
                            width="100%"
                            height="200"
                            frameborder="0" style="border:0"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDqaagiEpfM3wDfZlX7inQbJpGUQVarOZg
                                &q={{$lokasi}}" allowfullscreen>
                            </iframe>

                        </li>

                        <li class="list-group-item list-group-item-success"><i class="icon-credit-card"></i>TARGET KEGIATAN</li>
                        <li class="list-group-item">
                            @if(!empty($item->realisasi) AND count($item->realisasi)>0)
                            <div class="row">
                                <div class="col-xl-6 col-sm-12 text-bold"><h6 class="mt-3 mb-3">TARGET</h6>
                                    <ul class="mt-2 mb-2">
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan<span class="badge badge-primary">{{$item->Jadwal}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary">{{$item->Mekanisme}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info">{{$item->JumlahOrang}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info">{{$item->JumlahHari}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info">{{$item->JumlahOrangHari}}</span></li>
                                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark">{{RP($item->UpahHarian)}}</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success">{{RP($item->TotalBiayaUpah)}}</span></li>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary">{{Persen($item->PersenBiayaUpah)}} %</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success">{{RP($item->TotalBiayaLain)}}</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan<span class="badge badge-success">{{RP($item->TotalPagu)}}</span></li>

                                    </ul>
                                </div>
                                <div class="col-xl-6 col-sm-12 text-bold"><h6 class="mt-3 mb-3">REALISASI</h6>
                                    <ul class="mt-2 mb-2">
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan<span class="badge badge-primary">{{$item->sumrealisasi->Jadwal}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary">{{$item->sumrealisasi->Mekanisme}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info">{{$item->sumrealisasi->JumlahOrang}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info">{{$item->sumrealisasi->JumlahHari}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info">{{$item->sumrealisasi->JumlahOrangHari}}</span></li>
                                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark">{{RP($item->sumrealisasi->UpahHarian)}}</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success">{{RP($item->sumrealisasi->TotalBiayaUpah)}}</span></li>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary">{{Persen($item->sumrealisasi->PersenBiayaUpah)}} %</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success">{{RP($item->sumrealisasi->TotalBiayaLain)}}</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan<span class="badge badge-success">{{RP($item->sumrealisasi->TotalPagu)}}</span></li>

                                    </ul>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-xl-12 col-sm-12 text-bold"><h6 class="mt-3 mb-3">TARGET</h6>
                                    <ul class="mt-2 mb-2">
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Rencana Pelaksanaan<span class="badge badge-primary">{{$item->Jadwal}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary">{{$item->Mekanisme}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info">{{$item->JumlahOrang}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info">{{$item->JumlahHari}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info">{{$item->JumlahOrangHari}}</span></li>
                                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark">{{RP($item->UpahHarian)}}</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success">{{RP($item->TotalBiayaUpah)}}</span></li>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary">{{Persen($item->PersenBiayaUpah)}} %</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success">{{RP($item->TotalBiayaLain)}}</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Pagu Kegiatan<span class="badge badge-success">{{RP($item->TotalPagu)}}</span></li>

                                    </ul>
                                </div>
                            </div>
                            @endif
                        </li>
                        <li class="list-group-item list-group-item-warning"><i class="icon-credit-card"></i>RINCIAN AKUN KEGIATAN</li>
                        <li class="list-group-item">
                            <div class="row p-10 table-responsive">
                                <table class="table table-bordered">
                                    <tr class="table-primary">
                                        <th class="text-center">#</th>
                                        <th class="text-center">Akun</th>
                                    <th class="text-start">Keterangan Akun / Kegiatan</th>
                                    <th class="text-end">Pagu</th>
                                    <th class="text-end">Realisasi</th>
                                    <th class="text-end">Sisa</th>
                                    </tr>
                                    @php
                                        $total_pagu = 0;
                                        $total_dsa  = 0;
                                        $total_sisa = 0;
                                    @endphp
                                    @foreach ($item->akun as $kegiatan)
                                    @php
                                        $pagu      = $kegiatan->Amount ?? '0';
                                        $realisasi = $kegiatan->realisasi->TotalPagu ?? '0';
                                        $sisa      = $pagu - $realisasi;
                                        $total_pagu += $pagu;
                                        $total_dsa  += $realisasi;
                                        $total_sisa += $sisa;

                                    @endphp
                                    <tr>
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center">{{$kegiatan->Akun}}</td>
                                        <td class="text-start">{{$kegiatan->akun->NamaAkun ?? ''}}<br><small><i>{{$kegiatan->Uraian}}</i></small></td>
                                        <td class="text-end">{{RP($kegiatan->Amount)}}</td>
                                        <td class="text-end">
                                            @if(!empty($kegiatan->realisasi_akun))
                                            {{RP($kegiatan->realisasi_akun->TotalPagu ?? '0')}}
                                            @else
                                            <a akun="{{$kegiatan->Id}}" id="{{$kegiatan->idtable}}.{{$kegiatan->guid}}" data-intro="Input Realisasi" title="Input Realisasi" class="open-modal-monitoring static text-primary" action="realisasiPadatKarya" pagu="{{$kegiatan->Amount}}" sisa="{{$kegiatan->Id}}.{{$kegiatan->Kewenangan}}.{{$kegiatan->Program}}" href="#" style="font-size:20px;"><i class="fa fa-plus-circle"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-end">{{RP($sisa)}}</td>
                                    </tr>
                                    @endforeach
                                    @if(count($item->akun)>1)
                                    <tfoot>
                                        <tr class="table-primary">
                                            <th class="text-center"></th>
                                            <th class="text-center"></th>
                                        <th class="text-start">Jumlah</th>
                                        <th class="text-end">{{RP($total_pagu)}}</th>
                                        <th class="text-end">{{RP($total_dsa)}}</th>
                                        <th class="text-end">{{RP($total_sisa)}}</th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>

                            </div>
                        </li>
                        <li class="list-group-item list-group-item-info"><i class="icon-credit-card"></i>RINCIAN REALISASI KEGIATAN</li>
                        <li class="list-group-item">
                            <div class="row">

                                <div class="col-xl-12 col-sm-12 text-bold">

                                    <ul class="mt-0 mb-2">
                                        <br>
                                        @foreach ($item->realisasi as $itemRealisasi)
                                        <div class="row">
                                            <div class="col-8">
                                                <h6 class="mt-3 mb-3">REALISASI KEGIATAN {{strtoupper($itemRealisasi->akun->Uraian ?? '')}}</h6>
                                            </div>
                                            <div class="col-4 pt-3 text-end">
                                                @if(count($item->realisasi)>0)
                                                <a data-intro="Edit Data" title="Edit Data Realisasi" class="open-modal-monitoring static text-primary" id="{{$itemRealisasi->guid_sppd}}" action="editRPadatKarya" href="#" output="{{$itemRealisasi->TotalPagu}}" pagu="{{$itemRealisasi->TotalPaguDipa}}"><i class="icofont icofont-info-circle fa-2x"></i></a>
                                                <a data-intro="Hapus Data" title="Hapus Data Realisasi" class="static text-danger" onclick="confirmation_disabled(event)" href="{{route('satker/monitoring/status',['status'=>'0', 'id'=> $itemRealisasi->guid_sppd,'what'=>'realisasiPK'])}}"><i class="icofont icofont-close-circled fa-2x"></i></a></a>
                                                @endif
                                            </div>
                                            </div>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Akun : {{$itemRealisasi->akun->akun->KdAkun ?? ''}} - {{$itemRealisasi->akun->akun->NamaAkun ?? ''}} <span class="badge badge-success">{{RP($itemRealisasi->TotalPaguDipa)}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Realisasi Pelaksanaan<span class="badge badge-primary">{{$itemRealisasi->Jadwal}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Mekanisme Pelaksanaan<span class="badge badge-primary">{{$itemRealisasi->Mekanisme}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang<span class="badge badge-info">{{$itemRealisasi->JumlahOrang}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Hari<span class="badge badge-info">{{$itemRealisasi->JumlahHari}}</span></li>
                                        <li class="list-group-item list-group-item-info d-flex justify-content-between align-items-center">Jumlah Orang Hari<span class="badge badge-info">{{$itemRealisasi->JumlahOrangHari}}</span></li>
                                        <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">Upah Harian<span class="badge badge-dark">{{RP($itemRealisasi->UpahHarian)}}</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Upah<span class="badge badge-success">{{RP($itemRealisasi->TotalBiayaUpah)}}</span></li>
                                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">Persen Biaya Upah<span class="badge badge-primary">{{Persen($itemRealisasi->PersenBiayaUpah)}} %</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Biaya Lainnya<span class="badge badge-success">{{RP($itemRealisasi->TotalBiayaLain)}}</span></li>
                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">Total Realisasi Kegiatan<span class="badge badge-success">{{RP($itemRealisasi->TotalPagu)}}</span></li>
                                        <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Sisa Dana <span class="badge badge-danger">{{RP($itemRealisasi->TotalPaguDipa-$itemRealisasi->TotalPagu)}}</span></li>
                                        @foreach ($itemRealisasi->sppd as $sppd)
                                        <li class="list-group-item list-group-item-danger d-flex justify-content-between align-items-center">Data SP2D  Tanggal : {{\Carbon\Carbon::parse($sppd->tanggal ?? '')->isoFormat('DD/MM/YYYY')}} Nomor. {{$sppd->nosppd}}</li>
                                        @endforeach
                                        <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">Catatan : {{($itemRealisasi->Keterangan)}}</li>
                                        <br>

                                        @endforeach
                                        {{-- @endif --}}

                                    </ul>
                                </div>

                        </li>
                      </ul>
                    </div>

                @endforeach
            </div>
                </div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>

<script src="{{asset('assets/js/tour/intro.js')}}"></script>
<script src="{{asset('assets/js/tour/intro-init.js')}}"></script>


<script>
$('body').on('blur','.pagukegiatan,.hari,.upah,.totalbiayalain,.validasi',function(){
    var sum  = 0;
    $('.pagukegiatan').each(function() {
    sum += Number($(this).val().replace(/[^0-9]+/g,""));
    });
    $('.totalpagu').val(sum);

    $('.orang').each(function() {
    orang = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    $('.hari').each(function() {
    hari = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var oranghari = orang*hari;
    $('.modal-content-monitoring #oranghari').val((oranghari));
    $('.upah').each(function() {
    upah = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var biayaupah = oranghari*upah;
    // alert(biayaupah);
    $('.modal-content-monitoring #biayaupah').val(biayaupah);

    $('.totalpagu').each(function() {
    jumlahpagu = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var biayalain = (jumlahpagu-biayaupah);
    // alert((biayalain));
    $('.modal-content-monitoring #biayalain').val(biayalain);

    var persenUpah = (biayaupah/jumlahpagu*100);
    var Persen = persenUpah.toFixed(2);
    // alert(persenUpah);
    $('.modal-content-monitoring .persenupah').html(Persen+'%');

    $('.totalbiayalain').each(function() {
        totalbiayalain = Number($(this).val().replace(/[^0-9]+/g,""));
    });

    var jumlahRealisasi = biayaupah+totalbiayalain;
    // alert(jumlahRealisasi);
    $('.modal-content-monitoring #jumlahRealisasi').val(jumlahRealisasi);

    var sisaRealisasi = jumlahpagu-jumlahRealisasi;
    // alert(sisaRealisasi);
    $('.modal-content-monitoring #sisaRealisasi').val(sisaRealisasi);

});

$('body').on('click','.validasi',function(){
    var sum  = 0;
    $('.pagukegiatan').each(function() {
    sum += Number($(this).val().replace(/[^0-9]+/g,""));
    });
    $('.totalpagu').val(sum);

    $('.orang').each(function() {
    orang = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    $('.hari').each(function() {
    hari = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var oranghari = orang*hari;
    $('.modal-content-monitoring #oranghari').val((oranghari));
    $('.upah').each(function() {
    upah = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var biayaupah = oranghari*upah;
    // alert(biayaupah);
    $('.modal-content-monitoring #biayaupah').val(biayaupah);

    $('.totalpagu').each(function() {
    jumlahpagu = Number($(this).val().replace(/[^0-9]+/g,""));
    });
    var biayalain = (jumlahpagu-biayaupah);
    // alert((biayalain));
    $('.modal-content-monitoring #biayalain').val(biayalain);

    var persenUpah = (biayaupah/jumlahpagu*100);
    var Persen = persenUpah.toFixed(2);
    // alert(persenUpah);
    $('.modal-content-monitoring .persenupah').html(Persen+'%');

    $('.totalbiayalain').each(function() {
        totalbiayalain = Number($(this).val().replace(/[^0-9]+/g,""));
    });

    var jumlahRealisasi = biayaupah+totalbiayalain;
    // alert(jumlahRealisasi);
    $('.modal-content-monitoring #jumlahRealisasi').val(jumlahRealisasi);

    var sisaRealisasi = jumlahpagu-jumlahRealisasi;
    // alert(sisaRealisasi);
    $('.modal-content-monitoring #sisaRealisasi').val(sisaRealisasi);

});

$(document).ready(function(){

    $('body').on('click','.addakun',function(e){
        e.preventDefault();
                $(document).ready(function() {
                        feather.replace();
                    });
        var html  = '';
            html += '<div class="box"><div class="row mt-3">';
            html += '<div class="col-8"><h6 class="">KEGIATAN </h6></div>';
            html += '<div title="Tambah Akun Kegiatan" class="col-4 text-danger text-end"><a class="removeakun" href="#"><i data-feather="minus-circle"></i></a></div>';
            html += '<hr class="mt-2 mb-2">';
            html += '<div class="mb-2 col-xl-7 col-sm-xl">';
            html += '<label class="form-label">Akun Belanja</label>';
            html += '<select style="font-size:14px" required class="form-control select col-sm-12" name="akun[]">';
            html += '<option value="">Pilih Akun Belanja</option>';
            html += '@foreach ($akun as $item)<option value="{{$item->Id}}.{{$item->Kewenangan}}.{{$item->Program}}">{{$item->Id}} : {{$item->keterangan->NamaAkun}}</option>@endforeach';
            html += '</select>';
            html += '</div>';
            html += '<div class="mb-2 col-xl-5 col-sm-12">';
            html += '<label class="form-label">Jumlah Pagu Kegiatan</label>';
            html += '<div class="input-group">';
            html += '<span class="input-group-text"><i data-feather="credit-card"></i></span>';
            html += '<input required style="text-align:right; font-size:14px; padding:9px !important;" type="text" class="form-control number pagukegiatan" name="pagukegiatan[]">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '<div class="mb-2">';
            html += '<label class="form-label">Nama Kegiatan Padat Karya</label>';
            html += '<div class="input-group">';
            html += '<input required style="text-align:left; font-size:14px; padding:9px !important;" type="text" class="form-control" name="kegiatan[]">';
            html += '</div>';
            html += '</div>';
            html += '</div>';

        $(".newData").append(html);
        $('.select').select2({
                    dropdownParent: $('#open-modal-monitoring .box'),
                    tags: true
                });

    });

    $('body').on('click','.removeakun',function(){
    $(this).closest('.box').remove();
    });


    $('body').on('click','.addsp2d',function(e){
        e.preventDefault();
        $(document).ready(function() {
                        feather.replace();
                    });
        var html  = '';
            html += '<div class="boxsp2d"><div class="row">';
            html += '<div class="col-8"></div>';
            html += '<div title="Tambah Akun Kegiatan" class="col-4 text-danger text-end"><a class="removesp2d" href="#"><i data-feather="minus-circle"></i></a></div>';
            html += '<div class="mb-2 col-sm-5">';
            html += '<label class="form-label">Tanggal SP2D</label>';
            html += '<div class="input-group">';
            html += '<span class="input-group-text"><i data-feather="calendar"></i></span>';
            html += '<input required name="tanggal[]" style="font-size:14px; background-color:#fff;" readonly class="datepicker-append form-control" type="text" data-language="en">';
            html += '</div>';
            html += '</div>';
            html += '<div class="mb-2 col-sm-7">';
            html += '<label class="form-label">Nomor SP2D</label>';
            html += '<div class="input-group">';
            html += '<span class="input-group-text"><i data-feather="file-text"></i></span>';
            html += '<input maxlength="25" required style="font-size:14px" type="text" class="form-control number" name="nosp2d[]">';
            html += '</div>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

        $(".newDatasp2d").append(html);

        $(".datepicker-append").datepicker();
        feather.replace();


    });

    $('body').on('click','.removesp2d',function(){
    $(this).closest('.boxsp2d').remove();
    });

});

</script>
@endsection
