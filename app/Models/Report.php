<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Report extends Model
{
  use HasFactory;
  use HasAttachment;
  
  public $table = "reports";

  /**
   * @var string[]
   */
  protected $fillable = [
    'kode',
    'bagian_sistem',
    'bagian_hardware',
    'bagian_id',
    'tanggal',
    'bagian_pelapor',
    'kategori',
    'kendala',
    'dampak',
    'kontak',
    'url',
    'status',
    'catatan',
    'catatan1',
    'jenis',
    'created_by_id',
  ];
  
  public $with = [
    'createdBy',
    'reportuser',
    'users',
    'bagian'
  ];
  
  public function createdBy()
  {
    return $this->hasOne(User::class, 'id', 'created_by_id');
  }
  
  public function users()
  {
    return $this->belongsToMany(User::class);
  }

  public function feedbacks()
  {
      return $this->hasMany(FeedbackModels::class, 'report_id');
  }
  public function systemsection()
  {
    return $this->hasMany(SystemSectionModels::class);
  }
  public function divisionsection()
  {

    return $this->hasMany(DivisionModels::class);
  }

  public function reportuser()
  {
    return $this->hasMany(ReportUser::class, 'report_id', 'id');
  }

  public function bagian()
  {
    // return $this->hasOne(DivisionModels::class, 'id', 'bagian_id');
    return $this->hasOne(SystemSectionModels::class, 'id', 'bagian_id');
  }

}
