<?php switch($action):
    case ('insertPrognosa'): ?>

    <form id="myform" action="<?php echo e(route('satker/post/prognosa')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
            <h5 class="modal-title"><?php echo e($titleHead); ?></h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">


            <div class="list-group mb-3">
                <a class="list-group-item list-group-item-danger kegiatan text-center"></a>
                <a class="list-group-item list-group-item-warning output text-center"></a>
                <a class="list-group-item list-group-item-primary akun text-center"></a>
                <a class="list-group-item list-group-item-success dana text-center"></a>
                <a class="list-group-item list-group-item-grey pagu text-center"></a>
            </div>

            <div class="list-group mb-3 text-center">
            <button class="btn btn-outline-info btn-sm mb-3 autofill" type="button" title="Set Otomatis">Isi Bagi Rata</button>
            <button class="btn btn-outline-success btn-sm mb-3 autofill-realisasi" type="button" title="Set Otomatis">Isi Sesuai Realisasi</button>
            <button class="btn btn-outline-danger btn-sm resetfill" type="button" title="Set Otomatis">Reset</button>
            </div>

            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input value="<?php echo e($item->JAN); ?>" disabled type="hidden" id="JAN">
            <input value="<?php echo e($item->FEB); ?>" disabled type="hidden" id="FEB">
            <input value="<?php echo e($item->MAR); ?>" disabled type="hidden" id="MAR">
            <input value="<?php echo e($item->APR); ?>" disabled type="hidden" id="APR">
            <input value="<?php echo e($item->MEI); ?>" disabled type="hidden" id="MEI">
            <input value="<?php echo e($item->JUN); ?>" disabled type="hidden" id="JUN">
            <input value="<?php echo e($item->JUL); ?>" disabled type="hidden" id="JUL">
            <input value="<?php echo e($item->AGS); ?>" disabled type="hidden" id="AGS">
            <input value="<?php echo e($item->SEP); ?>" disabled type="hidden" id="SEP">
            <input value="<?php echo e($item->OKT); ?>" disabled type="hidden" id="OKT">
            <input value="<?php echo e($item->NOV); ?>" disabled type="hidden" id="NOV">
            <input value="<?php echo e($item->DES); ?>" disabled type="hidden" id="DES">

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <input type="hidden" id="pagu">
            <input type="hidden" id="id" name="id">

            <div class="table-responsive">
                <table class="table">
                  <thead class="bg-primary">
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Bulan</th>
                      <th class="text-center">Prognosa</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php for($i=1;$i<=12;$i++): ?>
                    <tr class="mb-0 pb-0">
                      <td class="text-center" valign="middle"><?php echo e($i); ?></td>
                      <td class="text-start start_<?php echo e($i); ?>" valign="middle"><a href="#" tilte="Prognosa Mingguan"><i class="fa fa-plus-circle week_list_<?php echo e($i); ?> text-primary"></i></a> <?php echo e(nameofmonth($i)); ?> </td>
                      <td>
                          <input name="bulan[]" style="text-align:right; font-size:14px" type="text" class="form-control prognosaValue number input_<?php echo e($i); ?>">
                          <div class="section_week_<?php echo e($i); ?>"></div>
                      </td>
                    </tr>
                    <?php endfor; ?>
                  </tbody>
                  <tfoot>
                    <tr class="table-danger">
                      <th class="text-center"></th>
                      <th class="text-center">Prognosa</th>
                      <th class="text-end"><input disabled id="total" style="text-align:right; font-size:14px" type="text" class="form-control number"></th>
                    </tr>
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-center">Pagu</th>
                        <th class="text-end"><input disabled style="text-align:right; font-size:14px" type="text" class="form-control pagu"></th>
                      </tr>
                      <tr class="table-warning">
                        <th class="text-center"></th>
                        <th class="text-center">Sisa</th>
                        <th class="text-end"><input disabled style="text-align:right; font-size:14px" type="text" class="form-control sisa"></th>
                      </tr>
                  </tfoot>

                </table>
                <div class="newSection"></div>
              </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submitPrognosa" type="submit">Simpan</button>
         </div>
    </form>

    <?php break; ?>

    <?php case ('updatePrognosa'): ?>

    <form id="myform" action="<?php echo e(route('satker/post/prognosa')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
            <h5 class="modal-title">Data Prognosa</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">


            <div class="list-group mb-3">
                <a class="list-group-item list-group-item-danger kegiatan text-center"></a>
                <a class="list-group-item list-group-item-warning output text-center"></a>
                <a class="list-group-item list-group-item-primary akun text-center"></a>
                <a class="list-group-item list-group-item-success dana text-center"></a>
                <a class="list-group-item list-group-item-grey pagu text-center"></a>
            </div>

            <div class="list-group mb-3 text-center">
                <button class="btn btn-outline-info btn-sm mb-3 autofill" type="button" title="Set Otomatis">Bagi Rata</button>
                <button class="btn btn-outline-success btn-sm mb-3 autofill-realisasi" type="button" title="Set Otomatis">Isi Sesuai Realisasi</button>
                <button class="btn btn-outline-danger btn-sm resetfill" type="button" title="Set Otomatis">Reset</button>
                </div>


                <?php $__currentLoopData = $data_dsa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <input value="<?php echo e($item->JAN); ?>" disabled type="hidden" id="JAN">
                <input value="<?php echo e($item->FEB); ?>" disabled type="hidden" id="FEB">
                <input value="<?php echo e($item->MAR); ?>" disabled type="hidden" id="MAR">
                <input value="<?php echo e($item->APR); ?>" disabled type="hidden" id="APR">
                <input value="<?php echo e($item->MEI); ?>" disabled type="hidden" id="MEI">
                <input value="<?php echo e($item->JUN); ?>" disabled type="hidden" id="JUN">
                <input value="<?php echo e($item->JUL); ?>" disabled type="hidden" id="JUL">
                <input value="<?php echo e($item->AGS); ?>" disabled type="hidden" id="AGS">
                <input value="<?php echo e($item->SEP); ?>" disabled type="hidden" id="SEP">
                <input value="<?php echo e($item->OKT); ?>" disabled type="hidden" id="OKT">
                <input value="<?php echo e($item->NOV); ?>" disabled type="hidden" id="NOV">
                <input value="<?php echo e($item->DES); ?>" disabled type="hidden" id="DES">

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <input type="hidden" id="pagu">
            <input type="hidden" id="id" name="id">

            <div class="table-responsive">
                <table class="table">
                  <thead class="bg-primary">
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Bulan</th>
                      <th class="text-center">Prognosa</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $prognosa = 0 ?>
                      <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="mb-0 pb-0">
                      <td class="text-center" valign="middle"><?php echo e($item->Bulan); ?></td>
                      <td class="text-start start_<?php echo e($item->Bulan); ?>" valign="middle"><a href="#" tilte="Prognosa Mingguan"><i class="fa week_list_<?php echo e($item->Bulan); ?> <?php if(count($item->mingguan)>0): ?> fa-minus-circle out_<?php echo e($item->Bulan); ?> <?php else: ?> fa-plus-circle <?php endif; ?> text-primary"></i></a> <?php echo e(nameofmonth($item->Bulan)); ?> </td>
                      <td>
                          <?php
                              $total=0;
                          ?>
                          <?php $__currentLoopData = $item->mingguan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $minggu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <div style='font-size:12px;' required class='input-group mb-1 disabled_<?php echo e($item->Bulan); ?>'><span class='input-group-text' style='font-size:14px;'><i class='fa fa-pencil'></i></span><input name='bulan_<?php echo e($item->Bulan); ?>_minggu[]' style='text-align:right; font-size:14px;' type='text' class='form-control prognosaValue number week_<?php echo e($item->Bulan); ?>_disabled' value="<?php echo e(RP($minggu->Amount)); ?>"></div>
                            <?php $total += $minggu->Amount; ?>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php
                              $prognosa+=$total;
                          ?>
                          <?php if($total>0): ?>
                          <div style='font-size:12px;' required class='input-group mb-1'>
                            <input name='bulan[]' style='text-align:right; font-size:14px;' type='text' disabled class='form-control input_<?php echo e($item->Bulan); ?> prognosaValue enabled_<?php echo e($item->Bulan); ?>' value="<?php echo e(RP($total)); ?>">
                          </div>
                          <?php else: ?>
                          <input name="bulan[]" style="text-align:right; font-size:14px" type="text" class="form-control mt-1 prognosaValue number input_<?php echo e($item->Bulan); ?>" value="<?php echo e(RP($item->Amount)); ?>">
                          <?php endif; ?>
                          <div class="section_week_<?php echo e($item->Bulan); ?>"></div>
                    </td>
                    </tr>
                    
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </tbody>
                  <tfoot>
                    <tr class="table-danger">
                      <th class="text-center"></th>
                      <th class="text-center">Prognosa</th>
                      <th class="text-end"><input disabled id="total" style="text-align:right; font-size:14px" type="text" class="form-control" value="<?php echo e(RP($prognosa+$data->sum('Amount'))); ?>"></th>
                    </tr>
                    <tr class="table-primary">
                        <th class="text-center"></th>
                        <th class="text-center">Pagu</th>
                        <th class="text-end"><input disabled style="text-align:right; font-size:14px" type="text" class="form-control pagu"></th>
                      </tr>
                      <tr class="table-warning">
                        <th class="text-center"></th>
                        <th class="text-center">Sisa</th>
                        <th class="text-end"><input disabled style="text-align:right; font-size:14px" type="text" class="form-control sisa" id="sisa"></th>
                      </tr>
                  </tfoot>

                </table>
                <div class="newSectionx">
                    
                    <div class='form-group mb-3 mt-3'><label class='col-form-label'>Justifikasi Prognosa Tidak 100%</label><textarea required style="font-size:14px;" class='form-control wajib' name='justifikasi' rows='3'><?php echo e($justifikasi->Justifikasi ?? ''); ?></textarea></div>
                    
                </div>
            </div>
         </div>
         <div class="modal-footer">
          <button class="btn btn-secondary text-start" type="button" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-success validasi" type="button">Validasi</button>
            <button class="btn btn-primary submitPrognosa" type="submit">Simpan</button>
         </div>
    </form>
    <?php break; ?>

    <?php case ('locking'): ?>

        <div class="modal-header">
            <h5 class="modal-title">Status Data Prognosa</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">

            <div class="alert alert-secondary" role="alert">
                <p>Data Prognosa Realisasi Sudah Terkunci, Harap Menghubungi Admin</p>
              </div>

         </div>
         <div class="modal-footer">
            <button class="btn btn-secondary float-start" type="button" data-bs-dismiss="modal">Close</button>
         </div>

    <?php break; ?>

<?php endswitch; ?>


<?php /**PATH /Users/user/dev/app-monira/resources/views/apps/data-modal.blade.php ENDPATH**/ ?>