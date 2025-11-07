<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;

class WorkingReport extends Model
{
    use HasFactory, HasAttachment;

    protected $table = 'working_reports';

    protected $fillable = [
        'machine_id',
        'region_id',
        'date',
        'has_trouble',
        'status',
        'created_by_id',
        'updated_by_id',
    ];

    protected $with = [
        'checksheet',
        'upload',
        'warmingup',
        'workresult',
        'machine',
        'region',
        'createdBy',
        'updatedBy',
    ];

    public function checksheetday()
    {
        return $this->hasOne(CheckSheetDay::class, 'working_report_id', 'id');
    }

    public function upload()
    {
        return $this->hasOne(Upload::class, 'working_report_id', 'id');
    }

    public function checksheetworkresult()
    {
        return $this->hasMany(CheckSheetWorkResult::class, 'check_sheet_day_id');
    }

    public function checksheet()
    {
        return $this->hasOne(CheckSheet::class, 'working_report_id', 'id');
    }

    public function warmingup()
    {
        return $this->hasOne(WarmingUp::class, 'working_report_id', 'id');
    }

    public function workresult()
    {
        return $this->hasOne(WorkResult::class, 'working_report_id', 'id');
    }

    public function machine()
    {
        return $this->belongsTo(MasterMachine::class, 'machine_id');
    }

    public function region()
    {
        return $this->belongsTo(MasterRegion::class, 'region_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function maintenanceOrders()
    {
        return $this->hasMany(MaintenanceOrder::class);
    }

}
