<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>VAS - <?php echo $__env->yieldContent('title', 'Visitor & Attendance System'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-indigo-600">VAS</h1>
                        <span class="ml-2 text-sm text-gray-500">Visitor & Attendance System</span>
                    </div>
                    <?php if(auth()->guard()->check()): ?>
                    <div class="ml-6 flex space-x-8">
                        <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600">Dashboard</a>
                        <a href="<?php echo e(route('attendances.index')); ?>" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600">History Absensi</a>
                        <a href="<?php echo e(route('visitors.index')); ?>" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600">Visitors</a>
                        <?php if(auth()->user()->canEdit()): ?>
                        <a href="<?php echo e(route('users.index')); ?>" class="inline-flex items-center px-1 pt-1 text-gray-900 hover:text-indigo-600">Manajemen User</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-user-tag"></i> <?php echo e(auth()->user()->access_level_name); ?>

                    </span>
                    <a href="<?php echo e(route('profile.show')); ?>" class="text-gray-700 hover:text-indigo-600">
                        <i class="fas fa-user-circle"></i> <?php echo e(auth()->user()->name); ?>

                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
</body>
</html><?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>