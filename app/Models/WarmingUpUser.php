<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarmingUpUser extends Model
{
  use HasFactory;

  public $table = "warmingup_user";

  /**
   * @var string[]
   */
  protected $fillable = [
    'warming_up_id',
    'user_id',
  ];

  public function warming_up()
  {
    return $this->belongsTo(WarmingUp::class, 'warming_up_id');
  }

  public function user()
  {
      return $this->belongsTo(User::class);
  }

}
