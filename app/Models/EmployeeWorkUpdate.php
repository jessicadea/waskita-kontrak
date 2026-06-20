<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeWorkUpdate extends Model
{
    protected $fillable = [
        'project_id',
        'project_stage_id',
        'employee_id',
        'progress_percent',
        'work_note',
        'documentation_file',
        'validation_status',
        'validated_by',
        'validation_note',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function stage()
    {
        return $this->belongsTo(ProjectStage::class, 'project_stage_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}