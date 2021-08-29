@extends('layouts.simple.master')
@section('title', 'Rangking Satker')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Belanja Per </h3>
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
                        <div id="gridContainer"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')


<script>
    $(function(){
       const sourcedata = @json($data);
       $("#gridContainer").dxDataGrid({
           dataSource: sourcedata,
           keyExpr: "KodeWilayah",
           showBorders: false,
           selection: {
               mode: "single"
           },
           columns: [{
                   dataField: "KodeWilayah",
                   width: 60,
                   caption: "KODE",
                   alignment: "center"

               },{
                   dataField: "Keterangan",
                   width: 280,
                   caption: "KETERANGAN",
                   alignment: "left"

               },{
                   dataField: "PaguAwal",
                   width: 150,
                   caption: "PAGU AWAL",
                   format: "#,###"
               },{
                   dataField: "Pagu",
                   width: 150,
                   caption: "PAGU AKHIR",
                   format: "#,###"
               },{
                   dataField: "Realisasi",
                   width: 150,
                   caption: "REALISASI",
                   format: "#,###"
               }
           ],
            sortByGroupSummaryInfo: [{
            summaryItem: "sum"
            }],
            searchPanel: {
            visible: true
            },
           summary: {
            totalItems: [
                    {
                      column: "Keterangan",
                      caption:"JUMLAH",
                      displayFormat:"Jumlah",
                      alignment: "left"

                    },
                    {
                   column: "PaguAwal",
                   summaryType: "sum",
                   displayFormat:"{0}",
                   customizeText: function(data) {
                    return data.value.toLocaleString('en') ;
                    }
                   }, {
                   column: "Pagu",
                   summaryType: "sum",
                   displayFormat:"{0}",
                   customizeText: function(data) {
                    return data.value.toLocaleString('en') ;
                    }
                   }, {
                   column: "Realisasi",
                   summaryType: "sum",
                   displayFormat:"{0}",
                   customizeText: function(data) {
                    return data.value.toLocaleString('en') ;
                    }
                   },{
                    name: "%",
                    showInColumn: "%",
                    summaryType: "custom",
                    displayFormat: "{0}%",
                    format: "#.##",
                    alignByColumn: true
                   }
            ],
            // calculateCustomSummary: function(options) {
            //     if (options.name == "%") {
            //         switch (options.summaryProcess) {
            //         case "start":
            //             options.totalValue = 0;
            //             break;
            //         case "calculate":
            //             options.totalValue = ((options.value.Realisasi / options.value.Pagu) * 100).toFixed(2);
            //             // options.totalValue = Math.round(options.totalValue);
            //             break;
            //         }
            //     }
            // },

            // calculateSummary(options: any) {
                calculateCustomSummary: function(options) {
                if (options.name === '%') {
                switch (options.summaryProcess) {
                    case 'start':
                    options.totalValue = [0, 0];
                    break;
                    case 'calculate':
                    options.totalValue = [options.totalValue[0] + (options.value.Realisasi || 0),
                        options.totalValue[1] + (options.value.Pagu || 0)];
                    break;
                    case 'finalize':
                    options.totalValue = ((options.totalValue[0] / options.totalValue[1]) * 100).toFixed(2);
                    break;
                }
                }
            },
           },
       });
       });

       </script>

@endsection


