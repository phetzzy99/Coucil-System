<?php

namespace App\Http\Controllers;

use App\Models\MeetingAgenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

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

    public function export($id, $type)
    {
        $report = MeetingAgenda::with([
            'meeting_type',
            'committeeCategory',
            'meetingFormat',
            'sections.meetingAgendaLectures.meetingAgendaItems',
            'sections.approvalDetails', // ดึงข้อมูลการรับรองใน Section
            'adminApprovedBy'
        ])->findOrFail($id);

        if ($type == 'pdf') {
            $options = new Options();
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);

            $html = view('admin.backend.pages.reports.pdf', compact('report'))->render();
            $dompdf->loadHtml($html);

            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('report.pdf');
        } elseif ($type == 'word') {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();

            // ตั้งค่าฟอนต์เริ่มต้นและขนาดฟอนต์
            $phpWord->setDefaultFontName('TH Sarabun New');
            $phpWord->setDefaultFontSize(16);

            $section = $phpWord->addSection();

            // เพิ่มส่วนหัวของรายงาน
            $section->addText('รายงานการประชุม', ['bold' => true, 'size' => 24], ['alignment' => 'center']);
            $section->addTextBreak();

            // เพิ่ม timestamp
            $currentDate = date('d/m/Y H:i:s');
            $section->addText("วันที่สร้างรายงาน: {$currentDate}", ['size' => 16], ['alignment' => 'center']);
            $section->addTextBreak();

            // เพิ่มรายละเอียดการประชุม
            // $section->addText("ประชุมประเภท: {$report->meeting_type->name}", ['size' => 16], ['alignment' => 'center']);
            // $section->addText("หมวดหมู่คณะกรรมการ: {$report->committeeCategory->name}", ['size' => 16], ['alignment' => 'center']);
            // $section->addText("รูปแบบการประชุม: {$report->meetingFormat->name}", ['size' => 16], ['alignment' => 'center']);

            // เพิ่มหัวข้อรายงาน
            $section->addText($report->meeting_agenda_title, ['bold' => true, 'size' => 20], ['alignment' => 'center']);

            // เพิ่มคำอธิบายของรายงาน
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $report->description);

            $thaiMonths = [
                '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน',
                '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม',
                '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
            ];

            $date = strtotime($report->meeting_agenda_date);
            $day = date('d', $date);
            $month = $thaiMonths[date('m', $date)];
            $year = date('Y', $date) + 543; // Convert to Buddhist year

            $section->addText("วันที่ประชุม: {$day} {$month} {$year}", ['size' => 16], ['alignment' => 'center']);

            if ($report->meeting_agenda_number) {
                $section->addText("เลขที่ประชุม/ปีที่ประชุม: {$report->meeting_agenda_number}/{$report->meeting_agenda_year}", ['size' => 16], ['alignment' => 'center']);
            }
            if ($report->meeting_agenda_time) {
                $section->addText("เวลาที่ประชุม: " . date('H:i', strtotime($report->meeting_agenda_time)), ['size' => 16], ['alignment' => 'center']);
            }
            if ($report->meeting_location) {
                $section->addText("สถานที่ประชุม: {$report->meeting_location}", ['size' => 16], ['alignment' => 'center']);
            }
            $section->addTextBreak(2);

            foreach ($report->sections as $sectionData) {
                // เพิ่มหัวข้อของ Section
                $section->addText($sectionData->section_title, ['bold' => true, 'size' => 18]);

                if ($sectionData->description) {
                    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $sectionData->description);
                }

                foreach ($sectionData->meetingAgendaLectures as $lecture) {
                    // เพิ่มหัวข้อของ Lecture
                    $section->addText($lecture->lecture_title, ['bold' => true, 'size' => 16]);

                    if ($lecture->content) {
                        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $lecture->content);
                    }

                    foreach ($lecture->meetingAgendaItems as $item) {
                        // เพิ่มหัวข้อของ Item
                        $section->addText($item->item_title, ['bold' => true, 'size' => 14]);

                        if ($item->content) {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $item->content);
                        }
                    }
                }

                // ตรวจสอบว่ามีการรับรองใน Section นี้หรือไม่
                if ($sectionData->approvalDetails->isNotEmpty()) {
                    $section->addText('การรับรองวาระนี้', ['bold' => true, 'size' => 16, 'color' => '0000FF']);

                    foreach ($sectionData->approvalDetails as $detail) {
                        $approvalType = ($detail->approval_type == 'no_changes') ? 'รับรองโดยไม่มีแก้ไข' : 'รับรองโดยมีแก้ไข';
                        $section->addText("- {$approvalType}", ['size' => 14]);

                        // เพิ่มหมายเหตุถ้ามี
                        if ($detail->note) {
                            \PhpOffice\PhpWord\Shared\Html::addHtml($section, "<i>หมายเหตุ: {$detail->note}</i>");
                        }
                    }
                }
            }

            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $fileName = 'report_' . date('Ymd_His') . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);
            $objWriter->save($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'ประเภทการส่งออกไม่ถูกต้อง');
        }
    }
}
