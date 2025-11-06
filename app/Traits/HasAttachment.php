<?php

namespace App\Traits;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;

trait HasAttachment
{
  /**
   * @inheritdoc
   */
  // public function __construct(array $attributes = [])
  // {
  //   $this->with = [...$this->with, 'attachments'];
  // }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\MorphMany
   */
  public function attachments()
  {
    return $this->morphMany(Attachment::class, 'attachmentable')->oldest();
  }

  /**
   * @inheritdoc
   */
  // public static function boot()
  // {
  //   parent::boot();
    
  //   static::deleted(function (Model $model) {
  //     $model->attachments()->delete();
  //   });
  // }
}