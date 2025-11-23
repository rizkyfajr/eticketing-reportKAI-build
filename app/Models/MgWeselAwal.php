<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;

class MgWeselAwal extends Model
{
    use HasFactory, HasAttachment;

    protected $table = 'mg_wesel_awal';
    
    protected $fillable = [
      'working_report_id',
      'ada',
      'tidak',
    ];

    public function workingreport()
    {
        return $this->belongsTo(WorkingReport::class, 'working_report_id', 'id');
    }
}
