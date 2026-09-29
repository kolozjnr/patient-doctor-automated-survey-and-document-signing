
<div x-data="notificationHandler()" 
     x-init="init()"
     style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
    
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-end"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 translate-end"
             class="alert d-flex align-items-center mb-3 shadow-sm"
             :class="{
                 'alert-success': notification.type === 'success',
                 'alert-danger': notification.type === 'error',
                 'alert-warning': notification.type === 'warning',
                 'alert-info': notification.type === 'info'
             }"
             role="alert"
             style="min-width: 300px;">
            
            <!-- Icon -->
            <div class="flex-shrink-0 me-3">
                <template x-if="notification.type === 'success'">
                    <svg width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                </template>
                <template x-if="notification.type === 'error'">
                    <svg width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                    </svg>
                </template>
                <template x-if="notification.type === 'warning'">
                    <svg width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                </template>
                <template x-if="notification.type === 'info'">
                    <svg width="20" height="20" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                    </svg>
                </template>
            </div>
            
            <!-- Content -->
            <div class="flex-grow-1" x-text="notification.message"></div>
            
            <!-- Close Button -->
            <button type="button" 
                    class="btn-close" 
                    @click="removeNotification(notification.id)"
                    aria-label="Close"></button>
        </div>
    </template>
</div>

<style>
.translate-end {
    transform: translateX(100%);
}
</style>

<script>
(function() {
    if (window.notificationSystemInitialized) {
        return;
    }
    window.notificationSystemInitialized = true;

    // Global helper function to trigger notifications
    window.notify = function(type, message) {
        window.dispatchEvent(new CustomEvent('notify-message', {
            detail: { type, message }
        }));
    };
})();

function notificationHandler() {
    return {
        notifications: [],
        listenerAdded: false,
        
        init() {
            // Only add listener once per component instance
            if (!this.listenerAdded) {
                window.addEventListener('notify-message', (event) => {
                    this.addNotification(event.detail.type, event.detail.message);
                });
                this.listenerAdded = true;
            }
            
            // Check for Laravel session flash messages (only once on init)
            <?php if(session('success')): ?>
                this.addNotification('success', '<?php echo e(session('success')); ?>');
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                this.addNotification('error', '<?php echo e(session('error')); ?>');
            <?php endif; ?>
            
            <?php if(session('warning')): ?>
                this.addNotification('warning', '<?php echo e(session('warning')); ?>');
            <?php endif; ?>
            
            <?php if(session('info')): ?>
                this.addNotification('info', '<?php echo e(session('info')); ?>');
            <?php endif; ?>
            
            // Check for Laravel validation errors
            <?php if($errors->any()): ?>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    this.addNotification('error', '<?php echo e($error); ?>');
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        },
        
        addNotification(type, message) {
            const id = Date.now() + Math.random();
            const notification = {
                id: id,
                type: type,
                message: message,
                show: false
            };
            
            this.notifications.push(notification);
            
            // Trigger animation
            setTimeout(() => {
                const notif = this.notifications.find(n => n.id === id);
                if (notif) notif.show = true;
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                this.removeNotification(id);
            }, 5000);
        },
        
        removeNotification(id) {
            const notif = this.notifications.find(n => n.id === id);
            if (notif) {
                notif.show = false;
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 300);
            }
        }
    }
}
</script>
<?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/layouts/simple/noti.blade.php ENDPATH**/ ?>