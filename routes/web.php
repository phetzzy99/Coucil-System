<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApprovedMeetingReportController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Category\CommitteeCategoryController;
use App\Http\Controllers\Category\MeetingTypeController;
use App\Http\Controllers\Category\RegulationCategoryController;
use App\Http\Controllers\Category\RuleCategoryController;
use App\Http\Controllers\CommitteeOpinionController;
use App\Http\Controllers\CommitteeVoteController;
use App\Http\Controllers\LegalDatabaseController;
use App\Http\Controllers\MainMeetingController;
use App\Http\Controllers\ManagementCategoryController;
use App\Http\Controllers\ManagementKeywordController;
use App\Http\Controllers\MeetingAgendaController;
use App\Http\Controllers\MeetingApprovalController;
use App\Http\Controllers\MeetingApprovalListController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MeetingFormatController;
use App\Http\Controllers\MeetingReportController;
use App\Http\Controllers\MeetingReportSummaryController;
use App\Http\Controllers\MeetingResolutionController;
use App\Http\Controllers\MeetingResolutionTypeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PrefixNameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegulationMeetingController;
use App\Http\Controllers\RuleMeetingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\Role;
use App\Models\Meeting;
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
    return redirect('/admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin Group Middleware
Route::middleware(['auth', 'roles:admin'])->group(function () {

    // Notification Route
    Route::get('/notifications', function() {
        $notifications = auth()->user()->unreadNotifications;
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json($notifications);
    });

    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');

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
    Route::get('/admin/roles/permission/all', [RoleController::class, 'AllRolePermission'])->name('all.roles.permission')->middleware('permission:All.User.Menu');
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

    // General User Update
    Route::get('/profile/edit', [UserProfileController::class, 'editProfile'])->name('user.profile.edit');
    Route::post('/profile/update', [UserProfileController::class, 'updateProfile'])->name('user.profile.update');

    // Prefix Name Route
    Route::get('/all/prefix-name', [PrefixNameController::class, 'AllPrefixName'])->name('all.prefix.name');
    Route::get('/add/prefix-name', [PrefixNameController::class, 'AddPrefixName'])->name('add.prefix.name');
    Route::post('/store/prefix-name', [PrefixNameController::class, 'StorePrefixName'])->name('store.prefix.name');
    Route::get('/edit/prefix-name/{id}', [PrefixNameController::class, 'EditPrefixName'])->name('edit.prefix.name');
    Route::post('/update/prefix-name/{id}', [PrefixNameController::class, 'UpdatePrefixName'])->name('update.prefix.name');
    Route::get('/delete/prefix-name/{id}', [PrefixNameController::class, 'DeletePrefixName'])->name('delete.prefix.name');

    // Position Route
    Route::get('/all/position', [PositionController::class, 'AllPosition'])->name('all.position');
    Route::get('/add/position', [PositionController::class, 'AddPosition'])->name('add.position');
    Route::post('/store/position', [PositionController::class, 'StorePosition'])->name('store.position');
    Route::get('/edit/position/{id}', [PositionController::class, 'EditPosition'])->name('edit.position');
    Route::post('/update/position/{id}', [PositionController::class, 'UpdatePosition'])->name('update.position');
    Route::get('/delete/position/{id}', [PositionController::class, 'DeletePosition'])->name('delete.position');

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

    // Main Meeting Route list
    Route::controller(MainMeetingController::class)->group(function () {
        Route::get('/all/main-meeting', 'AllMainMeeting')->name('all.main.meeting');
        Route::get('/add/main-meeting', 'AddMainMeeting')->name('add.main.meeting');
        Route::post('/store/main-meeting', 'StoreMainMeeting')->name('store.main.meeting');
        Route::get('/edit/main-meeting/{id}', 'EditMainMeeting')->name('edit.main.meeting');
        Route::post('/update/main-meeting/{id}', 'UpdateMainMeeting')->name('update.main.meeting');
        Route::get('/delete/main-meeting/{id}', 'DeleteMainMeeting')->name('delete.main.meeting');
    });

    // Meeting Format route list
    Route::controller(MeetingFormatController::class)->group(function () {
        Route::get('/all/meeting-format', 'AllMeetingFormat')->name('all.meeting.format');
        Route::get('/add/meeting-format', 'AddMeetingFormat')->name('add.meeting.format');
        Route::post('/store/meeting-format', 'StoreMeetingFormat')->name('store.meeting.format');
        Route::get('/edit/meeting-format/{id}', 'EditMeetingFormat')->name('edit.meeting.format');
        Route::post('/update/meeting-format', 'UpdateMeetingFormat')->name('update.meeting.format');
        Route::get('/delete/meeting-format/{id}', 'DeleteMeetingFormat')->name('delete.meeting.format');
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
        Route::post('/update/meeting-report/{id}', 'UpdateMeetingReport')->name('update.meeting.report');
        Route::get('/delete/meeting-report/{id}', 'DeleteMeetingReport')->name('delete.meeting.report');
    });

    // Meeting agenda route list (วาระการประชุม)
    Route::controller(MeetingAgendaController::class)->group(function () {
        Route::get('/all/meeting-agenda', 'AllMeetingAgenda')->name('all.meeting.agenda');
        Route::get('/add/meeting-agenda', 'AddMeetingAgenda')->name('add.meeting.agenda');
        Route::post('/store/meeting-agenda', 'StoreMeetingAgenda')->name('store.meeting.agenda');
        Route::get('/edit/meeting-agenda/{id}', 'EditMeetingAgenda')->name('edit.meeting.agenda');
        Route::post('/update/meeting-agenda/', 'UpdateMeetingAgenda')->name('update.meeting.agenda');

        Route::post('/update/status/meeting-agenda/{id}', 'UpdateStatusMeetingAgenda')->name('update.status.meeting.agenda');

        Route::get('/delete/meeting-agenda/{id}', 'DeleteMeetingAgenda')->name('delete.meeting.agenda');

        // Meeting Section and Lecture route list (หัวข้อวาระการประชุม และวาระการประชุมย่อย)
        Route::get('/add/meeting/agenda/lecture/{id}', 'AddMeetingAgendaLecture')->name('add.meeting.agenda.lecture');
        Route::post('/add/meeting/agenda/section', 'AddMeetingAgendaSection')->name('add.meeting.agenda.section');

        Route::get('/edit/meeting/agenda/section/{id}',[MeetingAgendaController::class, 'EditMeetingAgendaSection'])->name('edit.meeting.agenda.section');
        Route::post('/update/meeting/agenda/section/{id}',[MeetingAgendaController::class, 'UpdateMeetingAgendaSection'])->name('update.meeting.agenda.section');


        Route::post('/save/meeting/agenda/lecture/',[MeetingAgendaController::class, 'SaveMeetingAgendaLecture'])->name('save.meeting.agenda.lecture');
        Route::get('/edit/meeting/agenda/lecture/{id}',[MeetingAgendaController::class, 'EditMeetingAgendaLecture'])->name('edit.meeting.agenda.lecture');
        Route::post('/update/meeting/agenda/lecture',[MeetingAgendaController::class, 'UpdateMeetingAgendaLecture'])->name('update.meeting.agenda.lecture');
        Route::delete('/delete/meeting/agenda/section/{id}',[MeetingAgendaController::class, 'DeleteMeetingAgendaSection'])->name('delete.meeting.agenda.section');
        Route::get('/delete/meeting/agenda/lecture/{id}',[MeetingAgendaController::class, 'DeleteMeetingAgendaLecture'])->name('delete.meeting.agenda.lecture'); // ลบหัวข้อวาระการประชุม Lecture_id

        Route::post('/save/meeting/agenda/item', [MeetingAgendaController::class, 'SaveMeetingAgendaItem'])->name('save.meeting.agenda.item');
        // Route::get('/edit/meeting/agenda/item/{id}', [MeetingAgendaController::class, 'EditMeetingAgendaItem'])->name('edit.meeting.agenda.item');
        Route::get('/get-agenda-items/{lecture_id}', [MeetingAgendaController::class, 'GetAgendaItem'])->name('get.agenda.item');  // แสดงหัวข้อวาระการประชุม และวาระการประชุมย่อย
        Route::get('/edit/get-agenda-items/{itemId}', [MeetingAgendaController::class, 'getAgendaItems'])->name('edit.get.agenda.item'); // แสดงหัวข้อวาระการประชุม สำหรับแก้ไข
        Route::post('/update-agenda-item/{itemId}', [MeetingAgendaController::class, 'UpdateAgendaItem'])->name('update.agenda.item');
        Route::delete('/delete-agenda-item/{id}', [MeetingAgendaController::class, 'DeleteAgendaItem'])->name('delete.agenda.item');
    });

    // Meeting route list
    Route::controller(MeetingController::class)->group(function () {
        Route::get('/all/meeting', 'AllMeeting')->name('all.meeting');
        Route::get('/add/meeting', 'AddMeeting')->name('add.meeting');
        Route::post('/store/meeting', 'StoreMeeting')->name('store.meeting');
        Route::get('/edit/meeting/{id}', 'EditMeeting')->name('edit.meeting');
        Route::post('/update/meeting', 'UpdateMeeting')->name('update.meeting');
        Route::get('/delete/meeting/{id}', 'DeleteMeeting')->name('delete.meeting');
    });

    // Show Meeting section and lecture
    Route::get('/my/meetings', [MeetingController::class, 'MyMeetings'])->name('my.meetings');
    Route::get('/meeting/details/{id}', [MeetingController::class, 'MeetingDetails'])->name('meeting.detail');
    Route::get('/meeting/section/detail/{id}', [MeetingController::class, 'sectionAgendaItemDetail'])->name('meeting.section.detail');
    Route::get('/meeting/lecture/detail/{id}', [MeetingController::class, 'lectureSectionDetail'])->name('meeting.lecture.detail');
    // Route::get('/show/meeting/{id}', [MeetingController::class, 'ShowMeeting'])->name('show.meeting');

    // Meeting Approval route list
    Route::get('/all/meeting-approval', [MeetingApprovalController::class, 'AllMeetingApproval'])->name('all.meeting.approval');
    Route::get('/meeting/approval/detail/{id}', [MeetingApprovalController::class, 'MeetingApprovalDetail'])->name('meeting.approval.detail');

    // Routes สำหรับการรับรองรายงานการประชุม
    // Route::get('/meeting-approval/{id}', [MeetingApprovalController::class, 'show'])->name('meeting.approval.show'); //** */
    Route::post('/meeting-approval/{id}', [MeetingApprovalController::class, 'store'])->name('meeting.approval.store');
    // Route::get('/meeting-approvals', [MeetingApprovalController::class, 'index'])->name('meeting.approvals.index');
    Route::get('/meeting-approval-details/{id}', [MeetingApprovalController::class, 'getApprovalDetails'])->name('meeting.approval.details');
    // Route::get('/meeting-approval/{id}/data', [MeetingApprovalController::class, 'getApprovalData'])->name('meeting.approval.data');
    Route::post('/meeting-approval/{id}/update', [MeetingApprovalController::class, 'updateApproval'])->name('meeting.approval.update');
    Route::get('/meeting-approval/edit/{id}', [MeetingApprovalController::class, 'editApproval'])->name('meeting.approval.edit');

    // Routes สำหรับการรับรองรายงานการประชุม
    Route::get('/meeting/list/approval/{meeting_type_id}/{committee_id}', [MeetingApprovalListController::class, 'list'])->name('meeting.list.approval'); //** */

    // Settings route list
    Route::get('/settings/approval-deadline', [SettingsController::class, 'editApprovalDeadline'])->name('settings.edit_approval_deadline');
    Route::post('/settings/approval-deadline', [SettingsController::class, 'updateApprovalDeadline'])->name('settings.update_approval_deadline');

    // Meeting Report Summary route list
    // Route::get('/meeting/report/summary', [MeetingReportSummaryController::class, 'index'])->name('meeting.report.summary.index');
    // Route::get('/meeting/report/summary', [MeetingReportSummaryController::class, 'allReportSummary'])->name('meeting.report.summary.index');
    Route::get('/meeting/report/summary', [MeetingReportSummaryController::class, 'index'])->name('meeting.report.summary.index');
    Route::get('/meeting/report/summary/{id}', [MeetingReportSummaryController::class, 'showSummary'])->name('meeting.report.summary');
    Route::get('/meeting/report/summary/{id}/edit', [MeetingReportSummaryController::class, 'edit'])->name('meeting.report.summary.edit');
    Route::post('/meeting/report/summary/{id}/update', [MeetingReportSummaryController::class, 'update'])->name('meeting.report.summary.update');
    Route::post('/meeting/report/{id}/admin-approve', [MeetingReportSummaryController::class, 'adminApprove']);
    Route::post('/meeting/report/{id}/admin-cancel', [MeetingReportSummaryController::class, 'adminCancel']);

    Route::get('/approved-meeting-reports', [ApprovedMeetingReportController::class, 'allApprovedByAdmin'])->name('all.approved.meeting.reports');
    Route::get('/approved-meeting-reports/{id}', [ApprovedMeetingReportController::class, 'ListApprovedByAdmin'])->name('list.approved.meeting.reports');

    // Export Report after approval
    Route::get('/reports/{id}/export/{type}', [ApprovedMeetingReportController::class, 'export'])->name('reports.export');

    // Export Report meeting agenda
    Route::get('/meeting/export/{id}', [MeetingController::class, 'exportToWord'])->name('meeting.export.word');

    // บันทึกความเห็นคณะกรรมการ
    Route::post('/committee/feedback/store', [CommitteeOpinionController::class, 'store'])->name('committee.feedback.store');
    Route::get('/committee/feedback/{lectureId}', [CommitteeOpinionController::class, 'getOpinions'])->name('committee.feedback.get');
    Route::get('/committee/feedback/{lectureId}/current', [CommitteeOpinionController::class, 'getCurrentUserOpinion'])->name('committee.feedback.current');

    // อัพเดทสถานะการประชุมในส่วนของ Section
    Route::post('/update-meeting-status/{id}', [MeetingAgendaController::class, 'updateStatus'])->name('update.meeting.status');

    // Committee Opinion Visibility Update
    Route::post('/update-committee-opinion-visibility/{id}', [MeetingController::class, 'updateCommitteeOpinionVisibility'])->name('update.committee.opinion.visibility');

    // Show Meeting section and lecture
    Route::get('/my/meetings', [MeetingController::class, 'MyMeetings'])->name('my.meetings');
    Route::get('/meeting/type/{meeting_type_id?}', [MeetingController::class, 'ViewMeetingType'])->name('meeting.type.view');
    Route::get('/meeting/details/{id}', [MeetingController::class, 'MeetingDetails'])->name('meeting.detail');

    // Legal Database view
    Route::get('/admin/legal-database', [LegalDatabaseController::class, 'index'])->name('admin.legal.database');

    // Meeting Resolution route list
    Route::get('/all/meeting/resolution', [MeetingResolutionController::class, 'AllMeetingResolution'])->name('all.meeting.resolution');
    Route::get('/add/meeting/resolution', [MeetingResolutionController::class, 'AddMeetingResolution'])->name('add.meeting.resolution');
    Route::post('/store/meeting/resolution', [MeetingResolutionController::class, 'StoreMeetingResolution'])->name('store.meeting.resolution');
    Route::get('/edit/meeting/resolution/{id}', [MeetingResolutionController::class, 'EditMeetingResolution'])->name('edit.meeting.resolution');
    Route::post('/update/meeting/resolution', [MeetingResolutionController::class, 'UpdateMeetingResolution'])->name('update.meeting.resolution');
    Route::get('/delete/meeting/resolution/{id}', [MeetingResolutionController::class, 'DeleteMeetingResolution'])->name('delete.meeting.resolution');

    // Search Meeting Resolution
    Route::get('/search/meeting/resolution', [MeetingResolutionController::class, 'SearchMeetingResolution'])->name('search.meeting.resolution');
    Route::get('/search/meeting/resolution/results', [MeetingResolutionController::class, 'SearchMeetingResolutionResults'])->name('search.meeting.resolution.results');

    // Ajax Routes for Dependent Dropdowns
    Route::get('/get-meeting-agenda-lectures/{section_id}', [MeetingResolutionController::class, 'GetMeetingAgendaLectures']);
    Route::get('/get/meeting/agendas', [MeetingResolutionController::class, 'GetMeetingAgendas'])->name('get.meeting.agendas');
    Route::get('/get/meeting/sections', [MeetingResolutionController::class, 'GetMeetingSections'])->name('get.meeting.sections');
    Route::get('/get/meeting/lectures', [MeetingResolutionController::class, 'GetMeetingAgendaLectures'])->name('get.meeting.lectures');

    // add 05/05/2025
    // Meeting Resolution Type route list
    Route::controller(MeetingResolutionTypeController::class)->group(function () {
        Route::get('/all/meeting-resolution-type', 'AllMeetingResolutionTypes')->name('all.meeting.resolution.types');
        Route::get('/add/meeting-resolution-type', 'AddMeetingResolutionType')->name('add.meeting.resolution.type');
        Route::post('/store/meeting-resolution-type', 'StoreMeetingResolutionType')->name('store.meeting.resolution.type');
        Route::get('/edit/meeting-resolution-type/{id}', 'EditMeetingResolutionType')->name('edit.meeting.resolution.type');
        Route::post('/update/meeting-resolution-type', 'UpdateMeetingResolutionType')->name('update.meeting.resolution.type');
        Route::get('/delete/meeting-resolution-type/{id}', 'DeleteMeetingResolutionType')->name('delete.meeting.resolution.type');

        // AJAX Routes
        Route::get('/get/meeting-resolution-types', 'GetMeetingResolutionTypes')->name('get.meeting.resolution.types');
    });

    // เส้นทางสำหรับค้นหามติที่ประชุม
    Route::get('/meeting/resolution/types/search', [MeetingResolutionTypeController::class, 'SearchMeetingResolutionTypes'])->name('search.meeting.resolution.types');

    // Management Category Routes
    Route::controller(ManagementCategoryController::class)->group(function () {
        Route::get('/all/management/categories', 'AllManagementCategories')->name('all.management.categories');
        Route::get('/add/management/category', 'AddManagementCategory')->name('add.management.category');
        Route::post('/store/management/category', 'StoreManagementCategory')->name('store.management.category');
        Route::get('/edit/management/category/{id}', 'EditManagementCategory')->name('edit.management.category');
        Route::post('/update/management/category', 'UpdateManagementCategory')->name('update.management.category');
        Route::get('/delete/management/category/{id}', 'DeleteManagementCategory')->name('delete.management.category');
    });

    // Management Keyword Routes
    Route::controller(ManagementKeywordController::class)->group(function () {
        Route::get('/all/management/keywords', 'AllManagementKeywords')->name('all.management.keywords');
        Route::get('/add/management/keyword', 'AddManagementKeyword')->name('add.management.keyword');
        Route::post('/store/management/keyword', 'StoreManagementKeyword')->name('store.management.keyword');
        Route::get('/edit/management/keyword/{id}', 'EditManagementKeyword')->name('edit.management.keyword');
        Route::post('/update/management/keyword', 'UpdateManagementKeyword')->name('update.management.keyword');
        Route::get('/delete/management/keyword/{id}', 'DeleteManagementKeyword')->name('delete.management.keyword');
    });

}); // end of admin middleware


Route::get('/user/login', [AdminController::class, 'UserLogin'])->name('user.login')->middleware(RedirectIfAuthenticated::class);



// User Group Middleware
Route::middleware(['auth', 'roles:user'])->group(function () {

    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

}); // end of user middleware
