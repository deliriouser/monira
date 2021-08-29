<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>
<h3>Belanja Per </h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Belanja</li>
<li class="breadcrumb-item active">Eselon 1</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.select.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.scroller.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/datatable/datatable-extension/custom.js')); ?>"></script>


<script>
    $(function(){
       const sourcedata = <?php echo json_encode($data, 15, 512) ?>;
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
               },{
                   dataField: "Persen",
                   width: 70,
                   caption: "%",
                   format: "#.##",
                   alignment: "center"
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

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/belanja-eselon1.blade.php ENDPATH**/ ?>