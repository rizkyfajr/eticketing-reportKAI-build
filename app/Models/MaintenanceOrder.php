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
        'lampiran_path',
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
        'master_pedoman_id',
        'machine_hours',
        'status',

        // Kolom Follow Up Plan
        'follow_up_by_id',
        'follow_up_at',
        'follow_up_plan',
        'follow_up_estimate_at',

        // Kolom Starts to Repair
        'start_repair_by_id',
        'start_repair_at',
        'start_repair_notes',
        'start_repair_photo',

        // Kolom Repair Complete
        'complete_repair_by_id',
        'complete_repair_at',
        'complete_repair_notes',
        'complete_repair_photo',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
        'follow_up_estimate_at' => 'datetime',
        'start_repair_at' => 'datetime',
        'complete_repair_at' => 'datetime',

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

    public function componentLv1()
    {
        return $this->belongsTo(\App\Models\MachineComponent::class, 'component_lv1_id');
    }
    public function componentLv2()
    {
        return $this->belongsTo(\App\Models\MachineComponent::class, 'component_lv2_id');
    }
    public function componentLv3()
    {
        return $this->belongsTo(\App\Models\MachineComponent::class, 'component_lv3_id');
    }

    public function masterPedoman()
    {
        return $this->belongsTo(\App\Models\MasterPedoman::class);
    }

    public function results() {
        return $this->hasMany(\App\Models\MaintenanceOrderResult::class);
    }

    public function followUpBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'follow_up_by_id');
    }

    public function startRepairBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'start_repair_by_id');
    }

    public function completeRepairBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'complete_repair_by_id');
    }
}
