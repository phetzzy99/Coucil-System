<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin Group Middleware
Route::middleware(['auth', 'roles:admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');

    // All Role & permission
    Route::get('/admin/permission', [RoleController::class, 'AllPermission'])->name('all.permission');
    Route::get('/admin/permission/add', [RoleController::class, 'AddPermission'])->name('add.permission');
    Route::post('/admin/permission/store', [RoleController::class, 'StorePermission'])->name('store.permission');
    Route::get('/admin/permission/{id}/edit', [RoleController::class, 'EditPermission'])->name('edit.permission');
    Route::post('/admin/permission/update', [RoleController::class, 'UpdatePermission'])->name('update.permission');
    Route::get('/admin/permission/{id}/delete', [RoleController::class, 'DeletePermission'])->name('delete.permission');

    // All Roles
    Route::get('/admin/role', [RoleController::class, 'AllRole'])->name('all.roles');
    Route::get('/admin/role/add', [RoleController::class, 'AddRole'])->name('add.roles');
    Route::post('/admin/role/store', [RoleController::class, 'StoreRole'])->name('store.roles');
    Route::get('/admin/role/{id}/edit', [RoleController::class, 'EditRole'])->name('edit.roles');
    Route::post('/admin/role/update', [RoleController::class, 'UpdateRole'])->name('update.roles');
    Route::get('/admin/role/{id}/delete', [RoleController::class, 'DeleteRole'])->name('delete.roles');

    Route::get('/admin/roles/permission', [RoleController::class, 'AddRolesPermission'])->name('add.roles.permission');
    Route::post('/admin/role/permission/store', [RoleController::class, 'RolePermissionStore'])->name('role.permission.store');
    Route::get('/admin/roles/permission/all', [RoleController::class, 'AllRolePermission'])->name('all.roles.permission');
    Route::get('/admin/roles/permission/edit/{id}', [RoleController::class, 'AdminEditRoles'])->name('admin.edit.roles');
    Route::post('/admin/roles/permission/update/{id}', [RoleController::class, 'AdminUpdateRoles'])->name('admin.roles.update');
    Route::get('/admin/roles/permission/delete/{id}', [RoleController::class, 'AdminDeleteRoles'])->name('admin.delete.roles');

    // Admin User All Route
    Route::get('/all/admin', [AdminController::class, 'AllAdmin'])->name('all.admin');
    Route::get('/admin/add/admin', [AdminController::class, 'AddAdmin'])->name('add.admin');
    Route::post('/admin/store/admin', [AdminController::class, 'StoreAdmin'])->name('store.admin');
    Route::get('/admin/admin/{id}/edit', [AdminController::class, 'EditAdmin'])->name('edit.admin');
    Route::post('/admin/update/{id}', [AdminController::class, 'UpdateAdmin'])->name('update.admin');
    Route::get('/delete/admin/{id}', [AdminController::class, 'DeleteAdmin'])->name('delete.admin');


}); // end of admin middleware





// User Group Middleware
Route::middleware(['auth', 'roles:user'])->group(function () {

    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

}); // end of user middleware
