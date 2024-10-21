<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    public $guard_name = 'web';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // public $guard_name = 'web';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getPermissionGroups()
    {
        $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        return $permission_groups;
    }

    public static function getPermissionsByGroupName($group_name)
    {
        $permissions = DB::table('permissions')->select('name', 'id')->where('group_name', $group_name)->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;

        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                break;
            }
        }
        return $hasPermission;
    }

    public function committees()
    {
        return $this->belongsToMany(CommitteeCategory::class);
        // return $this->belongsTo(CommitteeCategory::class, 'committee_id', 'id');
    }

    public function meetingTypes()
    {
        return $this->belongsToMany(MeetingType::class)
            ->withPivot('committee_ids')
            ->withTimestamps();
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function meetingFormat()
    {
        return $this->belongsTo(MeetingFormat::class);
    }

    public function prefixName()
    {
        return $this->belongsTo(PrefixName::class, 'prefix_name_id', 'id');
    }

    // User Active Now
    public function UserOnline()
    {
        return Cache::has('user-is-online' . $this->id);
    }

}
