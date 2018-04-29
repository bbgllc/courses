<?php

Breadcrumbs::register('admin.dashboard', function ($breadcrumbs) {
    $breadcrumbs->push(__('strings.backend.dashboard.title'), route('admin.dashboard'));
});

Breadcrumbs::register('admin.course.categories', function ($breadcrumbs) {
    $breadcrumbs->push('Course categories', route('admin.course.categories'));
});

Breadcrumbs::register('admin.course.courses', function ($breadcrumbs) {
    $breadcrumbs->push('Courses', route('admin.course.courses'));
});


require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
