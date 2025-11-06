<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joblist extends Model
{
  use HasFactory;
  
  public $table = "job_lists";

  /**
   * @var string[]
   */
  protected $fillable = [
    'report_id',
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
  
  public function report()
  {
    return $this->belongsTo(Report::class, 'report_id');
  }
  public function usered()
  {

    return $this->hasMany(User::class);
  }
}
