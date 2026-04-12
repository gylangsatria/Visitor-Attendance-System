<?php $__env->startSection('title', 'User Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">User List</h3>
        <a href="<?php echo e(route('users.create')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Access Level</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="px-6 py-4"><?php echo e($user->name); ?></td>
                    <td class="px-6 py-4"><?php echo e($user->email); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded 
                            <?php if($user->access_level == 1): ?> bg-red-100 text-red-800
                            <?php elseif($user->access_level == 2): ?> bg-yellow-100 text-yellow-800
                            <?php else: ?> bg-blue-100 text-blue-800 <?php endif; ?>">
                            <?php echo e($user->access_level_name); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4"><?php echo e($user->phone ?? '-'); ?></td>
                    <td class="px-6 py-4">
                        <a href="<?php echo e(route('users.edit', $user)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <?php if(auth()->id() != $user->id): ?>
                        <form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        <?php echo e($users->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/users/index.blade.php ENDPATH**/ ?>