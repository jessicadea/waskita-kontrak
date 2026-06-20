<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'order_id',
        'project_name',
        'start_date',
        'due_date',
        'status',
        'progress_percent',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function assignments()
    {
        return $this->hasMany(ProjectAssignment::class);
    }

    public function workUpdates()
    {
        return $this->hasMany(EmployeeWorkUpdate::class);
    }

    public function logs()
    {
        return $this->hasMany(ProjectProgressLog::class);
    }

    public function stages()
    {
        return $this->hasMany(ProjectStage::class);
    }
}