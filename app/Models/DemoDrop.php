<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoDrop extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id',
        'curator_id',
        'file_path',
        'demo_title',
        'note',
        'feedback',
        'is_checked',
    ];
    

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function curator()
    {
        return $this->belongsTo(User::class, 'curator_id');
    }
}
