<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SystemSectionModels extends Model
{
  use HasFactory;
  use HasAttachment;

  public $table = "system_section";

  /**
   * @var string[]
   */
  protected $fillable = [
    'report_id',
    'singkatan',
    'nama',
    'level',
    'created_by_id',
  ];
  public $with = [
    'createdBy',
    'report',
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
  public function systemsection()
  {
    return $this->hasMany(\App\Models\SystemSectionModels::class, 'systemsection', 'id');
  }
}
