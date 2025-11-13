<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','start_date','end_date','created_by'];

    public function creator(){ return $this->belongsTo(User::class,'created_by'); }
    public function tasks(){ return $this->hasMany(Task::class); }
}
