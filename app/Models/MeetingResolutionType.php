<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingResolutionType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = [
        'meeting_date',
        'created_at',
        'updated_at',
    ];

    // ความสัมพันธ์กับตาราง committee_categories (สำหรับข้อมูลเก่า)
    public function committeeCategory()
    {
        return $this->belongsTo(CommitteeCategory::class, 'committee_category_id', 'id');
    }

    // ความสัมพันธ์กับตาราง management_categories
    public function managementCategory()
    {
        return $this->belongsTo(ManagementCategory::class, 'management_category_id', 'id');
    }

    // ความสัมพันธ์กับตาราง management_keywords
    public function managementKeyword()
    {
        return $this->belongsTo(ManagementKeyword::class, 'management_keyword_id', 'id');
    }

    // ความสัมพันธ์กับตาราง meeting_types
    public function meetingType()
    {
        return $this->belongsTo(MeetingType::class, 'meeting_type_id', 'id');
    }
}
