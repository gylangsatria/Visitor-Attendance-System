<?php $__env->startSection('title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Profile Information</h3>
        
        <?php if(auth()->user()->canEdit()): ?>
        <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" name="name" value="<?php echo e($user->name); ?>" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="text" name="phone" value="<?php echo e($user->phone); ?>" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                <textarea name="address" class="w-full px-3 py-2 border rounded-lg" rows="3"><?php echo e($user->address); ?></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Avatar</label>
                <?php if($user->avatar): ?>
                    <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" class="w-20 h-20 rounded-full mb-2">
                <?php endif; ?>
                <input type="file" name="avatar" accept="image/*" class="w-full px-3 py-2 border rounded-lg">
            </div>
            
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Update Profile
            </button>
        </form>
        <?php else: ?>
        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">Name</label>
                <p class="text-gray-900"><?php echo e($user->name); ?></p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">Email</label>
                <p class="text-gray-900"><?php echo e($user->email); ?></p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">Access Level</label>
                <p class="text-gray-900"><?php echo e($user->access_level_name); ?></p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">Phone</label>
                <p class="text-gray-900"><?php echo e($user->phone ?? '-'); ?></p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-1">Address</label>
                <p class="text-gray-900"><?php echo e($user->address ?? '-'); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Change Password -->
    <?php if(auth()->user()->canEdit()): ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Change Password</h3>
        
        <form method="POST" action="<?php echo e(route('profile.update-password')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Current Password</label>
                <input type="password" name="current_password" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
                <input type="password" name="new_password" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Change Password
            </button>
        </form>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/profile/show.blade.php ENDPATH**/ ?>