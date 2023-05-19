<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if ($user->hasRole('curator')) {
                DB::table('curators')->insert(['user_id' => $user->id]);
            }

            if ($user->hasRole('learner')) {
                DB::table('learners')->insert(['user_id' => $user->id]);
            }
        });
    }

    public function getRoleIdAttribute()
    {
        return optional($this->roles->first())->id;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function submittedDemoDrops()
    {
        return $this->hasMany(DemoDrop::class, 'learner_id');
    }

    public function reviewedDemoDrops()
    {
        return $this->hasMany(DemoDrop::class, 'curator_id');
    }
}
