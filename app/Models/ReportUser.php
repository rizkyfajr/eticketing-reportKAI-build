<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ReportUser extends Model
{
  use HasFactory;
  use HasAttachment;

  public $table = "report_user";

  /**
   * @var string[]
   */
  protected $fillable = [
    'report_id',
    'user_id',


    'created_by_id',
  ];
  public $with = [
    'createdBy',
    // 'report',
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
  public function divisionsection()
  {
    return $this->hasMany(\App\Models\DivisionModels::class, 'divisionsection', 'id');
  }
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
  public function repot()
  {
    return $this->belongsTo(Report::class);
  }
}
