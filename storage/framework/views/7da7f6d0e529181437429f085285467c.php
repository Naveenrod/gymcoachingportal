<?php $__env->startSection('title', 'Clients'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Clients</h1>
    <a href="<?php echo e(route('clients.create')); ?>" class="btn btn-primary">Add New Client</a>
</div>

<div class="filters">
    <form method="GET" action="" class="filter-form">
        <input type="text" name="search" placeholder="Search clients..." value="<?php echo e(request('search')); ?>">
        <select name="status">
            <option value="">All Status</option>
            <option value="Active" <?php echo e(request('status') == 'Active' ? 'selected' : ''); ?>>Active</option>
            <option value="Inactive" <?php echo e(request('status') == 'Inactive' ? 'selected' : ''); ?>>Inactive</option>
            <option value="On Hold" <?php echo e(request('status') == 'On Hold' ? 'selected' : ''); ?>>On Hold</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        <?php if(request('search') || request('status')): ?>
            <a href="<?php echo e(route('clients.index')); ?>" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
    </form>
</div>

<div class="stats-row">
    <div class="stat-card">
        <h3><?php echo e($clients->count()); ?></h3>
        <p>Total Clients</p>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Membership</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if($clients->isEmpty()): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No clients found</td>
                </tr>
            <?php else: ?>
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($client->full_name); ?></td>
                        <td><?php echo e($client->email); ?></td>
                        <td><?php echo e($client->phone ?: '-'); ?></td>
                        <td><span class="badge badge-<?php echo e(strtolower($client->membership_type)); ?>"><?php echo e($client->membership_type); ?></span></td>
                        <td><span class="status status-<?php echo e(strtolower(str_replace(' ', '-', $client->status))); ?>"><?php echo e($client->status); ?></span></td>
                        <td class="actions">
                            <a href="<?php echo e(route('clients.show', $client)); ?>" class="btn btn-sm btn-info">View</a>
                            <a href="<?php echo e(route('clients.edit', $client)); ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="<?php echo e(route('clients.destroy', $client)); ?>" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this client?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/gymcoachingportal/resources/views/clients/index.blade.php ENDPATH**/ ?>