<!DOCTYPE html>
<html lang="en" <?php if(Route::currentRouteName() == 'admin.rtl_layout'): ?> dir="rtl" <?php endif; ?>>

<head>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

     <style>
        /* Target the modal-content for the loading state */
        .content-loading {
            position: relative; /* Essential so the ::before stays inside here */
            pointer-events: none;
            min-height: 200px; /* Ensures the loader is visible even if body is empty */
        }
        #grip-click{
            display: none;
        }
        #cog-click{
            display: none;
        }
        .colorpick-eyedropper-input-trigger{
            display: none;
        }

        .content-loading::before {
            content: '';
            position: absolute; /* Change to absolute */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
            background-color: rgba(255, 255, 255, 0.8);
            background-image: url("https://user-images.githubusercontent.com/4838076/36352948-b8931430-149e-11e8-9f4b-3f00bc444a6d.gif");
            background-repeat: repeat;
            background-position: center;
            background-size: 30% 30%;
            border-radius: calc(0.3rem - 1px); /* Match Bootstrap modal rounded corners */
        }
    </style>

    <?php echo $__env->make('layouts.simple.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('layouts.simple.css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
</head>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch(Route::currentRouteName()):
    case ('admin.dashboard'): ?>
        <body onload="startTime()">
        <?php break; ?>

    <?php case ('admin.box_layout'): ?>
        <body class="box-layout">
        <?php break; ?>

    <?php case ('admin.rtl_layout'): ?>
        <body class="rtl">
        <?php break; ?>

    <?php case ('admin.dark_layout'): ?>
        <body class="dark-only">
        <?php break; ?>

    <?php default: ?>
        <body>
<?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <!-- loader starts-->
    <div class="loader-wrapper">
        <div class="loader-index"><span></span></div>
        <svg>
            <defs></defs>
            <filter id="goo">
                <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo">
                </fecolormatrix>
            </filter>
        </svg>
    </div>
    <!-- loader ends-->

    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->

    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <?php echo $__env->make('layouts.simple.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <?php echo $__env->make('layouts.simple.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="page-body">
            <?php echo $__env->make('layouts.simple.noti', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php echo $__env->yieldContent('main_content'); ?>
            </div>
            
            <?php echo $__env->make('layouts.simple.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
    <?php echo $__env->make('layouts.simple.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('admin.inc.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/layouts/simple/master.blade.php ENDPATH**/ ?>