<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\MeetingAgenda;
use Illuminate\Support\Facades\Auth;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('admin.body.sidebar', function ($view) {
            $user = Auth::user();
            if ($user) {
                // ดึงข้อมูลรายงานการประชุมที่เกี่ยวข้องกับผู้ใช้
                $meetings = MeetingAgenda::whereHas('approvals', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('status', 1)
                ->orderBy('meeting_agenda_date', 'desc')
                ->take(5) // แสดง 5 รายการล่าสุด
                ->get();

                // นับจำนวนรายงานที่รอการรับรอง
                $pendingReportsCount = MeetingAgenda::whereHas('approvals', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                        //   ->where('is_approved', false);
                })->count();

                $view->with([
                    'recentMeetings' => $meetings,
                    'pendingReportsCount' => $pendingReportsCount
                ]);
            }
        });
    }
}
