<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;

class Upload extends Model
{
  use HasFactory;
  use HasAttachment;

  protected $table = 'upload';

    protected $fillable = [
      'working_report_id',
      'date',
    ];

    public function workingreport()
    {
        return $this->belongsTo(WorkingReport::class, 'working_report_id', 'id');
    }
}
