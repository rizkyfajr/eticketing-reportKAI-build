<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;

class WorkResult extends Model
{
    use HasFactory, HasAttachment;

    protected $table = 'work_results';

    protected $fillable = [
        'working_report_id',
        'machine_id',
        'region_id',
        'tanggal',
        'cuaca',
        'wilayah',
        'petak_jalan',
        'jalur',
        'kelas_jalan',
        'kecepatan_lintas',
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
        'waktu_start_engine',
        'jam_luncuran',
        'jam_kerja',
        'jam_mesin',
        'jam_genset',
        'waktu_stop_engine',
        'counter_pecok',
        'oddometer',
        'penggunaan_hsd',
        'penggunaan_hsd1',
        'hsd_tersedia',
        'oddometer_hsd',
        'pengawal_id',
        'note',
        'operator_by1',
        'operator_at1',
        'operator_by2',
        'operator_at2',
        'operator_by3',
        'operator_at3',
        'approved_by',
        'approved_at',
        'created_by_id',
        'updated_by_id',
    ];

    protected $with = [
        'machine',
        'region',
        'pengawal',
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
        return $this->belongsTo(User::class, 'pengawal_id');
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
