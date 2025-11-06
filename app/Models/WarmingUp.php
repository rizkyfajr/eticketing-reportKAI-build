<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarmingUp extends Model
{
    use HasFactory;

    protected $table = 'warming_up';

    protected $fillable = [
        'working_report_id',
        'machine_id',
        'waktu_start_engine',
        'jam_kerja',
        'jam_mesin',
        'jam_genset',
        'counter_pecok',
        'oddometer',
        'waktu_stop_engine',
        'penggunaan_hsd',
        'hsd_tersedia',
        'note',
        'approved_by',
        'approved_at',
        'created_by_id',
        'updated_by_id',
    ];

    protected $with = [
        'machine',
        'createdBy',
        'approvedBy',
        'updatedBy',
        'warmingup_user',
    ];

    public function workingreport()
    {
        return $this->belongsTo(WorkingReport::class, 'working_report_id', 'id');
    }

    public function machine()
    {
        return $this->belongsTo(MasterMachine::class, 'machine_id');
    }

    public function region()
    {
        return $this->belongsTo(MasterRegion::class, 'region_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function warmingup_user()
    {
        return $this->hasMany(WarmingUpUser::class, 'warming_up_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'warmingup_user', 'warming_up_id', 'user_id')->withTimestamps();
    }

}
