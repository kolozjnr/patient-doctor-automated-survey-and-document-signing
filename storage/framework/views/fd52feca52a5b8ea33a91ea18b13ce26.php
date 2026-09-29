<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch(Route::currentRouteName()):
    case ('admin.footer_dark'): ?>
        <footer class="footer footer-dark">
        <?php break; ?>
    
    <?php case ('admin.footer_fixed'): ?>
        <footer class="footer footer-fix">
        <?php break; ?>

    <?php default: ?>
       <footer class="footer">
<?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 footer-copyright text-center">
                <p class="mb-0">Copyright <span class="year-update"> </span> © DSTRCT GROUP </p>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/layouts/simple/footer.blade.php ENDPATH**/ ?>