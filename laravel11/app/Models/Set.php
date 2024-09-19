<?php

namespace App\Models;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Set extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;
    public function lessons() {
        return $this->hasMany(Lesson::class, 'set_id', 'id');
    }
}
