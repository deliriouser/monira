<?php switch($what):
    case ('create'): ?>
    <form id="myform" action="<?php echo e(route('satker/mppnbp/post/rpd')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" value="<?php echo e($id); ?>" name="bulan">
        <input type="hidden" class="sisa" name="sisa" value="<?php echo e($sisa); ?>">
        <div class="modal-header">
            <h5 class="modal-title">Input Data RPD Bulan <?php echo e(nameofmonth($id)); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
                    <div class="mb-2">
                    <label class="form-label">Jenis Akun</label>
                    <div class="input-group">
                        <select style="font-size:14px" required class="form-control select col-sm-12" name="akun">
                            <option value="">Pilih</option>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->Akun); ?>"><?php echo e($item->keterangan->NamaAkun); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Jumlah Penarikan</label>
                        <div class="input-group">
                                <input required name="jumlah" style="text-align:left; font-size:14px" type="text" class="form-control number angkarpd">
                         </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Prioritas</label>
                        <div class="input-group">
                            <select style="font-size:14px" required class="form-control select col-sm-12" name="prioritas">
                                <option value="">Pilih</option>
                                <option value="0">Rendah</option>
                                <option value="1">Sedang</option>
                                <option value="2">Tinggi</option>
                            </select>
                         </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Rincian Kegiatan</label>
                        <div class="input-group">
                            <textarea style="font-size:14px" required class="form-control" name="keterangan" rows="3"></textarea>
                         </div>
                    </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary submitData" type="submit">Simpan</button>
         </div>
    </form>
    <?php break; ?>

    <?php case ('read'): ?>
    <div class="modal-header">
        <h5 class="modal-title">Daftar RPD Bulan <?php echo e(nameofmonth($id)); ?></h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
     </div>
     <div class="modal-body">

    
        <table class="table table-sm">
            <thead class="bg-primary">
                <tr>
                    <th class="text-center">NO</th>
                    <th class="text-start">AKUN</th>
                    <th class="text-end">JUMLAH</th>
                    <th class="text-center">...</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class=" <?php if($item->prioritas==0): ?> table-success <?php elseif($item->prioritas==1): ?> table-warning <?php else: ?> table-danger <?php endif; ?> ">
                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                    <td class="text-start"><?php echo e($item->akun); ?> - <?php echo e($item->ketAkun->NamaAkun ?? ''); ?><br>
                        <small><?php echo e($item->keterangan); ?></small>
                    </td>
                    <td class="text-end"><?php echo e(RP($item->jumlah)); ?></td>
                    <td>
                        <a title="hapus" onclick="confirmation_disabled(event)" href="<?php echo e(route('satker/mppnbp/rpd',['status'=>'0', 'id'=>Crypt::encrypt($item->id),'what'=>'rpd'])); ?>" data-bs-original-title="" class="text-danger"><i class="icofont icofont-error fa-2x"></i></a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr class="table-info">
                    <th class="text-center"></th>
                    <th class="text-end">TOTAL</th>
                    <th class="text-end"><?php echo e(RP($data->sum('jumlah'))); ?></th>
                    <th class="text-center"></th>
                </tr>
            </tfoot>

        </table>
    </div>
</div>
<div class="modal-footer">
   <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
</div>

    <?php break; ?>
<?php endswitch; ?>


<?php /**PATH /Users/user/dev/app-monira/resources/views/apps/data-modal-rpd.blade.php ENDPATH**/ ?>