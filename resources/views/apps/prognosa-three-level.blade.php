@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
{{-- <h3>{{$segment}} Per {{$unit ?? ''}}</h3> --}}
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Prognosa</li>
<li class="breadcrumb-item active">{{$segment}}</li>
<li class="breadcrumb-item">{{ucfirst($unit)}}</li>
@endsection


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				{{-- <div class="card-body"> --}}
                    <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Prognosa {{$segment}} Per {{$unit ?? ''}}</h5>
                        </div>
                        <div class="col-3 text-end">
                            <div class="export">
                                <i data-feather="printer" class="exportbtn text-primary"></i>
                                <div class="export-content">
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/reportPrognosa',['type'=>'excell','unit'=>Request::route('unit'),'segment'=>Request::route('segment')])}}">Excell</a>
                                    <a target="_blank" href="{{route(Auth::user()->ba.'/reportPrognosa',['type'=>'pdf','unit'=>Request::route('unit'),'segment'=>Request::route('segment')])}}">Pdf</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
						<table class="table table-sm" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-end">PROGNOSA</th>
									<th class="text-center">%</th>
									<th class="text-end">SISA</th>
								</tr>
							</thead>
							<tbody>
                                @php
                                    $TotalPaguAwal  = 0;
                                    $TotalPaguAkhir = 0;
                                    $TotalRealisasi = 0;
                                    $TotalPrognosa  = 0;
                                    $PaguAwalSub    = 0;
                                    $PaguAkhirSub   = 0;
                                    $RealisasiSub   = 0;
                                    $PrognosaSub    = 0;
                                    $TotalSisa      = 0;
                                    $SisaSub        = 0;

                                @endphp
                                @foreach ($data as $item)
                                <tr class="table-danger">
									<td class="text-center"><b>{{$item->KodeHeader}}</b></td>
									<td class="text-start" colspan="8"><b>{{$item->NamaHeader}}</b></td>
                                </tr>

                                @foreach ($item->Data as $key => $value)
                                <tr class="table-warning">
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center"><b>{{$key}}</b></td>
                                        <td class="text-start" colspan="7"><b>
                                            @if (isset($value->KodeSubHeader))
                                                {{$value->NamaSubHeader}}
                                            @endif
                                        </b></td>
                                </tr>
                                @php
                                    $PaguAwal  = 0;
                                    $PaguAkhir = 0;
                                    $Realisasi = 0;
                                    $Prognosa  = 0;
                                    $Sisa  = 0;

                                @endphp

                                @foreach ($value->SubData as $detil)
                                @php
                                    // $PaguAwal  += $detil->PaguAwal ?? '0';
                                    $PaguAkhir += $detil->PaguAkhir ?? '0';
                                    $Realisasi += $detil->Realisasi ?? '0';
                                    $Prognosa  += $detil->Prognosa;
                                    $Sisa      += $detil->PaguAkhir-$detil->Prognosa;
                                @endphp

                                <tr>
									<td class="text-center"></td>
									<td class="text-center">{{$detil->Kode}}</td>
									<td class="text-start">{{$detil->Keterangan}}</td>
									<td class="text-end">{{RP($detil->PaguAkhir)}}</td>
									<td class="text-end">{{RP($detil->Realisasi)}}</td>
									<td class="text-center">{{Persen($detil->Persen)}}%</td>
									<td class="text-end">{{RP($detil->Prognosa)}}</td>
									<td class="text-center">{{Persen($detil->PersenPrognosa)}}%</td>
									<td class="text-end">{{RP($detil->PaguAkhir-$detil->Prognosa)}}</td>
								</tr>


                                @endforeach

                                @php
                                    // $TotalPaguAwal  += $PaguAwal;
                                    $TotalPaguAkhir += $PaguAkhir;
                                    $TotalRealisasi += $Realisasi;
                                    $TotalPrognosa  += $Prognosa;
                                    $TotalSisa      += $Sisa;

                                @endphp

                                <tr class="border-top-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH</th>
                                    <th class="text-end">{{RP($PaguAkhir)}}</th>
                                    <th class="text-end">{{RP($Realisasi)}}</th>
                                    <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                                    <th class="text-end">{{RP($Prognosa)}}</th>
                                    <th class="text-center">{{Persen(divnum($Prognosa,$PaguAkhir)*100)}}%</th>
                                    <th class="text-end">{{RP($Sisa)}}</th>
                                </tr>

                                @php
                                // $PaguAwalSub  += $PaguAwal;
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                $PrognosaSub  += $Prognosa;
                                $SisaSub      += $Sisa;
                                @endphp

                                @endforeach

                                <tr class="table-info">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH PROPINSI</th>
                                    <th class="text-end">{{RP($PaguAkhirSub)}}</th>
                                    <th class="text-end">{{RP($RealisasiSub)}}</th>
                                    <th class="text-center">{{Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)}}%</th>
                                    <th class="text-end">{{RP($PrognosaSub)}}</th>
                                    <th class="text-center">{{Persen(divnum($PrognosaSub,$PaguAkhirSub)*100)}}%</th>
                                    <th class="text-end">{{RP($SisaSub)}}</th>
                                </tr>

                                @php
                                $PaguAwalSub  = 0;
                                $PaguAkhirSub = 0;
                                $RealisasiSub = 0;
                                $PrognosaSub  = 0;
                                $SisaSub      = 0;
                                @endphp


                                @endforeach
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">JUMLAH RAYA</th>
									<th class="text-end">{{RP($TotalPaguAkhir)}}</th>
									<th class="text-end">{{RP($TotalRealisasi)}}</th>
									<th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
									<th class="text-end">{{RP($TotalPrognosa)}}</th>
									<th class="text-center">{{Persen(divnum($TotalPrognosa,$TotalPaguAkhir)*100)}}%</th>
									<th class="text-end">{{RP($TotalSisa)}}</th>
								</tr>

						</table>
					{{-- </div> --}}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
