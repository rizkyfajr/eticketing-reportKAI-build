<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheet extends Model
{
    use HasFactory;

    protected $table = 'check_sheets';

    protected $fillable = [
        'working_report_id',
        'upt_resor',
        'tanggal',
        'waktu',
        'region_id',
        'cuaca',
        'tipe_kpjr',
        'nomor_seri',
        'jam_mesin',
        'kilometer_mesin',
        'counter_tamping',
        'note',
        'approved_by',
        'approved_at',
        'approved_by1',
        'approved_at1',
        'approved_by2',
        'approved_at2',
        'created_by_id',
        'updated_by_id',
    ];

    protected $with = [
        'region',
        'approvedBy',
        'approvedBy1',
        'approvedBy2',
        'createdBy',
        'updatedBy',
        // 'results',
    ];

    public function results()
    {
        return $this->hasMany(CheckSheetResult::class, 'check_sheet_id');
    }

    public function workingreport()
    {
        return $this->belongsTo(WorkingReport::class);
    }

    public function region()
    {
        return $this->belongsTo(MasterRegion::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvedBy1()
    {
        return $this->belongsTo(User::class, 'approved_by1');
    }

    public function approvedBy2()
    {
        return $this->belongsTo(User::class, 'approved_by2');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
