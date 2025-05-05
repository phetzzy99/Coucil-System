<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'management_category_id',
        'keyword_title',
        'description',
    ];

    public function managementCategory()
    {
        return $this->belongsTo(ManagementCategory::class);
    }
}
