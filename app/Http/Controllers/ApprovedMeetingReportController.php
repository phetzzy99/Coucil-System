<?php

namespace App\Http\Controllers;

use App\Models\MeetingAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovedMeetingReportController extends Controller
{
    public function allApprovedByAdmin()
    {
        $user = Auth::user();
        $userMeetingTypes = $user->meetingTypes;

        // ดึง committee ids ที่ user มีสิทธิ์
        $userCommitteeIds = [];
        foreach ($userMeetingTypes as $meetingType) {
            $committeeIds = json_decode($meetingType->pivot->committee_ids, true);
            if ($committeeIds) {
                $userCommitteeIds = array_merge($userCommitteeIds, $committeeIds);
            }
        }
        $userCommitteeIds = array_unique($userCommitteeIds);

        // ดึงรายงานที่รับรองแล้วตามสิทธิ์
        $approvedReports = MeetingAgenda::with([
            'meeting_type',
            'committeeCategory',
            'adminApprovedBy',
            'sections'
        ])
            ->where('is_admin_approved', true)
            ->whereIn('meeting_type_id', $userMeetingTypes->pluck('id'))
            ->whereIn('committee_category_id', $userCommitteeIds)
            ->orderBy('admin_approved_at', 'desc')
            ->paginate(10);

        return view(
            'admin.backend.pages.approved_reports_by_admin.all_approved_report_by_admin',
            compact('approvedReports')
        );

        // return view('admin.backend.pages.approved_reports_by_admin.all_approved_report_by_admin', compact('approvedReports'));
    }

    public function ListApprovedByAdmin($id)
    {
        $user = Auth::user();
        $userMeetingTypes = $user->meetingTypes->pluck('id');

        // ดึง committee ids ที่ user มีสิทธิ์
        $userCommitteeIds = [];
        foreach ($user->meetingTypes as $meetingType) {
            $committeeIds = json_decode($meetingType->pivot->committee_ids, true);
            if ($committeeIds) {
                $userCommitteeIds = array_merge($userCommitteeIds, $committeeIds);
            }
        }

        $report = MeetingAgenda::with([
            'meeting_type',
            'committeeCategory',
            'meetingFormat',
            'sections.meetingAgendaLectures.meetingAgendaItems',
            'adminApprovedBy'
        ])
            ->where('is_admin_approved', true)
            ->where(function ($query) use ($userMeetingTypes, $userCommitteeIds) {
                $query->whereIn('meeting_type_id', $userMeetingTypes)
                    ->whereIn('committee_category_id', $userCommitteeIds);
            })
            ->findOrFail($id);

        return view(
            'admin.backend.pages.approved_reports_by_admin.list_approved_report_by_admin',
            compact('report')
        );

        // return view('admin.backend.pages.approved_reports_by_admin.list_approved_report_by_admin', compact('report'));
    }
}
