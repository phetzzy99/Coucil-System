<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_code',
        'name',
        'description',
    ];

    public function managementKeywords()
    {
        return $this->hasMany(ManagementKeyword::class);
    }
}
