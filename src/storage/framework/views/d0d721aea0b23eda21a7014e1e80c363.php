<?php $__env->startSection('title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <h3 class="text-lg font-semibold mb-4">Edit User</h3>
    
    <form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name *</label>
            <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Access Level *</label>
            <select name="access_level" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="2" <?php echo e($user->access_level == 2 ? 'selected' : ''); ?>>Editor (Level 2)</option>
                <option value="3" <?php echo e($user->access_level == 3 ? 'selected' : ''); ?>>Viewer (Level 3)</option>
                <option value="4" <?php echo e($user->access_level == 4 ? 'selected' : ''); ?>>Guest (Level 4)</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
            <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
            <textarea name="address" class="w-full px-3 py-2 border rounded-lg" rows="3"><?php echo e(old('address', $user->address)); ?></textarea>
        </div>
        
        <div class="flex justify-end">
            <a href="<?php echo e(route('users.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                Cancel
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Update User
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/users/edit.blade.php ENDPATH**/ ?>