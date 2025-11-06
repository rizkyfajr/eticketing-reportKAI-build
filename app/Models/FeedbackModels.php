<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FeedbackModels extends Model
{
  use HasFactory;
  use HasAttachment;

  public $table = "feedbacks";

  /**
   * @var string[]
   */
  protected $fillable = [
    'report_id',
    'kode',
    'bagian_sistem',
    'tanggal',
    'bagian_pelapor',
    'kategori',
    'kendala',
    'dampak',
    'kontak',
    'url',
    'status',
    'catatan',
    'balasan',
    'balasan1',
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
}
