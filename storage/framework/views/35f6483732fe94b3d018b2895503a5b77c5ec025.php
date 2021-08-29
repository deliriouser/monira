<?php $__env->startSection('title', 'Rangking Satker'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatables.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/datatable-extension.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/grid/dx.softblue.css')); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-items'); ?>
<li class="breadcrumb-item">Pivot</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-9">
                            <h5>Pivot Data Pagu, Realisasi, Prognosa</h5>
                        </div>
                        <div class="col-3 text-end">
                            <i data-feather="trending-up"></i>
                        </div>
                    </div>
                </div>
				
                    <div class="table-responsive">
                        <div id="data"></div>
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
<script type="text/javascript" src="<?php echo e(asset('assets/js/grid/js/dx.all.js')); ?>"></script>


<script>


$(function(){
    const sourcedata = <?php echo json_encode($data, 15, 512) ?>;
    $("#data").dxPivotGrid({
        allowSortingBySummary: true,
        allowSorting: true,
        allowFiltering: true,
        allowExpandAll: true,
        showBorders: true,
        fieldChooser: {
            enabled: true,
            applyChangesMode: "instantly",
            allowSearch: true
        },
        fieldPanel: {
            showColumnFields: false,
            showDataFields: false,
            showFilterFields: true,
            showRowFields: true,
            allowFieldDragging: true,
            visible: true
        },
        headerFilter: {
            allowSearch: true,
            showRelevantValues: true,
        },
        export: {
            enabled: true
        },
        dataSource: {
            fields: [{
                caption: "Propinsi",
                dataField: "Propinsi",
                width: 250,
                area: "row"
            }
            ,{
                caption : "Pagu Awal",
                dataField: "PaguAwal",
                dataType: "number",
                summaryType: "sum",
                // format: "currency",
                area: "data",
                format: "#,###",
                width: 40,

            }
            , {
                caption : "Pagu Akhir",
                dataField: "Pagu",
                dataType: "number",
                summaryType: "sum",
                // format: "currency",
                area: "data",
                format: "#,###",
                width: 40,

            }, {
                caption : "Realisasi",
                dataField: "Realisasi",
                dataType: "number",
                summaryType: "sum",
                // format: "currency",
                area: "data",
                format: "#,###",
                width: 40,


            }, {
                caption : "Prognosa",
                dataField: "Prognosa",
                dataType: "number",
                summaryType: "sum",
                // format: "currency",
                area: "data",
                format: "#,###",
                width: 40,


            }],
            store: sourcedata
        }
    });
});



       </script>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.simple.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/user/dev/app-monira/resources/views/apps/data-pivot.blade.php ENDPATH**/ ?>