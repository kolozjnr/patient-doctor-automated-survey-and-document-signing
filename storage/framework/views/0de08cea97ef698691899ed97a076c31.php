<!-- Page Sidebar Start-->
<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper"><a href="<?php echo e(route('admin.dashboard')); ?>"><img class="img-fluid for-light"
                    src="<?php echo e(asset('assets/images/logo/main-logo.png')); ?>" alt="logo" style="max-width: 60%; max-height: auto;"><img class="img-fluid for-dark"
                    src="<?php echo e(asset('assets/images/logo/main_logo.png')); ?>" alt=""></a>
            <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid">
                </i></div>
        </div>
        <div class="logo-icon-wrapper"><a href="<?php echo e(route('admin.dashboard')); ?>"><img class="img-fluid"
                    src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"><a href="<?php echo e(route('admin.dashboard')); ?>"><img class="img-fluid"
                                src="<?php echo e(asset('assets/images/logo/logo-icon.png')); ?>" alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa-solid fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    <li class="pin-title sidebar-main-title">
                        <div>
                            <h6>Pinned</h6>
                        </div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6 class="lan-1">General</h6>
                        </div>
                    </li>
                    
                   
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('admin.dashboard')); ?>"><svg
                            class="stroke-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                        </svg><svg class="fill-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-home')); ?>"></use>
                        </svg> <span><?php echo e(__('Dashboard')); ?></span></a>
                    </li>
                    
                    <li class="sidebar-list"><a
                        class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('admin.list_patients')); ?>"><svg
                            class="stroke-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-user')); ?>"></use>
                        </svg><svg class="fill-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-user')); ?>"></use>
                        </svg> <span><?php echo e(__('Patients')); ?></span></a>
                    </li>
                    
                    <li class="sidebar-list"><a
                            class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('admin.departments')); ?>"><svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-sitemap')); ?>"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-sitemap')); ?>"></use>
                            </svg>
                            <span><?php echo e(__('Departments')); ?></span></a>
                    </li>

                    <li class="sidebar-list"><a
                        class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('admin.documents.index')); ?>"><svg
                            class="stroke-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-file')); ?>"></use>
                        </svg><svg class="fill-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-file')); ?>"></use>
                        </svg> <span><?php echo e(__('Document')); ?></span></a>
                    </li>

                     <li class="sidebar-list"><a
                        class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('admin.faq.index')); ?>"><svg
                            class="stroke-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-faq')); ?>"></use>
                        </svg><svg class="fill-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-faq')); ?>"></use>
                        </svg> <span><?php echo e(__('FAQ')); ?></span></a>
                    </li>


                      
                    

                    
                    <li class="sidebar-list"></i><a
                            class="sidebar-link sidebar-title" href="javascript:void(0)"><svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-sample-page')); ?>"></use>
                            </svg><svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-sample-page')); ?>"></use>
                            </svg><span><?php echo e(__('Questions')); ?></span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('admin.questions.index', ['module' => 'general'])); ?>"><?php echo e(__('General Questions')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.questions.index', ['module' => 'bellscale'])); ?>"><?php echo e(__('Bell Scale Questions')); ?></a></li>
                            <li><a href="<?php echo e(route('admin.questions.index', ['module' => 'intake'])); ?>"><?php echo e(__('Intake Questions')); ?></a></li>
                            <li>
                                
                                <a href="<?php echo e(route('admin.questions.index', ['module' => 'monthly'])); ?>"><?php echo e(__('Monthly Questions')); ?></a></li>
                                
                            <li><a href="<?php echo e(route('admin.questions.index', ['module' => 'survey'])); ?>"><?php echo e(__('Survey Questions')); ?></a></li>
                        </ul>
                    </li>
                    
                    <li class="sidebar-list"></i><a
                        class="sidebar-link sidebar-title link-nav" href="<?php echo e(route('admin.survey.index')); ?>"><svg
                            class="stroke-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#c-invoice')); ?>"></use>
                        </svg><svg class="fill-icon">
                            <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#c-invoice')); ?>"></use>
                        </svg> <span><?php echo e(__('Surveys')); ?></span></a>
                    </li>

                    
                    <li class="sidebar-list">
                        
                        
                        <a class="sidebar-link sidebar-title"
                            href="javascript:void(0)"><svg class="stroke-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-reports')); ?>"></use>
                            </svg><svg class="fill-icon">
                                <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#fill-reports')); ?>"></use>
                            </svg><span><?php echo e(__("Reports")); ?></span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="<?php echo e(route('admin.reports.getSurveyReport')); ?>"><?php echo e(__("Survey Report")); ?></a></li>
                            <li><a href="<?php echo e(route('admin.reports.questionReport')); ?>"><?php echo e(__("Question Report")); ?></a></li>
                            <li><a href="<?php echo e(route('admin.reports.bellscaleReport')); ?>"><?php echo e(__("Bellscale Report")); ?></a></li>
                            <li><a href="<?php echo e(route('admin.reports.chartReport')); ?>"><?php echo e(__("Overall Report")); ?></a></li>
                        </ul>
                    </li>
                
                    
                     
                  
         
                    
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
<!-- Page Sidebar Ends-->
<?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/layouts/simple/sidebar.blade.php ENDPATH**/ ?>