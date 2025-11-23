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
        'tanggal',
        'cuaca',
        'jenis_kpjr',
        'nomor_mesin',
        'nomor_sarana',
        'waktu_start_engine',
        'jam_traveling_awal',
        'jam_kerja_awal',
        'jam_mesin_awal',
        'jam_generator_awal',
        'counter_tamping_awal',
        'oddometer_awal',
        'hsd_awal_kerja',
        'konsumsi_hsd',
        'waktu_stop_engine',
        'jam_traveling_akhir',
        'jam_kerja_akhir',
        'jam_mesin_akhir',
        'jam_generator_akhir',
        'counter_tamping_akhir',
        'oddometer_akhir',
        'hsd_akhir_kerja',
        'operator_by1',
        'operator_at1',
        'operator_by2',
        'operator_at2',
        'operator_by3',
        'operator_at3',
        'approved_by',
        'approved_at',
        'approved_by1',
        'approved_at1',
        'note',
        'created_by_id',
    ];

    protected $with = [
        'machine',
        'pengawal',
        'pengawal1',
        'operator1',
        'operator2',
        'operator3',
        'createdBy',
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

    public function pengawal()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function pengawal1()
    {
        return $this->belongsTo(User::class, 'approved_by2');
    }

    public function operator1()
    {
        return $this->belongsTo(User::class, 'operator_by1');
    }

    public function operator2()
    {
        return $this->belongsTo(User::class, 'operator_by2');
    }

    public function operator3()
    {
        return $this->belongsTo(User::class, 'operator_by3');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
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
