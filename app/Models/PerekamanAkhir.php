<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;

class PerekamanAkhir extends Model
{
    use HasFactory, HasAttachment;

    protected $table = 'perekaman_akhir';
    
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
