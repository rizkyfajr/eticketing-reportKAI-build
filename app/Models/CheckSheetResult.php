<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheetResult extends Model
{
    use HasFactory;
    
    protected $table = 'check_sheet_results';

    protected $fillable = [
        'check_sheet_id',
        'check_sheet_master_id',
        'hasil',
        'keterangan',
    ];

    protected $with = [
        'checksheet',
        'checksheetmaster',
    ];

    public function checksheet()
    {
        return $this->belongsTo(CheckSheet::class, 'check_sheet_id');
    }

    public function checksheetmaster()
    {
        return $this->belongsTo(CheckSheetMaster::class, 'check_sheet_master_id');
    }
}