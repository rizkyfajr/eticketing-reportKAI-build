<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheetDayResult extends Model
{
    use HasFactory;
    
    protected $table = 'check_sheet_day_results';

    protected $fillable = [
        'check_sheet_day_id',
        'check_sheet_master_day_id',
        'cek',
        'tambahan',
        'ganti',
        'kiri_depan',
        'kanan_depan',
        'hasil',
        'keterangan',
    ];

    protected $with = [
        'checksheetday',
        'checksheetdaymaster',
    ];

    public function checksheetday()
    {
        return $this->belongsTo(CheckSheetDay::class, 'check_sheet_day_id');
    }

    public function checksheetdaymaster()
    {
        return $this->belongsTo(CheckSheetMasterDay::class, 'check_sheet_master_day_id');
    }
    
}
