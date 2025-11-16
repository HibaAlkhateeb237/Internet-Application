<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->group(base_path('routes/api/admin.php'));

Route::prefix('user')
    ->group(base_path('routes/api/user.php'));
