<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class MaintenanceOrder extends Model
{

    use HasFactory;
    protected $fillable = [
        'working_report_id',
        'master_machine_id',
        'category',          // planned | unplanned
        'title',
        'problem_note',
        'component_lv1',
        'component_lv2',
        'location',
        'trouble_at',
        'assigned_to',
        'plan_start_at',
        'action_plan',
        'repair_start_at',
        'repair_action',
        'repair_done_at',
        'repair_result',
        'attachment_path',
    ];

    protected $casts = [
        'trouble_at'     => 'datetime',
        'plan_start_at'  => 'datetime',
        'repair_start_at'=> 'datetime',
        'repair_done_at' => 'datetime',
    ];

    public function workingReport()
    {
        return $this->belongsTo(WorkingReport::class);
    }

    public function machine()
    {
        return $this->belongsTo(MasterMachine::class, 'master_machine_id');
    }

    public function scopeByReport($q, $reportId)
    {
        return $q->where('working_report_id', $reportId);
    }

    public function scopeByMachine($q, $machineId)
    {
        return $q->where('master_machine_id', $machineId);
    }

    public function scopeSearch($q, $term)
    {
        if (!$term) return $q;
        return $q->where(function ($w) use ($term) {
            $w->where('title', 'like', "%{$term}%")
              ->orWhere('problem_note', 'like', "%{$term}%")
              ->orWhere('location', 'like', "%{$term}%");
        });
    }

    public function maintenanceOrders()
    {
        return $this->hasMany(MaintenanceOrder::class, 'master_machine_id');
    }

}
