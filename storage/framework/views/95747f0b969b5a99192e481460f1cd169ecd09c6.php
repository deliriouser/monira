<div class="sidebar-wrapper">
	<div>
		<div class="logo-wrapper">
			<a href="<?php echo e(route('/')); ?>"><img class="img-fluid for-light" src="<?php echo e(asset('assets/images/logo/logo.png')); ?>" alt=""><img class="img-fluid for-dark" src="<?php echo e(asset('assets/images/logo/logo_dark.png')); ?>" alt=""></a>
			<div class="back-btn"><i class="fa fa-angle-left"></i></div>
			<div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
		</div>
		<div class="logo-icon-wrapper"><a href="<?php echo e(route('/')); ?>"><img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt=""></a></div>
		<nav class="sidebar-main">
			<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
			<div id="sidebar-menu">
				<ul class="sidebar-links" id="simple-bar">
					<li class="back-btn">
						<a href="<?php echo e(route('/')); ?>"><img class="img-fluid" src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt=""></a>
						<div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
					</li>
					<li class="sidebar-main-title">
						<div>
							<h6 class="lan-1"><?php echo e(trans('lang.General')); ?> </h6>
                     		<p class="lan-2"><?php echo e(trans('Dashboards,Menu & layout.')); ?></p>
						</div>
					</li>
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(), 'dashboard') ? 'active' : ''); ?>" href="#"><i data-feather="home"></i><span class="lan-3"><?php echo e(trans('lang.Dashboards')); ?></span>
							<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'dashboard') ? 'right' : 'down'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), 'dashboard') ? 'block' : 'none'); ?>">
							<li><a class="lan-4 <?php echo e(in_array(Route::currentRouteName(), ['admin/dashboard', 'admin/dashboard/belanja','satker/dashboard', 'satker/dashboard/belanja']) ? 'active' : 'none'); ?>" href="<?php echo e(route(Auth::user()->ba.'/dashboard/belanja')); ?>">Belanja</a></li>
                            <?php if(Auth:: user()->level_id!=3): ?>
							<li><a class="lan-4 <?php echo e(in_array(Route::currentRouteName(), ['admin/dashboard', 'admin/dashboard/covid','satker/dashboard', 'satker/dashboard/covid']) ? 'active' : 'none'); ?>" href="<?php echo e(route(Auth::user()->ba.'/dashboard/covid')); ?>">COVID-19</a></li>
                     		<?php endif; ?>
                            <li><a class="lan-5 <?php echo e(str_contains(Route::currentRouteName(), 'dashboard/penerimaan') ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/dashboard/penerimaan')); ?>">PNBP</a></li>
						</ul>
					</li>

                    <?php if(Auth:: user()->level_id!=3): ?>

					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(), 'rangking') ? 'active' : ''); ?>" href="#"><i data-feather="bar-chart"></i><span class="lan-3">Rangking</span>
							<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'rangking') ? 'right' : 'down'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), 'rangking') ? 'block' : 'none'); ?>">
							<li><a class="lan-4 <?php echo e(str_contains(Route::currentRouteName(), 'rangking/satker') ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/rangking/satker')); ?>">Satker</a></li>
                     		<li><a class="lan-5 <?php echo e(str_contains(Route::currentRouteName(), 'rangking/propinsi') ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/rangking/propinsi')); ?>">Propinsi</a></li>
                     		<li><a class="lan-5 <?php echo e(str_contains(Route::currentRouteName(), 'rangking/pivotsatker') ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/rangking/pivotsatker')); ?>">Propinsi Satker</a></li>
                             <li><a class="lan-4 <?php echo e(str_contains(Route::currentRouteName(), 'rangking/harian') ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/rangking/harian',['top'=>'60.00','bottom'=>'43.38'])); ?>">Laporan Harian</a></li>
                            </ul>
					</li>

					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(),Auth::user()->ba.'/belanja') ? 'active' : ''); ?>" href="#">
							<i data-feather="shopping-bag"></i><span>Belanja </span>
							<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), Auth::user()->ba.'/belanja') ? 'down' : 'right'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), Auth::user()->ba.'/belanja') ? 'block;' : 'none;'); ?>">
							<li>
								<a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'belanja/eselon1') ? 'active' : ''); ?>" href="#">Eselon 1
									<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'belanja/eselon1') ? 'down' : 'right'); ?>"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'belanja/eselon1') ? 'block' : 'none'); ?>;">
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'eselon1','segment'=>'belanja', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/eselon1/belanja') ? 'active' : ''); ?>">Belanja</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'eselon1','segment'=>'sumberdana', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/eselon1/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'eselon1','segment'=>'kegiatan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/eselon1/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'eselon1','segment'=>'output', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/eselon1/output') ? 'active' : ''); ?>">Output</a></li>
									
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'eselon1','segment'=>'kewenangan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/eselon1/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'eselon1','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/eselon1/akun') ? 'active' : ''); ?>">Akun</a></li>
								</ul>
							</li>

                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'belanja/propinsi') ? 'active' : ''); ?>" href="#">Propinsi
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'belanja/propinsi') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'belanja/propinsi') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'propinsi','segment'=>'belanja', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/propinsi/belanja') ? 'active' : ''); ?>">Belanja</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'propinsi','segment'=>'sumberdana', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/propinsi/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'propinsi','segment'=>'kegiatan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/propinsi/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'propinsi','segment'=>'output', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/propinsi/output') ? 'active' : ''); ?>">Output</a></li>
                                    
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'propinsi','segment'=>'kewenangan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/propinsi/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'propinsi','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/propinsi/akun') ? 'active' : ''); ?>">Akun</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'belanja/satker') ? 'active' : ''); ?>" href="#">Satker
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'belanja/satker') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'belanja/satker') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'satker','segment'=>'belanja', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/satker/belanja') ? 'active' : ''); ?>">Belanja</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'satker','segment'=>'sumberdana', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/satker/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'satker','segment'=>'kegiatan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/satker/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'satker','segment'=>'output', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/satker/output') ? 'active' : ''); ?>">Output</a></li>
                                    
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'satker','segment'=>'kewenangan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/satker/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['unit'=>'satker','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'belanja/satker/akun') ? 'active' : ''); ?>">Akun</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>


                    <li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(),Auth::user()->ba.'/padatkarya') ? 'active' : ''); ?>" href="#">
							<i data-feather="globe"></i><span>Padat Karya</span>
							<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), Auth::user()->ba.'/padatkarya') ? 'down' : 'right'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), Auth::user()->ba.'/padatkarya') ? 'block;' : 'none;'); ?>">
							<li>
								<a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'padatkarya/eselon1') ? 'active' : ''); ?>" href="#">Eselon 1
									<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'padatkarya/eselon1') ? 'down' : 'right'); ?>"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'padatkarya/eselon1') ? 'block' : 'none'); ?>;">
									<li><a href="<?php echo e(route(Auth::user()->ba.'/padatkarya',['unit'=>'eselon1','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'padatkarya/eselon1/akun') ? 'active' : ''); ?>">Akun</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/padatkarya',['unit'=>'eselon1','segment'=>'rekap', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'padatkarya/eselon1/rekap') ? 'active' : ''); ?>">Rekap</a></li>
									
								</ul>
							</li>

                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'padatkarya/propinsi') ? 'active' : ''); ?>" href="#">Propinsi
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'padatkarya/propinsi') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'padatkarya/propinsi') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/padatkarya',['unit'=>'propinsi','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'padatkarya/propinsi/akun') ? 'active' : ''); ?>">Akun</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/padatkarya',['unit'=>'propinsi','segment'=>'rekap', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'padatkarya/propinsi/rekap') ? 'active' : ''); ?>">Rekap</a></li>
                                    
                                </ul>
                            </li>
                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'padatkarya/satker') ? 'active' : ''); ?>" href="#">Satker
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'padatkarya/satker') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'padatkarya/satker') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/padatkarya',['unit'=>'satker','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'padatkarya/satker/akun') ? 'active' : ''); ?>">Akun</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/padatkarya',['unit'=>'satker','segment'=>'rekap', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'padatkarya/satker/rekap') ? 'active' : ''); ?>">Rekap</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/padatkarya',['unit'=>'satker','segment'=>'rincian', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'padatkarya/satker/rincian') ? 'active' : ''); ?>">Rincian</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(),Auth::user()->ba.'/covid') ? 'active' : ''); ?>" href="#">
							<i data-feather="shield"></i><span>Covid-19</span>
							<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), Auth::user()->ba.'/covid') ? 'down' : 'right'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), Auth::user()->ba.'/covid') ? 'block;' : 'none;'); ?>">
							<li>
								<a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'covid/eselon1') ? 'active' : ''); ?>" href="#">Eselon 1
									<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'covid/eselon1') ? 'down' : 'right'); ?>"></i></div>
								</a>
								<ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'covid/eselon1') ? 'block' : 'none'); ?>;">
									<li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'eselon1','segment'=>'belanja', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/eselon1/belanja') ? 'active' : ''); ?>">Belanja</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'eselon1','segment'=>'sumberdana', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/eselon1/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'eselon1','segment'=>'kegiatan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/eselon1/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'eselon1','segment'=>'output', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/eselon1/output') ? 'active' : ''); ?>">Output</a></li>
									
									<li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'eselon1','segment'=>'kewenangan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/eselon1/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'eselon1','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/eselon1/akun') ? 'active' : ''); ?>">Akun</a></li>
								</ul>
							</li>

                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'covid/propinsi') ? 'active' : ''); ?>" href="#">Propinsi
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'covid/propinsi') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'covid/propinsi') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'propinsi','segment'=>'belanja', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/propinsi/belanja') ? 'active' : ''); ?>">Belanja</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'propinsi','segment'=>'sumberdana', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/propinsi/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'propinsi','segment'=>'kegiatan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/propinsi/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'propinsi','segment'=>'output', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/propinsi/output') ? 'active' : ''); ?>">Output</a></li>
                                    
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'propinsi','segment'=>'kewenangan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/propinsi/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'propinsi','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/propinsi/akun') ? 'active' : ''); ?>">Akun</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'covid/satker') ? 'active' : ''); ?>" href="#">Satker
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'covid/satker') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'covid/satker') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'satker','segment'=>'belanja', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/satker/belanja') ? 'active' : ''); ?>">Belanja</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'satker','segment'=>'sumberdana', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/satker/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'satker','segment'=>'kegiatan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/satker/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'satker','segment'=>'output', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/satker/output') ? 'active' : ''); ?>">Output</a></li>
                                    
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'satker','segment'=>'kewenangan', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/satker/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'satker','segment'=>'akun', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/satker/akun') ? 'active' : ''); ?>">Akun</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/covid',['unit'=>'satker','segment'=>'volume', 'month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'covid/satker/volume') ? 'active' : ''); ?>">Rincian Barang</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>


                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(),Auth::user()->ba.'/prognosa') ? 'active' : ''); ?>" href="#">
                            <i data-feather="trending-up"></i><span>Prognosa </span>
                            <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), Auth::user()->ba.'/prognosa') ? 'down' : 'right'); ?>"></i></div>
                        </a>
                        <ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), Auth::user()->ba.'/prognosa') ? 'block;' : 'none;'); ?>">
                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'prognosa/eselon1') ? 'active' : ''); ?>" href="#">Eselon 1
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'prognosa/eselon1') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'prognosa/eselon1') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'eselon1','segment'=>'belanja'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/eselon1/belanja') ? 'active' : ''); ?>">Belanja</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'eselon1','segment'=>'sumberdana'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/eselon1/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'eselon1','segment'=>'kegiatan'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/eselon1/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'eselon1','segment'=>'output'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/eselon1/output') ? 'active' : ''); ?>">Output</a></li>
                                    
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'eselon1','segment'=>'kewenangan'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/eselon1/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'eselon1','segment'=>'akun'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/eselon1/akun') ? 'active' : ''); ?>">Akun</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'prognosa/propinsi') ? 'active' : ''); ?>" href="#">Propinsi
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'prognosa/propinsi') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'prognosa/propinsi') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'propinsi','segment'=>'belanja'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/propinsi/belanja') ? 'active' : ''); ?>">Belanja</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'propinsi','segment'=>'sumberdana'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/propinsi/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'propinsi','segment'=>'kegiatan'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/propinsi/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'propinsi','segment'=>'output'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/propinsi/output') ? 'active' : ''); ?>">Output</a></li>
                                    
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'propinsi','segment'=>'kewenangan'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/propinsi/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'propinsi','segment'=>'akun'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/propinsi/akun') ? 'active' : ''); ?>">Akun</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="submenu-title <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'prognosa/satker') ? 'active' : ''); ?>" href="#">Satker
                                    <div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'prognosa/satker') ? 'down' : 'right'); ?>"></i></div>
                                </a>
                                <ul class="nav-sub-childmenu submenu-content" style="display: <?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit'),'prognosa/satker') ? 'block' : 'none'); ?>;">
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'satker','segment'=>'belanja'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/satker/belanja') ? 'active' : ''); ?>">Belanja</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'satker','segment'=>'sumberdana'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/satker/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'satker','segment'=>'kegiatan'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/satker/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'satker','segment'=>'output'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/satker/output') ? 'active' : ''); ?>">Output</a></li>
                                    
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'satker','segment'=>'kewenangan'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/satker/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
                                    <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa',['unit'=>'satker','segment'=>'akun'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('unit').'/'.Request::route('segment'),'prognosa/satker/akun') ? 'active' : ''); ?>">Akun</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo e(route(Auth::user()->ba.'/prognosa/locking')); ?>" class="<?php echo e(str_contains(url()->current(), 'prognosa/locking') ? 'active' : ''); ?>">Gembok</a></li>
                        </ul>
                    </li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/mppnbp' ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/mppnbp')); ?>"><i data-feather="briefcase"> </i><span>Monitoring RPD MP</span></a></li>
                    <li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(), 'snipper') ? 'active' : ''); ?>" href="#"><i data-feather="users"></i><span>Monitoring SNIPPER</span>
								<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'snipper') ? 'down' : 'right'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), 'snipper') ? 'block' : 'none'); ?>;">
							<li><a href="<?php echo e(route(Auth::user()->ba.'/snipper',['what'=>'filesk'])); ?>" class="<?php echo e(str_contains(url()->current(), 'snipper/filesk') ? 'active' : ''); ?>">File SK</a></li>
	                        <li><a class="<?php echo e(str_contains(url()->current(), 'snipper/rekap') ? 'active' : ''); ?> OpenModalAdminSnipper static" id="0" what="propinsi" href="#">Daftar Pejabat</a></li>
						</ul>
					</li>


                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/pivot' ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/pivot')); ?>"><i data-feather="database"> </i><span>Pivot Data</span></a></li>

                    <?php if(Auth:: user()->access_id==1): ?>
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(), 'upload') ? 'active' : ''); ?>" href="#"><i data-feather="upload-cloud"></i><span>Upload</span>
								<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), 'upload') ? 'down' : 'right'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), 'upload') ? 'block' : 'none'); ?>;">
							<li><a href="<?php echo e(route('/upload',['what'=>'dipa'])); ?>" class="<?php echo e(str_contains(url()->current(), 'upload/dipa') ? 'active' : ''); ?>">SPAN DIPA</a></li>
	                        <li><a href="<?php echo e(route('/upload',['what'=>'realisasi'])); ?>" class="<?php echo e(str_contains(url()->current(), 'upload/realisasi') ? 'active' : ''); ?>">SPAN Belanja</a></li>
	                        <li><a href="<?php echo e(route('/upload',['what'=>'penerimaan'])); ?>" class="<?php echo e(str_contains(url()->current(), 'upload/penerimaan') ? 'active' : ''); ?>">SPAN PNBP</a></li>
	                        <li><a href="<?php echo e(route('/upload',['what'=>'mp'])); ?>" class="<?php echo e(str_contains(url()->current(), 'upload/mp') ? 'active' : ''); ?>">Alokasi MP</a></li>
	                        <li><a href="<?php echo e(route('/upload',['what'=>'semp'])); ?>" class="<?php echo e(str_contains(url()->current(), 'upload/semp') ? 'active' : ''); ?>">Surat Edaran MP</a></li>
	                        <li><a href="<?php echo e(route('/upload',['what'=>'prognosa'])); ?>" class="<?php echo e(str_contains(url()->current(), 'upload/prognosa') ? 'active' : ''); ?>">Prognosa Satker</a></li>
						</ul>
					</li>

                    <li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(), '/utility') ? 'active' : ''); ?>" href="#"><i data-feather="settings"></i><span>Utility</span>
								<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(), '/utility') ? 'down' : 'right'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(), '/utility') ? 'block' : 'none'); ?>;">
							<li><a href="<?php echo e(route(Auth::user()->ba.'/utility',['what'=>'userlist'])); ?>" class="<?php echo e(str_contains(url()->current(), 'utility/userlist') ? 'active' : ''); ?>">Daftar User</a></li>
							<li><a href="<?php echo e(route(Auth::user()->ba.'/utility',['what'=>'message'])); ?>" class="<?php echo e(str_contains(url()->current(), 'utility/message') ? 'active' : ''); ?>">Message</a></li>
						</ul>
					</li>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php if(Auth:: user()->level_id==3): ?>


					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/belanja' ? 'active' : ''); ?>" href="#">
							<i data-feather="shopping-bag"></i><span>Belanja</span>
							<div class="according-menu"><i class="fa fa-angle-<?php echo e(Route::currentRouteName() == Auth::user()->ba.'/belanja' ? 'down' : 'right'); ?>"></i></div>
						</a>

								<ul class="sidebar-submenu" style="display: <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/belanja' ? 'block' : 'none;'); ?>;">
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['segment'=>'belanja','month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('segment'),'belanja/belanja') ? 'active' : ''); ?>">Belanja</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['segment'=>'sumberdana','month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('segment'),'belanja/sumberdana') ? 'active' : ''); ?>">Sumber Dana</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['segment'=>'kegiatan','month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('segment'),'belanja/kegiatan') ? 'active' : ''); ?>">Kegiatan</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['segment'=>'output','month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('segment'),'belanja/output') ? 'active' : ''); ?>">Output</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['segment'=>'kewenangan','month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('segment'),'belanja/kewenangan') ? 'active' : ''); ?>">Kewenangan</a></li>
									
									<li><a href="<?php echo e(route(Auth::user()->ba.'/belanja',['segment'=>'akun','month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('segment'),'belanja/akun') ? 'active' : ''); ?>">Akun</a></li>
								</ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/prognosa' ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/prognosa')); ?>"><i data-feather="trending-up"> </i><span>Prognosa</span></a></li>

                    <li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/monitoring' ? 'active' : ''); ?>" href="#">
							<i data-feather="monitor"></i><span>Monitoring</span>
							<div class="according-menu"><i class="fa fa-angle-<?php echo e(Route::currentRouteName() == Auth::user()->ba.'/monitoring' ? 'down' : 'right'); ?>"></i></div>
						</a>

								<ul class="sidebar-submenu" style="display: <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/monitoring' ? 'block' : 'none;'); ?>;">
									<li><a href="<?php echo e(route(Auth::user()->ba.'/monitoring',['segment'=>'covid','month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('segment'),'monitoring/covid') ? 'active' : ''); ?>">COVID-19</a></li>
									<li><a href="<?php echo e(route(Auth::user()->ba.'/monitoring',['segment'=>'padatkarya','month'=>DATE('n')])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('segment'),'monitoring/padatkarya') ? 'active' : ''); ?>">Padat Karya</a></li>
								</ul>
                    </li>


					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/rangking/satker' ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/rangking/satker')); ?>#<?php echo e(Auth::user()->kdsatker); ?>"><i data-feather="bar-chart"> </i><span>Rangking</span></a></li>
					<li class="sidebar-list">
                        
                        
                        
                        <a class="sidebar-link sidebar-title link-nav <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/utility' ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/utility',['type'=>'inbox'])); ?>"><i data-feather="mail"></i><span>Inbox</span></a></li>

                    <li class="sidebar-list">
						<a class="sidebar-link sidebar-title <?php echo e(str_contains(Route::currentRouteName(),'/snipper') ? 'active' : ''); ?>" href="#">
							<i data-feather="target"></i><span>Snipper</span>
							<div class="according-menu"><i class="fa fa-angle-<?php echo e(str_contains(Route::currentRouteName(),'/snipper') ? 'down' : 'right'); ?>"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: <?php echo e(str_contains(Route::currentRouteName(),'/snipper') ? 'block' : 'none'); ?>;">
							<li><a href="<?php echo e(route(Auth::user()->ba.'/snipper/daftar',['type'=>'pejabat'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('type'),'snipper/daftar/pejabat') ? 'active' : ''); ?>">Pejabat Perbendaharaan</a></li>
							<li><a href="<?php echo e(route(Auth::user()->ba.'/snipper/daftar',['type'=>'berkas'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('type'),'snipper/daftar/berkas') ? 'active' : ''); ?>">File SK Pejabat</a></li>
							<li><a href="<?php echo e(route(Auth::user()->ba.'/snipper/daftar',['type'=>'calon'])); ?>" class="<?php echo e(str_contains(Route::currentRouteName().'/'.Request::route('type'),'snipper/daftar/calon') ? 'active' : ''); ?>">Pegawai Tersertifikasi</a></li>
						</ul>
                    </li>
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/mppnbp' ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/mppnbp')); ?>"><i data-feather="briefcase"> </i><span>MP PNBP</span></a></li>

                        <?php endif; ?>



                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo e(Route::currentRouteName() == Auth::user()->ba.'/profile' ? 'active' : ''); ?>" href="<?php echo e(route(Auth::user()->ba.'/profile')); ?>"><i data-feather="user"> </i><span>Profile</span></a></li>
					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav <?php echo e(Route::currentRouteName()=='logout' ? 'active' : ''); ?>" href="<?php echo e(route('logout')); ?>"><i data-feather="log-out"> </i><span>Logout</span></a></li>
				</ul>
			</div>
			<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
		</nav>
	</div>
</div>
TES

<?php /**PATH /Users/user/dev/app-monira/resources/views/layouts/simple/sidebar.blade.php ENDPATH**/ ?>