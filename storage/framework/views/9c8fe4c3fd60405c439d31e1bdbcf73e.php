<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Building Her Coaching</title>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>Building Her Coaching</h1>
            <h2>Login</h2>

            <?php if($errors->any()): ?>
                <div class="alert alert-error">
                    <?php echo e($errors->first()); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo e(old('username')); ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>

        <?php if(app()->environment('local') && isset($devUsers) && $devUsers->count()): ?>
            <div class="dev-login-panel">
                <div class="dev-login-header">
                    <span class="dev-login-icon">⚡</span>
                    <h3>Quick Login <span class="dev-mode-badge">DEV MODE</span></h3>
                    <p>Click any user below to instantly log in</p>
                </div>
                <div class="dev-login-grid">
                    <?php $__currentLoopData = $devUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $devUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <form method="POST" action="<?php echo e(route('dev.login')); ?>" class="dev-login-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="user_id" value="<?php echo e($devUser->id); ?>">
                            <button type="submit" class="dev-user-btn">
                                <div class="dev-user-info">
                                    <span class="dev-user-name"><?php echo e($devUser->full_name); ?></span>
                                    <span class="dev-user-username">@ <?php echo e($devUser->username); ?></span>
                                </div>
                                <span class="role-badge role-badge-<?php echo e($devUser->role); ?>"><?php echo e(ucfirst($devUser->role)); ?></span>
                            </button>
                        </form>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH /Users/naveenrodrigo/gymcoachingportal/resources/views/auth/login.blade.php ENDPATH**/ ?>