<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Position extends Model
{
  use HasFactory;
  use HasAttachment;
  
  public $table = "positions";

  /**
   * @var string[]
   */
  protected $fillable = [
    'position',
    'created_at',
    'updated_at',
  ];
  
  public $with = [
    'users',
  ];
  
  public function positions()
  {
    return $this->hasMany(\App\Models\Position::class, 'positions', 'id');
  }

  public function users()
  {
    return $this->belongsTo(User::class, 'position_id');
  }
}
