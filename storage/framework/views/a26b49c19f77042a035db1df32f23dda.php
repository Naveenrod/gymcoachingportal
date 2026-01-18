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
    <nav class="navbar">
        <div class="nav-brand">
            <h2>Building Her Coaching</h2>
        </div>
        <ul class="nav-menu">
            <li><a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">Dashboard</a></li>
            <li><a href="<?php echo e(route('clients.index')); ?>" class="<?php echo e(request()->routeIs('clients.*') ? 'active' : ''); ?>">Clients</a></li>
            <li><a href="<?php echo e(route('checkin.index')); ?>" class="<?php echo e(request()->routeIs('checkin.*') ? 'active' : ''); ?>">Check In</a></li>
        </ul>
        <div class="nav-user">
            <span>Welcome, <?php echo e(session('full_name')); ?></span>
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-sm btn-secondary">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-error"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <footer class="footer">
        <p>&copy; <?php echo e(date('Y')); ?> Gym Coaching Portal. All rights reserved.</p>
    </footer>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/naveenrodrigo/gymcoachingportal/resources/views/layouts/app.blade.php ENDPATH**/ ?>