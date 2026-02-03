<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;
use App\Traits\RegionalScope;

class WorkResult extends Model
{
    use HasFactory, HasAttachment, RegionalScope;

    protected $table = 'work_results';

    protected $fillable = [
        'working_report_id',
        'wilayah',
        'petak_jalan',
        'kelas_jalan',
        'lokasi_stabling_awal',
        'lokasi_stabling_akhir',
        'lokasi_awal1',
        'lokasi_akhir1',
        'jumlah1',
        'lokasi_awal2',
        'lokasi_akhir2',
        'jumlah2',
        'lokasi_awal3',
        'lokasi_akhir3',
        'jumlah3',
        'total_distance',
        'no_wesel1',
        'km_hm1',
        'jumlah_wesel1',
        'no_wesel2',
        'km_hm2',
        'jumlah_wesel2',
        'no_wesel3',
        'km_hm3',
        'jumlah_wesel3',
        'total_wesel',
        'no_lengkung1',
        'radius1',
        'jumlah_lengkung1',
        'no_lengkung2',
        'radius2',
        'jumlah_lengkung2',
        'no_lengkung3',
        'radius3',
        'jumlah_lengkung3',
        'total_lengkung',
        'waktu_stop_engine',
        'km_hm_lengkung1',
        'km_hm_lengkung2',
        'km_hm_lengkung3',
        'jam_traveling_akhir',
        'jam_kerja_akhir',
        'jam_mesin_akhir',
        'jam_generator_akhir',
        'counter_tamping_akhir',
        'oddometer_akhir',
        'hsd_akhir_kerja',
        'konsumsi_hsd',
        'hu_hi1',
        'hu_hi2',
        'hu_hi3',
        'hu_hi4',
        'hu_hi5',
        'hu_hi6',
        'hu_hi7',
        'hu_hi8',
        'hu_hi9',
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
        'created_by_id',
    ];

    protected $with = [
        'pengawal',
        'pengawal1',
        'operator1',
        'operator2',
        'operator3',
        'createdBy',
        'updatedBy',
        // 'workresult_user',
    ];

    public function workingreport()
    {
        return $this->belongsTo(WorkingReport::class, 'working_report_id', 'id');
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

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    // public function workresult_user()
    // {
    //     return $this->hasMany(WorkresultUser::class, 'work_result_id', 'id');
    // }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'workresult_user', 'work_result_id', 'user_id')->withTimestamps();
    // }

}
