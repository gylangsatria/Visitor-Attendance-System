<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-indigo-600">VAS</h2>
            <p class="text-gray-600 mt-2">Visitor & Attendance System</p>
        </div>
        <h3 class="text-xl mb-6 text-center">Login</h3>
        
        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            
            <button type="submit" 
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                Login
            </button>
        </form>
        
        <div class="mt-4 text-sm text-gray-600">
            <p class="text-center">Demo Accounts:</p>
            <ul class="text-xs mt-2 space-y-1">
                <li>Admin: admin@vas.com / password123</li>
                <li>Editor: editor@vas.com / password123</li>
                <li>Viewer: viewer@vas.com / password123</li>
                <li>Guest: guest@vas.com / password123</li>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>