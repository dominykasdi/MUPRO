<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enrollment;
use App\Models\User;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('order');
    }

    public function curator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function completedStepsForLearner($userId)
    {
        return $this->learnerSteps()->where('user_id', $userId)->where('completed', true)->count();
    }

    public function getTotalStepsAttribute()
    {
        return $this->steps()->count();
    }

    public function enrolledLearners()
    {
        return $this->hasManyThrough(
            User::class,
            Enrollment::class,
            'course_id', // Foreign key on enrollments table
            'id', // Foreign key on users table
            'id', // Local key on courses table
            'user_id' // Local key on enrollments table
        );
    }

    public function inProgressLearners()
    {
        return $this->enrollments()->where('completed', false)->count();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'course_tag');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
