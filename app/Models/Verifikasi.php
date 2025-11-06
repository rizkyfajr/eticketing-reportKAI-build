<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasApproval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Verifikasi extends Model
{
  use HasFactory;
  
  public $table = "verifications";

  /**
   * @var string[]
   */
  protected $fillable = [
    'report_id',
    'tanggal',
    'catatan',
    'created_by_id',
  ];
  
  public $with = [
    'createdBy',
    'report'
  ];
  
  public function createdBy()
  {
    return $this->hasOne(User::class, 'id', 'created_by_id');
  }
  
  public function users()
  {
    return $this->belongsToMany(User::class);
  }
  
  public function risk()
  {
    return $this->belongsTo(Report::class, 'id', 'report_id');
  }
  
  public function report()
  {
    return $this->belongsTo(Report::class, 'report_id');
  }
  
}
