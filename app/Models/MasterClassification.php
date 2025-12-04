<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterClassification extends Model
{
  use HasFactory;

  protected $table = 'master_classifications';

  protected $fillable = [
      'name',
  ];
}
