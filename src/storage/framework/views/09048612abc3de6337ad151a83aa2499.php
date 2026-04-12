<?php $__env->startSection('title', 'Attendance History'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Attendance History</h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isEditor()): ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <?php endif; ?>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isEditor()): ?>
                    <td class="px-6 py-4"><?php echo e($attendance->user->name); ?></td>
                    <?php endif; ?>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded <?php echo e($attendance->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($attendance->type); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4"><?php echo e($attendance->attendance_time); ?></td>
                    <td class="px-6 py-4"><?php echo e($attendance->ip_address ?? '-'); ?></td>
                    <td class="px-6 py-4"><?php echo e($attendance->notes ?? '-'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e(auth()->user()->isAdmin() || auth()->user()->isEditor() ? 5 : 4); ?>" class="px-6 py-4 text-center text-gray-500">
                        No attendance records found.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        <?php echo e($attendances->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/attendances/index.blade.php ENDPATH**/ ?>