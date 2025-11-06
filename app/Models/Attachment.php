<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
  use HasFactory;

  /**
   * @var string[]
   */
  protected $fillable = [
    'attachmentable_id',
    'attachmentable_type',
    'uploader_id',
    'path',
    'filename',
    'size',
    'description',
  ];

  /**
   * Get all of the models that own attachments.
   */
  public function attachmentable()
  {
    return $this->morphTo();
  }

  /**
   * @var array 
   */
  protected $with = [
    'uploader',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function uploader()
  {
    return $this->hasOne(User::class, 'id', 'uploader_id');
  }
}
