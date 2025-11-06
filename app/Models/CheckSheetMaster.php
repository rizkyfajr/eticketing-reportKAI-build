<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheetMaster extends Model
{
  use HasFactory;
  
  protected $table = 'check_sheet_masters';

  protected $fillable = [
      'group_name',
      'komponen',
      'rujukan',
      'satuan',
      'urutan',
  ];

  public function results()
  {
      return $this->hasMany(CheckSheetResult::class, 'check_sheet_master_id');
  }
}
