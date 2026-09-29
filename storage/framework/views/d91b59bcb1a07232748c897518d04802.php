<?php $__env->startSection('title', 'ECC Dashboard'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/jquery.dataTables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/vector-map1/jsvectormap.min.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"> <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg></a></li>
                        <li class="breadcrumb-item">ECC</li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <div class="container-fluid dashboard-5">
        <div class="row">
            <div class="col-12 od-xl-1">
                <div class="row">
                    <div class="col s-xxl-3 box-col-4">
                        <div class="card social-widget widget-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="social-icons">
                                            <i class="fa-solid fa-bed"></i>
                                            
                                            </div><span><?php echo e(__('Patients')); ?></span>
                                    </div><span class="font-success f-12 d-xxl-block">+22.9%</span>
                                </div>
                                <div class="social-content">
                                    <div>
                                        <h5 class="mb-1 counter" data-target="<?php echo e($totalPatients); ?>"><?php echo e($totalPatients ?? 0); ?></h5>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s-xxl-3 box-col-4">
                        <div class="card social-widget widget-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="social-icons">
                                            
                                            <i class="fa-solid fa-question"></i>
                                            </div><span><?php echo e(__('Questions')); ?></span>
                                    </div>
                                    
                                </div>
                                <div class="social-content">
                                    <div>
                                        <h5 class="mb-1 counter" data-target="<?php echo e($totalQuestions); ?>"><?php echo e($totalQuestions ?? 0); ?></h5>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s-xxl-3 box-col-4">
                        <div class="card social-widget widget-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="social-icons">
                                            
                                            <i class="fa-solid fa-clipboard-list"></i>
                                            </div>
                                                <span><?php echo e(__('Survey')); ?></span>
                                    </div><span class="font-success f-12 d-xxl-block">+76.10%</span>
                                </div>
                                <div class="social-content">
                                    <div>
                                        <h5 class="mb-1 counter" data-target="<?php echo e($totalSurveys); ?>"><?php echo e($totalSurveys ?? 0); ?></h5>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s-xxl-3 box-col-4">
                        <div class="card social-widget widget-hover">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="social-icons">
                                            
                                            <i class="fa-solid fa-users"></i>
                                            </div><span><?php echo e(__('Employees')); ?></span>
                                    </div><span class="font-success f-12 d-xxl-block">+62.08%</span>
                                </div>
                                <div class="social-content">
                                    <div>
                                        <h5 class="mb-1 counter" data-target="<?php echo e($totalEmployees); ?>"><?php echo e($totalEmployees ?? 0); ?></h5>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-12 col-xl-11 od-xl-7 box-col-7">
                <div class="card">
                    <div class="card-header card-no-border">
                        <div class="header-top">
                            <h5 class="m-0"></h5>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="subscriber-chart-container">
                            <div id="dashboard-chart"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid Ends-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/apex-chart.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/chart/apex-chart/stock-prices.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/counter/counter-custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/dataTables.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/dataTables.select.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/select.bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vector-map1/jsvectormap.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vector-map1/world.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vector-map1/custom-vectormap.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dashboard/dashboard_5.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/dashboards/dashboard.blade.php ENDPATH**/ ?>