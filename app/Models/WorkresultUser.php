<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAttachment;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WorkresultUser extends Model
{
  use HasFactory;
  use HasAttachment;

  public $table = "workresult_user";

  /**
   * @var string[]
   */
  protected $fillable = [
    'work_result_id',
    'user_id',
  ];

  public function work_result()
  {
    return $this->belongsTo(WorkResult::class, 'work_result_id');
  }
}
