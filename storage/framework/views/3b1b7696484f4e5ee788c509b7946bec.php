<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Welcome back, <?php echo e(session('full_name')); ?>!</p>
</div>

<div class="stats-row">
    <div class="stat-card">
        <h3><?php echo e($totalClients); ?></h3>
        <p>Total Clients</p>
        <small><?php echo e($activeClients); ?> Active</small>
    </div>
    <div class="stat-card">
        <h3><?php echo e($totalAppointments); ?></h3>
        <p>Total Appointments</p>
        <small><?php echo e($upcomingAppointments); ?> Upcoming</small>
    </div>
    <div class="stat-card">
        <h3><?php echo e($todaysAppointments->count()); ?></h3>
        <p>Today's Sessions</p>
        <small><?php echo e(now()->format('l, F j')); ?></small>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dashboard-section">
        <h2>Today's Appointments - <?php echo e(now()->format('F j, Y')); ?></h2>
        <?php if($todaysAppointments->isEmpty()): ?>
            <p class="empty-message">No appointments scheduled for today.</p>
        <?php else: ?>
            <div class="appointment-list">
                <?php $__currentLoopData = $todaysAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="appointment-card">
                        <div class="appointment-time">
                            <strong><?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A')); ?></strong>
                            <span><?php echo e($appointment->duration_minutes); ?> min</span>
                        </div>
                        <div class="appointment-details">
                            <h4>
                                <a href="<?php echo e(route('clients.show', $appointment->client_id)); ?>">
                                    <?php echo e($appointment->client->full_name); ?>

                                </a>
                            </h4>
                            <p><?php echo e($appointment->session_type); ?></p>
                            <?php if($appointment->client->phone): ?>
                                <p class="phone"><?php echo e($appointment->client->phone); ?></p>
                            <?php endif; ?>
                            <?php if($appointment->notes): ?>
                                <p class="notes"><?php echo e($appointment->notes); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="appointment-status">
                            <span class="status status-<?php echo e(strtolower($appointment->status)); ?>"><?php echo e($appointment->status); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="dashboard-section">
        <h2>Upcoming Appointments</h2>
        <?php if($upcoming->isEmpty()): ?>
            <p class="empty-message">No upcoming appointments in the next 7 days.</p>
        <?php else: ?>
            <table class="compact-table">
                <tbody>
                    <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e(\Carbon\Carbon::parse($appointment->appointment_date)->format('M j')); ?></strong><br>
                                <small><?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A')); ?></small>
                            </td>
                            <td>
                                <a href="<?php echo e(route('clients.show', $appointment->client_id)); ?>">
                                    <?php echo e($appointment->client->full_name); ?>

                                </a><br>
                                <small><?php echo e($appointment->session_type); ?></small>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="dashboard-section">
        <h2>Recent Clients</h2>
        <?php if($recentClients->isEmpty()): ?>
            <p class="empty-message">No clients yet.</p>
        <?php else: ?>
            <table class="compact-table">
                <tbody>
                    <?php $__currentLoopData = $recentClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <a href="<?php echo e(route('clients.show', $client->id)); ?>">
                                    <?php echo e($client->full_name); ?>

                                </a><br>
                                <small><?php echo e($client->email); ?></small>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e(strtolower($client->membership_type)); ?>">
                                    <?php echo e($client->membership_type); ?>

                                </span>
                            </td>
                            <td>
                                <span class="status status-<?php echo e(strtolower(str_replace(' ', '-', $client->status))); ?>">
                                    <?php echo e($client->status); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-link">View All Clients</a>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/gymcoachingportal/resources/views/dashboard.blade.php ENDPATH**/ ?>