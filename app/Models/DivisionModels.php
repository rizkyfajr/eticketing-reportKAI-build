<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DivisionModels extends Model
{
  use HasFactory;
  use HasAttachment;

  public $table = "division";

  /**
   * @var string[]
   */
  protected $fillable = [
    'report_id',
    'division_number',
    'division_name',

    'created_by_id',
  ];
  public $with = [
    'createdBy',
    'report',
    'users',
  ];

  public function createdBy()
  {
    return $this->hasOne(User::class, 'id', 'created_by_id');
  }

  // public function users()
  // {
  //   return $this->belongsToMany(User::class);
  // }

  public function users()
  {
    return $this->belongsTo(User::class, 'division_id');
  }

  public function report()
  {
    return $this->belongsTo(Report::class, 'report_id');
  }
  public function divisionsection()
  {
    return $this->hasMany(\App\Models\DivisionModels::class, 'divisionsection', 'id');
  }
}
