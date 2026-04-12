<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-calendar-check text-indigo-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Absensi Hari Ini</p>
                    <p class="text-2xl font-bold"><?php echo e($today_attendance); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Visitor Hari Ini</p>
                    <p class="text-2xl font-bold"><?php echo e($today_visitors); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-user-clock text-yellow-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Visitor Aktif</p>
                    <p class="text-2xl font-bold"><?php echo e($active_visitors); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
        <div class="flex space-x-4">
            <form method="POST" action="<?php echo e(route('attendance.check-in')); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-sign-in-alt"></i> Check In
                </button>
            </form>
            <form method="POST" action="<?php echo e(route('attendance.check-out')); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    <i class="fas fa-sign-out-alt"></i> Check Out
                </button>
            </form>
            <a href="<?php echo e(route('visitors.create')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                <i class="fas fa-user-plus"></i> Register Visitor
            </a>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <?php if(isset($recent_activities)): ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $recent_activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-6 py-4"><?php echo e($activity->name); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded <?php echo e($activity->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($activity->type); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4"><?php echo e($activity->attendance_time); ?></td>
                        <td class="px-6 py-4"><?php echo e($activity->ip_address ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php elseif(isset($my_attendances)): ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">My Recent Attendances</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $my_attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded <?php echo e($attendance->type == 'check_in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($attendance->type); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4"><?php echo e($attendance->attendance_time); ?></td>
                        <td class="px-6 py-4"><?php echo e($attendance->ip_address ?? '-'); ?></td>
                        <td class="px-6 py-4"><?php echo e($attendance->notes ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>