<?php

namespace App\Models;

use App\Models\LessonContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;
    public function contents() {
        return $this->hasMany(LessonContent::class, 'lesson_id', 'id');
    }
}
