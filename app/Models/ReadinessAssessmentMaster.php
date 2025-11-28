<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadinessAssessmentMaster extends Model
{
  use HasFactory;

  protected $table = 'readiness_assessment_master';

  protected $fillable = [
      'group_name',
      'urutan',
      'nomor',
      'komponen',
      'pertanyaan',
  ];

  public function assessments()
  {
      return $this->hasMany(ReadinessAssessment::class, 'assessment_master_id');
  }
}
