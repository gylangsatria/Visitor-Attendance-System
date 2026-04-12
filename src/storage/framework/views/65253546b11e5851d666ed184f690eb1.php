<?php $__env->startSection('title', 'Register Visitor'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
    <h3 class="text-lg font-semibold mb-4">Register New Visitor</h3>
    
    <form method="POST" action="<?php echo e(route('visitors.store')); ?>">
        <?php echo csrf_field(); ?>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name *</label>
            <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Phone *</label>
            <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">ID Card Number</label>
            <input type="text" name="id_card_number" value="<?php echo e(old('id_card_number')); ?>" class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Company</label>
            <input type="text" name="company" value="<?php echo e(old('company')); ?>" class="w-full px-3 py-2 border rounded-lg">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Purpose *</label>
            <textarea name="purpose" class="w-full px-3 py-2 border rounded-lg" rows="3" required><?php echo e(old('purpose')); ?></textarea>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Person to Meet *</label>
            <input type="text" name="person_to_meet" value="<?php echo e(old('person_to_meet')); ?>" class="w-full px-3 py-2 border rounded-lg" required>
        </div>
        
        <div class="flex justify-end">
            <a href="<?php echo e(route('visitors.index')); ?>" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 mr-2">
                Cancel
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Register Visitor
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/visitors/create.blade.php ENDPATH**/ ?>