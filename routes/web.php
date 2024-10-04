<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Category\CommitteeCategoryController;
use App\Http\Controllers\Category\MeetingTypeController;
use App\Http\Controllers\Category\RegulationCategoryController;
use App\Http\Controllers\Category\RuleCategoryController;
use App\Http\Controllers\MeetingReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegulationMeetingController;
use App\Http\Controllers\RuleMeetingController;
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


    // Rules Category
    Route::controller(RuleCategoryController::class)->group(function () {
        Route::get('/all/rules-category', 'AllRuleCategory')->name('rule.category');
        Route::get('/add/rules-category', 'AddRuleCategory')->name('add.rule.category');
        Route::post('/store/rule-category', 'StoreRulesCategory')->name('store.rule.category');
        Route::get('/edit/rule-category/{id}', 'EditRulesCategory')->name('edit.rule.category');
        Route::post('/update/rule-category', 'UpdateRulesCategory')->name('update.rule.category');
        Route::get('/delete/rule-category/{id}', 'DeleteRulesCategory')->name('delete.rule.category');
    });

    // Regulation Category
    Route::controller(RegulationCategoryController::class)->group(function () {
        Route::get('/all/regulation-category', 'AllRegulationCategory')->name('all.regulation.category');
        Route::get('/add/regulation-category', 'AddRegulationCategory')->name('add.regulation.category');
        Route::post('/store/regulation-category', 'StoreRegulationCategory')->name('store.regulation.category');
        Route::get('/edit/regulation-category/{id}', 'EditRegulationCategory')->name('edit.regulation.category');
        Route::post('/update/regulation-category', 'UpdateRegulationCategory')->name('update.regulation.category');
        Route::get('/delete/regulation-category/{id}', 'DeleteRegulationCategory')->name('delete.regulation.category');
    });

    // Rules of Meeting
    Route::controller(RuleMeetingController::class)->group(function () {
        Route::get('/all/rules-of-meeting', 'AllRulesOfMeeting')->name('all.rule.meeting');
        Route::get('/add/rules-of-meeting', 'AddRulesOfMeeting')->name('add.rule.meeting');
        Route::post('/store/rules-of-meeting', 'StoreRulesOfMeeting')->name('store.rule.meeting');
        Route::get('/edit/rules-of-meeting/{id}', 'EditRulesOfMeeting')->name('edit.rule.meeting');
        Route::post('/update/rules-of-meeting', 'UpdateRulesOfMeeting')->name('update.rule.meeting');

        Route::post('/update/status/rule-meeting/{id}', 'UpdateStatusRulesOfMeeting')->name('update.status.rule.meeting');

        Route::get('/delete/rules-of-meeting/{id}', 'DeleteRulesOfMeeting')->name('delete.rule.meeting');
    });

    // Regulation of Meeting
    Route::controller(RegulationMeetingController::class)->group(function () {
        Route::get('/all/regulation-of-meeting', 'AllRegulationOfMeeting')->name('all.regulation.meeting');
        Route::get('/add/regulation-of-meeting', 'AddRegulationOfMeeting')->name('add.regulation.meeting');
        Route::post('/store/regulation-of-meeting', 'StoreRegulationOfMeeting')->name('store.regulation.meeting');
        Route::get('/edit/regulation-of-meeting/{id}', 'EditRegulationOfMeeting')->name('edit.regulation.meeting');
        Route::post('/update/regulation-of-meeting', 'UpdateRegulationOfMeeting')->name('update.regulation.meeting');

        Route::post('/update/status/regulation-meeting/{id}', 'UpdateStatusRegulationMeeting')->name('update.status.regulation.meeting');

        Route::get('/delete/regulation-of-meeting/{id}', 'DeleteRegulationOfMeeting')->name('delete.regulation.meeting');
    });

    // Committee route list
    Route::controller(CommitteeCategoryController::class)->group(function () {
        Route::get('/all/committee-category', 'AllCommitteeCategory')->name('all.committee.category');
        Route::get('/add/committee-category', 'AddCommitteeCategory')->name('add.committee.category');
        Route::post('/store/committee-category', 'StoreCommitteeCategory')->name('store.committee.category');
        Route::get('/edit/committee-category/{id}', 'EditCommitteeCategory')->name('edit.committee.category');
        Route::post('/update/committee-category', 'UpdateCommitteeCategory')->name('update.committee.category');
        Route::get('/delete/committee-category/{id}', 'DeleteCommitteeCategory')->name('delete.committee.category');
    });

    // Meeting Type route list
    Route::controller(MeetingTypeController::class)->group(function () {
        Route::get('/all/meeting-type', 'AllMeetingType')->name('all.meeting.type');
        Route::get('/add/meeting-type', 'AddMeetingType')->name('add.meeting.type');
        Route::post('/store/meeting-type', 'StoreMeetingType')->name('store.meeting.type');
        Route::get('/edit/meeting-type/{id}', 'EditMeetingType')->name('edit.meeting.type');
        Route::post('/update/meeting-type', 'UpdateMeetingType')->name('update.meeting.type');
        Route::get('/delete/meeting-type/{id}', 'DeleteMeetingType')->name('delete.meeting.type');
    });

    // Meeting_report
    Route::controller(MeetingReportController::class)->group(function () {
        Route::get('/all/meeting-report', 'AllMeetingReport')->name('all.meeting.report');
        Route::get('/add/meeting-report', 'AddMeetingReport')->name('add.meeting.report');
        Route::post('/store/meeting-report', 'StoreMeetingReport')->name('store.meeting.report');
        Route::get('/edit/meeting-report/{id}', 'EditMeetingReport')->name('edit.meeting.report');
        Route::post('/update/meeting-report', 'UpdateMeetingReport')->name('update.meeting.report');
        Route::get('/delete/meeting-report/{id}', 'DeleteMeetingReport')->name('delete.meeting.report');
    });


}); // end of admin middleware





// User Group Middleware
Route::middleware(['auth', 'roles:user'])->group(function () {

    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

}); // end of user middleware
