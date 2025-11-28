<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadinessAssessment extends Model
{
  use HasFactory;

  protected $table = 'readiness_assessment';

  protected $fillable = [
      'assessment_master_id',
      'user_id',
      'assessment_date',
      'ya',
      'tidak',
      'note',
  ];

  protected $casts = [
      'assessment_date' => 'date',
  ];

  public function user()
  {
      return $this->belongsTo(User::class);
  }

  public function masterQuestion()
  {
      return $this->belongsTo(ReadinessAssessmentMaster::class, 'assessment_master_id');
  }
}
