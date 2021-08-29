@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/datatable-extension.css')}}">
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Prognosa Satker</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Prognosa</li>
<li class="breadcrumb-item active">Satker</li>
@endsection


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
                    <div class="table-responsive">
						<table class="table table-sm" id="page-all">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">NO</th>
									<th class="text-start">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU AWAL</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
									<th class="text-center">PROGNOSA</th>
								</tr>
							</thead>
							<tbody>
                                @php
                                    $TotalPaguAwal  = 0;
                                    $TotalPaguAkhir = 0;
                                    $TotalRealisasi = 0;
                                    $PaguAwalSub  = 0;
                                    $PaguAkhirSub = 0;
                                    $RealisasiSub = 0;

                                @endphp
                                @foreach ($data as $item)
                                <tr class="table-danger">
									<td class="text-center"><b>{{$item->KodeHeader}}</b></td>
									<td class="text-start" colspan="8"><b>{{$item->NamaHeader}}</b></td>
                                </tr>

                                @foreach ($item->Data as $key => $value)
                                <tr class="table-warning">
                                        <td class="text-center"{{$loop->iteration}}</td>
                                        <td class="text-center"><b>{{$key}}</b></td>
                                        <td class="text-start" colspan="8"><b>
                                            @if (isset($value->KodeSubHeader))
                                                {{$value->NamaSubHeader}}
                                            @endif
                                        </b></td>
                                </tr>
                                @foreach ($value->SubDataSecond as $keyDetail => $detail)
                                <tr class="table-success">
                                        <td class="text-center"{{$loop->iteration}}</td>
                                        <td class="text-center"><b>{{$keyDetail}}</b></td>
                                        <td class="text-start" colspan="8"><b>
                                            @if (isset($detail->KodeSubHeaderSub))
                                                {{$detail->NamaSubHeaderSub}}
                                            @endif
                                        </b></td>
                                </tr>
                                @php
                                    $PaguAwal     = 0;
                                    $PaguAkhir    = 0;
                                    $Realisasi    = 0;
                                @endphp

                                @foreach ($detail->SubData as $detil_data)
                                @php
                                    $PaguAwal  += $detil_data->PaguAwal ?? '0';
                                    $PaguAkhir += $detil_data->PaguAkhir ?? '0';
                                    $Realisasi += $detil_data->Realisasi ?? '0';
                                @endphp

                                <tr>
									<td class="text-center"></td>
									<td class="text-center">{{$detil_data->Kode}}</td>
									<td class="text-start">{{$detil_data->Keterangan}}</td>
									<td class="text-end">{{RP($detil_data->PaguAwal)}}</td>
									<td class="text-end">{{RP($detil_data->PaguAkhir)}}</td>
									<td class="text-end">{{RP($detil_data->Realisasi)}}</td>
									<td class="text-center">{{Persen($detil_data->Persen)}}%</td>
                                    <td></td>
								</tr>

                                @endforeach

                                @php
                                $TotalPaguAwal  += $PaguAwal;
                                $TotalPaguAkhir += $PaguAkhir;
                                $TotalRealisasi += $Realisasi;
                            @endphp

                            <tr class="border-top-primary">
                                <th class="text-center"></th>
                                <th class="text-start"></th>
                                <th class="text-start">JUMLAH</th>
                                <th class="text-end">{{RP($PaguAwal)}}</th>
                                <th class="text-end">{{RP($PaguAkhir)}}</th>
                                <th class="text-end">{{RP($Realisasi)}}</th>
                                <th class="text-center">{{Persen(divnum($Realisasi,$PaguAkhir)*100)}}%</th>
                                <th></th>
                            </tr>

                                @endforeach



                                @php
                                $PaguAwalSub  += $PaguAwal;
                                $PaguAkhirSub += $PaguAkhir;
                                $RealisasiSub += $Realisasi;
                                @endphp

                                @endforeach

                                <tr class="table-info">
									<th class="text-center"></th>
									<th class="text-start">JUMLAH</th>
                                    <th></th>
                                    <th class="text-end">{{RP($PaguAwalSub)}}</th>
                                    <th class="text-end">{{RP($PaguAkhirSub)}}</th>
                                    <th class="text-end">{{RP($RealisasiSub)}}</th>
                                    <th class="text-center">{{Persen(divnum($RealisasiSub,$PaguAkhirSub)*100)}}%</th>
                                    <th></th>
                                </tr>

                                @php
                                $PaguAwalSub  = 0;
                                $PaguAkhirSub = 0;
                                $RealisasiSub = 0;
                                @endphp


                                @endforeach
								<tr class="table-primary">
									<th class="text-center"></th>
									<th class="text-start"></th>
									<th class="text-start">TOTAL JUMLAH RAYA</th>
									<th class="text-end">{{RP($TotalPaguAwal)}}</th>
									<th class="text-end">{{RP($TotalPaguAkhir)}}</th>
									<th class="text-end">{{RP($TotalRealisasi)}}</th>
									<th class="text-center">{{Persen(divnum($TotalRealisasi,$TotalPaguAkhir)*100)}}%</th>
                                    <th></th>
								</tr>

						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatable-extension/custom.js')}}"></script>
@endsection
