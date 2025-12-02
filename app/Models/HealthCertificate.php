<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HealthCertificate extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'upload_date',
    'valid_from',
    'valid_until',
    'file_path',
    'status',
    'notes',
  ];

  protected $casts = [
    'upload_date' => 'date',
    'valid_from' => 'date',
    'valid_until' => 'date',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Check apakah sertifikat masih valid untuk hari ini
   */
  public function isValid()
  {
    $today = Carbon::today();
    return $this->status === 'active'
      && $today->between($this->valid_from, $this->valid_until);
  }

  /**
   * Scope untuk mendapatkan sertifikat yang valid
   */
  public function scopeValid($query)
  {
    $today = Carbon::today();
    return $query->where('status', 'active')
      ->where('valid_from', '<=', $today)
      ->where('valid_until', '>=', $today);
  }
}
