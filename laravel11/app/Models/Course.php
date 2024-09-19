<?php

namespace App\Models;

use App\Models\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function sets() {
        return $this->hasMany(Set::class, 'course_id', 'id');
    }
}
