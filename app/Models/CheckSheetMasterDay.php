<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheetMasterDay extends Model
{
  use HasFactory;

  protected $table = 'check_sheet_master_days';

  protected $fillable = [
      'group_name',
      'urutan',
      'komponen',
      'rujukan',
      'nilai_rujukan',
      'satuan',
      'jenis_mesin',
  ];

  public function dayresults()
  {
      return $this->hasMany(CheckSheetDayResult::class, 'check_sheet_master_day_id');
  }
}
