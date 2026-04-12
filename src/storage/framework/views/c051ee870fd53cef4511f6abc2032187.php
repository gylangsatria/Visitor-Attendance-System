<?php $__env->startSection('title', 'Visitors'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Visitor List</h3>
        <a href="<?php echo e(route('visitors.create')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            <i class="fas fa-plus"></i> Register Visitor
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Person to Meet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $visitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visitor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4"><?php echo e($visitor->name); ?></td>
                    <td class="px-6 py-4"><?php echo e($visitor->phone); ?></td>
                    <td class="px-6 py-4"><?php echo e($visitor->person_to_meet); ?></td>
                    <td class="px-6 py-4"><?php echo e($visitor->check_in_time->format('d/m/Y H:i')); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded <?php echo e($visitor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                            <?php echo e($visitor->status); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="<?php echo e(route('visitors.show', $visitor)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <?php if($visitor->status == 'active'): ?>
                        <form method="POST" action="<?php echo e(route('visitors.checkout', $visitor)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-sign-out-alt"></i> Check Out
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No visitors found.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        <?php echo e($visitors->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/visitors/index.blade.php ENDPATH**/ ?>