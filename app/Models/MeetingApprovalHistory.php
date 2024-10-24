<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_agenda_id',
        'user_id',
        'action',
        'note',
        'action_at'
    ];

    protected $dates = [
        'action_at',
        'created_at',
        'updated_at'
    ];

    // Action Constants
    const ACTION_APPROVE = 'approve';
    const ACTION_CANCEL = 'cancel';

    /**
     * เชื่อมความสัมพันธ์กับ MeetingAgenda
     */
    public function meetingAgenda()
    {
        return $this->belongsTo(MeetingAgenda::class);
    }

    /**
     * เชื่อมความสัมพันธ์กับ User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope สำหรับกรองตาม action
     */
    public function scopeApprovals($query)
    {
        return $query->where('action', self::ACTION_APPROVE);
    }

    public function scopeCancellations($query)
    {
        return $query->where('action', self::ACTION_CANCEL);
    }

    /**
     * Scope สำหรับกรองตามช่วงเวลา
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('action_at', [$startDate, $endDate]);
    }

    /**
     * Accessor สำหรับแสดงข้อความ action ภาษาไทย
     */
    public function getActionTextAttribute()
    {
        return [
            self::ACTION_APPROVE => 'รับรอง',
            self::ACTION_CANCEL => 'ยกเลิกการรับรอง'
        ][$this->action] ?? $this->action;
    }

    /**
     * Accessor สำหรับแสดงสีของ action
     */
    public function getActionColorAttribute()
    {
        return [
            self::ACTION_APPROVE => 'success',
            self::ACTION_CANCEL => 'danger'
        ][$this->action] ?? 'secondary';
    }

    /**
     * สร้างประวัติการรับรอง
     */
    public static function recordApproval($meetingAgendaId, $userId, $note = null)
    {
        return self::create([
            'meeting_agenda_id' => $meetingAgendaId,
            'user_id' => $userId,
            'action' => self::ACTION_APPROVE,
            'note' => $note,
            'action_at' => now()
        ]);
    }

    /**
     * ดึงประวัติล่าสุดของการประชุม
     */
    public static function getLatestHistory($meetingAgendaId)
    {
        return self::where('meeting_agenda_id', $meetingAgendaId)
            ->latest('action_at')
            ->first();
    }

    /**
     * ดึงรายการประวัติทั้งหมดของการประชุม
     */
    public static function getAllHistory($meetingAgendaId)
    {
        return self::where('meeting_agenda_id', $meetingAgendaId)
            ->with(['user'])
            ->orderBy('action_at', 'desc')
            ->get();
    }

    /**
     * ตรวจสอบว่าเป็นการรับรองหรือไม่
     */
    public function isApproval()
    {
        return $this->action === self::ACTION_APPROVE;
    }

    /**
     * ตรวจสอบว่าเป็นการยกเลิกหรือไม่
     */
    public function isCancellation()
    {
        return $this->action === self::ACTION_CANCEL;
    }

    /**
     * แสดงข้อความสรุปการดำเนินการ
     */
    public function getSummaryText()
    {
        return sprintf(
            '%s โดย %s เมื่อ %s %s',
            $this->action_text,
            $this->user->name,
            $this->action_at->format('d/m/Y H:i'),
            $this->note ? "($this->note)" : ''
        );
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set default values when creating
        static::creating(function ($history) {
            if (!$history->action_at) {
                $history->action_at = now();
            }
        });
    }
}
