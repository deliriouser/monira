@extends('layouts.simple.master')

@section('title', 'Default')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/chartist.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3 class="f-w-600" id="greeting"></h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
<li class="breadcrumb-item active">Default</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row second-chart-list third-news-update">


        @foreach ($DashBelanja as $item)
        <div class="col-xl-4 col-lg-12 box-col-12 example-popover" data-bs-trigger="hover" data-container="body" data-bs-toggle="popover" data-bs-placement="top" title="Rincian Data" data-offset="-20px -20px" data-bs-content="PAGU : {{RP($item->Pagu)}}">
			<div class="card">
				<div class="card-body">
					<div class="media align-items-center">
						<div class="media-body right-chart-content">
							<h5>Belanja {{belanja($item->Belanja)}}</h5>
							<small>PAGU</small><bold> {{numbering($item->Pagu)}}</bold>
							<small>REALISASI</small><bold> {{numbering($item->Realisasi)}}</bold>
						</div>
						<div class="knob-block text-center">
							<input readonly disabled class="knob1" data-width="5" data-height="100" data-thickness=".3" data-angleoffset="0" data-linecap="round" data-fgcolor="#{{Color($item->Persen)}}" data-bgcolor="#eef5fb" value="{{Persen($item->Persen)}}">
						</div>
					</div>
				</div>
			</div>
		</div>
        @endforeach

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">

                    <h5 class="card-title mb-0">Realisasi Jenis Belanja</h5>
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                </div>
              <div class="card-block row">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="bg-primary">
                          <tr>
                            <th class="text-center">KODE</th>
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($DashBelanja as $item)
                            <tr class="table-{{ColorTable($item->Persen)}}">
                                <td class="text-center">{{$item->Belanja}}</td>
                                <td class="text-start">{{belanja($item->Belanja)}}</td>
                                <td class="text-end">{{RP($item->PaguAwal)}}</td>
                                <td class="text-end">{{RP($item->Pagu)}}</td>
                                <td class="text-end">{{RP($item->Realisasi)}}</td>
                                <td class="text-end">{{RP($item->Sisa)}}</td>
                                <td class="text-center"><span class="badge badge-{{ColorTable($item->Persen)}}">{{persen($item->Persen)}}</span></td>
                            </tr>
                            @endforeach
                            @php
                                $totalPersen = ($DashBelanja->sum('Realisasi')/$DashBelanja->sum('Pagu'))*100;
                            @endphp
                        </tbody>
                        <thead class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end">{{RP($DashBelanja->sum('PaguAwal'))}}</th>
                              <th class="text-end">{{RP($DashBelanja->sum('Pagu'))}}</th>
                              <th class="text-end">{{RP($DashBelanja->sum('Realisasi'))}}</th>
                              <th class="text-end">{{RP($DashBelanja->sum('Sisa'))}}</th>
                              <th class="text-center">{{Persen($totalPersen)}}</th>
                            </tr>
                          </thead>
                      </table>
                </div>
              </div>
            </div>
          </div>


          <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Realisasi Jenis Kegiatan</h5>
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                </div>
              <div class="card-block row">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="bg-primary">
                          <tr>
                            <th class="text-center">KODE</th>
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($DashKegiatan as $item)
                            <tr class="table-{{ColorTable($item->Persen)}}">
                                <td class="text-center">{{$item->KdKegiatan}}</td>
                                <td class="text-start">{{$item->NamaKegiatan}}</td>
                                <td class="text-end">{{RP($item->PaguAwal)}}</td>
                                <td class="text-end">{{RP($item->Pagu)}}</td>
                                <td class="text-end">{{RP($item->Realisasi)}}</td>
                                <td class="text-end">{{RP($item->Sisa)}}</td>
                                <td class="text-center"><span class="badge badge-{{ColorTable($item->Persen)}}">{{persen($item->Persen)}}</span></td>
                            </tr>
                            @endforeach
                            @php
                                $totalPersen = ($DashKegiatan->sum('Realisasi')/$DashKegiatan->sum('Pagu'))*100;
                            @endphp
                        </tbody>
                        <thead class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end">{{RP($DashKegiatan->sum('PaguAwal'))}}</th>
                              <th class="text-end">{{RP($DashKegiatan->sum('Pagu'))}}</th>
                              <th class="text-end">{{RP($DashKegiatan->sum('Realisasi'))}}</th>
                              <th class="text-end">{{RP($DashKegiatan->sum('Sisa'))}}</th>
                              <th class="text-center">{{Persen($totalPersen)}}</th>
                            </tr>
                          </thead>
                      </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Realisasi Sumber Dana</h5>
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                </div>
              <div class="card-block row">
                    <div class="table-responsive">
                      <table class="table">
                        <thead class="bg-primary">
                          <tr>
                            <th class="text-center">KODE</th>
                            <th>KETERANGAN</th>
                            <th class="text-end">PAGU AWAL</th>
                            <th class="text-end">PAGU AKHIR</th>
                            <th class="text-end">REALISASI</th>
                            <th class="text-end">SISA</th>
                            <th class="text-center">%</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($DashSDana as $item)
                            <tr class="table-{{ColorTable($item->Persen)}}">
                                <td class="text-center">{{$item->KodeSumberDana}}</td>
                                <td class="text-start">{{$item->NamaSumberDana}}</td>
                                <td class="text-end">{{RP($item->PaguAwal)}}</td>
                                <td class="text-end">{{RP($item->Pagu)}}</td>
                                <td class="text-end">{{RP($item->Realisasi)}}</td>
                                <td class="text-end">{{RP($item->Sisa)}}</td>
                                <td class="text-center"><span class="badge badge-{{ColorTable($item->Persen)}}">{{persen($item->Persen)}}</span></td>
                            </tr>
                            @endforeach
                            @php
                                $totalPersen = ($DashSDana->sum('Realisasi')/$DashSDana->sum('Pagu'))*100;
                            @endphp
                        </tbody>
                        <thead class="table-primary">
                            <tr>
                              <th class="text-center"></th>
                              <th>JUMLAH</th>
                              <th class="text-end">{{RP($DashSDana->sum('PaguAwal'))}}</th>
                              <th class="text-end">{{RP($DashSDana->sum('Pagu'))}}</th>
                              <th class="text-end">{{RP($DashSDana->sum('Realisasi'))}}</th>
                              <th class="text-end">{{RP($DashSDana->sum('Sisa'))}}</th>
                              <th class="text-center">{{Persen($totalPersen)}}</th>
                            </tr>
                          </thead>
                      </table>
                </div>
              </div>
            </div>
          </div>


	</div>
</div>
<script type="text/javascript">
	var session_layout = '{{ session()->get('layout') }}';
</script>
@endsection

@section('script')
<script src="{{asset('assets/js/chart/chartist/chartist.js')}}"></script>
<script src="{{asset('assets/js/chart/chartist/chartist-plugin-tooltip.js')}}"></script>
<script src="{{asset('assets/js/chart/knob/knob.min.js')}}"></script>
<script src="{{asset('assets/js/chart/knob/knob-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
<script src="{{asset('assets/js/chart/apex-chart/stock-prices.js')}}"></script>
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/js/dashboard/default.js')}}"></script>
<script src="{{asset('assets/js/notify/index.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.bundle.js')}}"></script>
<script src="{{asset('assets/js/typeahead/typeahead.custom.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/handlebars.js')}}"></script>
<script src="{{asset('assets/js/typeahead-search/typeahead-custom.js')}}"></script>
@endsection
