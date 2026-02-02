<?php $__env->startSection('title', 'Client Check In'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Client Check In List</h1>
    <p><?php echo e($clients->total()); ?> <?php echo e(Str::plural('client', $clients->total())); ?> total</p>
</div>

<div class="checkin-container">
    <?php if(session('success')): ?>
        <div class="alert alert-success" style="margin: 1rem 1rem 0;"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="checkin-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Loom Link</th>
                    <th>Package</th>
                    <th>Frequency</th>
                    <th>Day</th>
                    <th>Status</th>
                    <th>Rank</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <form method="POST" action="<?php echo e(route('checkin.update', $client)); ?>" class="checkin-form" id="form-<?php echo e($client->id); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <td class="client-name-cell">
                                <a href="<?php echo e(route('clients.show', $client)); ?>"><?php echo e($client->full_name); ?></a>
                            </td>
                            <td class="loom-link-cell">
                                <input type="url" name="loom_link" value="<?php echo e($client->loom_link ?? ''); ?>" placeholder="https://loom.com/...">
                            </td>
                            <td class="package-cell">
                                <select name="package">
                                    <option value="">Select Package</option>
                                    <option value="Macros & Program" <?php echo e(($client->package ?? '') == 'Macros & Program' ? 'selected' : ''); ?>>Macros & Program</option>
                                    <option value="Meal Plan & Prog" <?php echo e(($client->package ?? '') == 'Meal Plan & Prog' ? 'selected' : ''); ?>>Meal Plan & Prog</option>
                                    <option value="Ambassador" <?php echo e(($client->package ?? '') == 'Ambassador' ? 'selected' : ''); ?>>Ambassador</option>
                                    <option value="Program Only" <?php echo e(($client->package ?? '') == 'Program Only' ? 'selected' : ''); ?>>Program Only</option>
                                </select>
                            </td>
                            <td class="frequency-cell">
                                <select name="check_in_frequency">
                                    <option value="">Select</option>
                                    <option value="Weekly" <?php echo e(($client->check_in_frequency ?? '') == 'Weekly' ? 'selected' : ''); ?>>Weekly</option>
                                    <option value="Fortnightly" <?php echo e(($client->check_in_frequency ?? '') == 'Fortnightly' ? 'selected' : ''); ?>>Fortnightly</option>
                                    <option value="Monthly" <?php echo e(($client->check_in_frequency ?? '') == 'Monthly' ? 'selected' : ''); ?>>Monthly</option>
                                </select>
                            </td>
                            <td class="day-cell">
                                <select name="check_in_day">
                                    <option value="">Select</option>
                                    <option value="Monday" <?php echo e(($client->check_in_day ?? '') == 'Monday' ? 'selected' : ''); ?>>Mon</option>
                                    <option value="Tuesday" <?php echo e(($client->check_in_day ?? '') == 'Tuesday' ? 'selected' : ''); ?>>Tue</option>
                                    <option value="Wednesday" <?php echo e(($client->check_in_day ?? '') == 'Wednesday' ? 'selected' : ''); ?>>Wed</option>
                                    <option value="Thursday" <?php echo e(($client->check_in_day ?? '') == 'Thursday' ? 'selected' : ''); ?>>Thu</option>
                                    <option value="Friday" <?php echo e(($client->check_in_day ?? '') == 'Friday' ? 'selected' : ''); ?>>Fri</option>
                                    <option value="Saturday" <?php echo e(($client->check_in_day ?? '') == 'Saturday' ? 'selected' : ''); ?>>Sat</option>
                                    <option value="Sunday" <?php echo e(($client->check_in_day ?? '') == 'Sunday' ? 'selected' : ''); ?>>Sun</option>
                                </select>
                            </td>
                            <td class="submitted-cell <?php echo e(($client->submitted ?? '') == 'Submitted' ? 'submitted-active' : ''); ?>">
                                <select name="submitted" class="submitted-select">
                                    <option value="">Not Submitted</option>
                                    <option value="Submitted" <?php echo e(($client->submitted ?? '') == 'Submitted' ? 'selected' : ''); ?>>Submitted</option>
                                </select>
                            </td>
                            <td class="rank-cell">
                                <input type="text" name="rank" value="<?php echo e($client->rank ?? ''); ?>" placeholder="e.g., Gold">
                            </td>
                            <td class="actions-cell">
                                <button type="submit" class="btn-save">Save</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="empty-message">
                            No clients found. <a href="<?php echo e(route('clients.create')); ?>">Add a client</a> to get started.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($clients->hasPages()): ?>
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-color);">
            <?php echo e($clients->links()); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateSubmittedCells() {
        document.querySelectorAll('select[name="submitted"]').forEach(select => {
            const cell = select.closest('td.submitted-cell');
            if (select.value === 'Submitted') {
                cell.classList.add('submitted-active');
            } else {
                cell.classList.remove('submitted-active');
            }
        });
    }

    updateSubmittedCells();

    document.querySelectorAll('select[name="submitted"]').forEach(select => {
        select.addEventListener('change', function() {
            const cell = this.closest('td.submitted-cell');
            cell.classList.toggle('submitted-active', this.value === 'Submitted');
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/gymcoachingportal/resources/views/checkin/index.blade.php ENDPATH**/ ?>