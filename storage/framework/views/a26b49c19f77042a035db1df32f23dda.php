<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Gym Coaching Portal'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-brand">
            <h2>Building Her Coaching</h2>
        </div>
        <ul class="nav-menu">
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->role !== 'client'): ?>
                    <li><a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">Dashboard</a></li>
                    <li><a href="<?php echo e(route('clients.index')); ?>" class="<?php echo e(request()->routeIs('clients.*') ? 'active' : ''); ?>">Clients</a></li>
                    <li><a href="<?php echo e(route('appointments.index')); ?>" class="<?php echo e(request()->routeIs('appointments.*') ? 'active' : ''); ?>">Appointments</a></li>
                    <li><a href="<?php echo e(route('checkin.index')); ?>" class="<?php echo e(request()->routeIs('checkin.*') ? 'active' : ''); ?>">Check In</a></li>
                    <?php if(Auth::user()->isAdmin()): ?>
                        <li><a href="<?php echo e(route('users.index')); ?>" class="<?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">Users</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="<?php echo e(route('portal.dashboard')); ?>" class="<?php echo e(request()->routeIs('portal.dashboard') ? 'active' : ''); ?>">My Dashboard</a></li>
                    <li><a href="<?php echo e(route('portal.appointments')); ?>" class="<?php echo e(request()->routeIs('portal.appointments') ? 'active' : ''); ?>">My Appointments</a></li>
                    <li><a href="<?php echo e(route('portal.profile')); ?>" class="<?php echo e(request()->routeIs('portal.profile') ? 'active' : ''); ?>">My Profile</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        <div class="nav-user">
            <span>Welcome, <?php echo e(Auth::check() ? Auth::user()->full_name : ''); ?></span>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-sm btn-secondary">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-error alert-dismissible" role="alert"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if(session('warning')): ?>
            <div class="alert alert-warning alert-dismissible" role="alert"><?php echo e(session('warning')); ?></div>
        <?php endif; ?>
        <?php if(session('info')): ?>
            <div class="alert alert-info alert-dismissible" role="alert"><?php echo e(session('info')); ?></div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <footer class="footer">
        <p>&copy; <?php echo e(date('Y')); ?> Building Her Coaching. All rights reserved.</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(function() { alert.remove(); }, 500);
                }, 5000);
            });
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/naveenrodrigo/gymcoachingportal/resources/views/layouts/app.blade.php ENDPATH**/ ?>