<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectProgressLog extends Model
{
    protected $fillable = [
        'project_id',
        'work_update_id',
        'progress_percent',
        'note',
        'updated_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function workUpdate()
    {
        return $this->belongsTo(EmployeeWorkUpdate::class, 'work_update_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}