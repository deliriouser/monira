@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Belanja Per {{$segment}}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Belanja</li>
<li class="breadcrumb-item active">Eselon 1</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-body">
                    <div class="table-responsive">
						<table class="table table-sm table">
							<thead class="bg-primary">
								<tr>
									<th class="text-center">KODE</th>
									<th class="text-start">KETERANGAN</th>
									<th class="text-end">PAGU AWAL</th>
									<th class="text-end">PAGU AKHIR</th>
									<th class="text-end">REALISASI</th>
									<th class="text-center">%</th>
								</tr>
							</thead>
							<tbody>
                                @php
                                    $Kode = null;
                                    $KodeSum=null;
                                @endphp

                                @foreach ($data as $key => $item)
                                @if(isset($item->KodeHeader))
                                    @if($item->KodeHeader!=$Kode)
                                    <tr class="table-danger">
                                        <td class="text-start">{{$item->KodeHeader}}</td>
                                        <td class="text-start text-bold" colspan="5">{{strtoupper($item->NamaHeader)}}</td>
                                    </tr>
                                    @endif
                                @endif

								<tr>
									<td class="text-center">{{$item->Kode}}</td>
									<td class="text-start">{{$item->Keterangan}}</td>
									<td class="text-end">{{RP($item->PaguAwal)}}</td>
									<td class="text-end">{{RP($item->Pagu)}}</td>
									<td class="text-end">{{RP($item->Realisasi)}}</td>
									<td class="text-center">{{Persen($item->Persen)}}%</td>
								</tr>

                                {{-- <tr class="table-danger">
                                    <th>{{$item->KodeHeader}} - {{$key}}</th>
									<th class="text-center">JUMLAH </th>
									<th class="text-end">{{RP($data->sum('PaguAwal'))}}</th>
									<th class="text-end">{{RP($data->sum('Pagu'))}}</th>
									<th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
									<th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}</th>
                                </tr> --}}

                                @if(isset($item->KodeHeader))
                                    @php
                                        $Kode=$item->KodeHeader;
                                        $KodeSum=$item->KodeHeader;
                                    @endphp
                                @endif
                                @endforeach
                            </tbody>
							<tfoot class="table-primary">
								<tr>
                                    <th></th>
									<th class="text-center">JUMLAH</th>
									<th class="text-end">{{RP($data->sum('PaguAwal'))}}</th>
									<th class="text-end">{{RP($data->sum('Pagu'))}}</th>
									<th class="text-end">{{RP($data->sum('Realisasi'))}}</th>
									<th class="text-center">{{Persen(divnum($data->sum('Realisasi'),$data->sum('Pagu'))*100)}}%</th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
@endsection
