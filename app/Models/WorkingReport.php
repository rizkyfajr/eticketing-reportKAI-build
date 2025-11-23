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
        'mode',
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
        'kupt_by1',
        'kupt_at1',
        'created_by_id',
        'updated_by_id',
    ];

    protected $with = [
        'mglurusanawal',
        'mglengkunganawal',
        'mgweselawal',
        'pemeriksaansilangkpjr',
        'pemeriksaansilanglahan',
        'perekamanawal',
        'mglurusanakhir',
        'mglengkunganakhir',
        'mgweselakhir',
        'perekamanakhir',
        'checksheet',
        'upload',
        'warmingup',
        'workresult',
        'machine',
        'region',
        'operator1',
        'operator2',
        'operator3',
        'pengawal',
        'pengawal1',
        'kuptBy1',
        'createdBy',
        'updatedBy',
    ];

    public function mglurusanawal()
    {
        return $this->hasOne(MgLurusanAwal::class, 'working_report_id', 'id');
    }

    public function mglengkunganawal()
    {
        return $this->hasOne(MgLengkunganAwal::class, 'working_report_id', 'id');
    }

    public function mgweselawal()
    {
        return $this->hasOne(MgWeselAwal::class, 'working_report_id', 'id');
    }

    public function pemeriksaansilangkpjr()
    {
        return $this->hasOne(PemeriksaanSilangKpjr::class, 'working_report_id', 'id');
    }

    public function pemeriksaansilanglahan()
    {
        return $this->hasOne(Pemeriksaansilanglahan::class, 'working_report_id', 'id');
    }

    public function perekamanawal()
    {
        return $this->hasOne(PerekamanAwal::class, 'working_report_id', 'id');
    }

    public function mglurusanakhir()
    {
        return $this->hasOne(MgLurusanAkhir::class, 'working_report_id', 'id');
    }

    public function mglengkunganakhir()
    {
        return $this->hasOne(MgLengkunganAkhir::class, 'working_report_id', 'id');
    }

    public function mgweselakhir()
    {
        return $this->hasOne(MgWeselAkhir::class, 'working_report_id', 'id');
    }

    public function perekamanakhir()
    {
        return $this->hasOne(PerekamanAkhir::class, 'working_report_id', 'id');
    }

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
    
    public function pengawal()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function pengawal1()
    {
        return $this->belongsTo(User::class, 'approved_by1');
    }
    
    public function kuptBy1()
    {
        return $this->belongsTo(User::class, 'kupt_by1');
    }

}
