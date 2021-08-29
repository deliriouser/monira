<div class="page-header">
  <div class="header-wrapper row m-0">
    <form class="form-inline search-full col" action="#" method="get">
      <div class="mb-3 w-100">
        <div class="Typeahead Typeahead--twitterUsers">
          <div class="u-posRelative">
            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Cuba .." name="q" title="" autofocus>
            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div>
            <i class="close-search" data-feather="x"></i>
          </div>
          <div class="Typeahead-menu"></div>
        </div>
      </div>
    </form>
    <div class="header-logo-wrapper col-auto p-0">
      <div class="logo-wrapper"><a href="<?php echo e(route('/')); ?>"><img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo.png')); ?>" alt=""></a></div>
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
    </div>
    <div class="left-header col horizontal-wrapper ps-0">
        <ul class="nav-menus">
        <li title="Refresh Data" class="maximize"><a class="text-dark" href="<?php echo e(route(Auth::user()->ba.'/setyear',['tahun' => $setyear,'unit' => Auth::user()->ba])); ?>"><i data-feather="cpu"></i></a></li>
        </ul>
    </div>
    <div class="nav-right col-10 pull-right right-header p-0">
      <ul class="nav-menus">
        <li title="Dark Mode">
          <div class="mode"><i class="themes fa fa-moon-o"></i></div>
        </li>

        <li title="Fullscreen Mode" class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>

        <?php if(Auth:: user()->level_id==3): ?>

        <?php if($notifdipacovid>0 OR $notifbelanjacovid>0): ?>
        <li class="onhover-dropdown">
            <div class="notification-box blinker text-primary"><i style="font-size:20px;" class="fa fa-shield"></i>
                <span class="badge rounded-pill badge-secondary">
                    <?php
                        $notifcovid = $notifdipacovid+$notifbelanjacovid;
                    ?>
                    <?php echo e($notifcovid); ?>

                </span>
            </div>
            <ul class="notification-dropdown onhover-show-div">
              <li class="bg-primary"><i data-feather="shield"></i>
                <h6 class="f-18 mb-0">Notifikasi Pandemi Covid-19</h6>
              </li>
              <li>
                <a href="<?php echo e(route(Auth::user()->ba.'/monitoring',['segment'=>'covid','month'=>DATE('n')])); ?>"><p class="text-start text-primary"><i class="fa fa-bell-o me-3 font-primary"></i>Belum Update Volume Kegiatan</p></a>
                <a href="<?php echo e(route(Auth::user()->ba.'/monitoring',['segment'=>'covid','month'=>DATE('n')])); ?>"><p class="text-start text-primary"><i class="fa fa-bell-o me-3 font-primary"></i>Belum Update Realisasi</p></a>
              </li>
            </ul>
        </li>
        <?php endif; ?>


        <?php if(isset($prognosa) AND $prognosa>0): ?>
        <li class="onhover-dropdown loadData">
            <div class="notification-box blinker text-primary"><i style="font-size:20px;" class="fa fa-bell"></i>
                <span class="badge rounded-pill badge-secondary">
                    <?php echo e($prognosa); ?>

                </span>
            </div>
            <ul class="notification-dropdown onhover-show-div">
              <li class="bg-primary"><i data-feather="bell"></i>
                <h6 class="f-18 mb-0">Notifikasi Prognosa</h6>
              </li>
              <li>
                <a href="<?php echo e(route(Auth::user()->ba.'/prognosa')); ?>"><p class="text-start text-primary"><i class="fa fa-bell-o me-3 font-primary"></i><?php echo e($prognosa); ?> Akun Belum Buat Prognosa</p></a>
              </li>
            </ul>
        </li>
        <?php endif; ?>
        <?php if(isset($message) AND $message>0): ?>
        <li class="onhover-dropdown loadDataMessage">
            <div class="notification-box blinker text-primary"><i style="font-size:20px;" class="fa fa-envelope"></i>
                <span class="badge rounded-pill badge-secondary">
                    <?php echo e($message); ?>

                </span>
            </div>
            <ul class="notification-dropdown onhover-show-div">
              <li class="bg-primary"><i data-feather="mail"></i>
                <h6 class="f-18 mb-0">Pesan Masuk</h6>
              </li>
              <?php $__currentLoopData = $dataMessage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="text-start">
                <div class="media"><img class="img-fluid rounded-circle me-3" src="<?php echo e(asset('assets/images/user/avatar.png')); ?>" alt="">
                  <div class="media-body col-12"><span>Admin</span>
                    <small class="f-12 font-success float-end"><?php echo e(\Carbon\Carbon::parse($item->created_at)->diffForHumans()); ?></small>
                    <p>
                        <a class="text-primary" href="<?php echo e(route(Auth::user()->ba.'/utility',['type'=> 'inbox'])); ?>"><?php echo e($item->message->Subject); ?></a>
                    </p>

                </div>
                </div>
              </li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>
        </li>
        <?php endif; ?>
        <?php if(isset($dataSK) AND $dataSK>0): ?>
        <li class="onhover-dropdown loadNotifSK">
            <div class="notification-box blinker text-primary"><i style="font-size:20px;" class="fa fa-file-text"></i>
                <span class="badge rounded-pill badge-secondary">
                    <?php echo e($dataSK); ?>

                </span>
            </div>
            <ul class="notification-dropdown onhover-show-div">
              <li class="bg-primary"><i data-feather="mail"></i>
                <h6 class="f-18 mb-0">Notifikasi Data SK</h6>
              </li>
              <?php $__currentLoopData = $dataMessageSK; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li class="text-start">
                    <p>
                        <a class="text-primary" href="<?php echo e(route(Auth::user()->ba.'/snipper/daftar',['type'=>'berkas'])); ?>"><?php echo e($item->keterangan); ?> Belum Di Upload</a>
                    </p>

              </li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>
        </li>

        <?php endif; ?>
        <?php endif; ?>

        <li class="language-nav">
            <div class="translate_wrapper">
              <div class="current_lang">
                <div class="lang text-lg-center"><i class="fa fa-spin fa-cog"></i> <?php echo e($setyear); ?></div>
              </div>
              <div class="more_lang">
                <?php for($i = $today; $i >=2018; $i--): ?>
                <a href="<?php echo e(route(Auth::user()->ba.'/setyear',['tahun' => $i,'unit' => Auth::user()->ba])); ?>" class="active">
                  <div class="lang"><span class="lang-txt"><?php echo e($i); ?></span></div>
                </a>
                <?php endfor; ?>
              </div>
            </div>
          </li>

        <li class="profile-nav onhover-dropdown p-0 me-0">
          <div class="media profile-media">
            <img class="b-r-10 img-40" src="<?php echo e(asset('assets/images/user/logo.png')); ?>" alt="">
            <div class="media-body">
              <span><?php echo e(strtoupper(Auth::user()->name)); ?></span>
              <p class="mb-0 font-roboto"><?php echo e(strtoupper(level(Auth::user()->level_id))); ?> <i class="middle fa fa-angle-down"></i></p>
            </div>
          </div>
          <ul class="profile-dropdown onhover-show-div">
            <li><a href="<?php echo e(route(Auth::user()->ba.'/profile')); ?>"><i data-feather="user"></i><span>Account </span></a></li>
            
            <li><a href="<?php echo e(route('logout')); ?>"><i data-feather="log-out"> </i><span>Log Out</span></a></li>
          </ul>
        </li>
      </ul>
    </div>
    <script class="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">
      <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
      <div class="ProfileCard-details">
      <div class="ProfileCard-realName">{{name}}</div>
      </div>
      </div>
    </script>
    <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
  </div>
</div>
<?php /**PATH /Users/user/dev/app-monira/resources/views/layouts/simple/header.blade.php ENDPATH**/ ?>