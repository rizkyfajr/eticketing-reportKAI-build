<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheetDay extends Model
{
    use HasFactory;

    protected $table = 'check_sheet_days';

    protected $fillable = [
        'working_report_id',
        'no_seri',
        'jenis',
        'jam_mesin',
        'counter_pecok',
        'kilometer_mesin',
        'tanggal',
        'lokasi',
        'wilayah',
        'region_id',
        'note',
        'created_by_id',
        'updated_by_id',
    ];

    public function checksheetworkresult()
    {
        return $this->hasOne(CheckSheetWorkResult::class, 'check_sheet_day_id');
    }

    public function dayresults()
    {
        return $this->hasMany(CheckSheetDayResult::class, 'check_sheet_day_id');
    }

    public function workingReport()
    {
        return $this->belongsTo(WorkingReport::class, 'working_report_id');
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
}
