<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckSheetWorkResult extends Model
{
  use HasFactory;

  protected $table = 'check_sheet_work_results';

    protected $fillable = [
        'working_report_id',
        'check_sheet_day_id',
        'catatan_gangguan',
        'lokasi_dan_jam1',
        'hu_hi_1',
        'jumlah_1',
        'lokasi_dan_jam2',
        'hu_hi_2',
        'jumlah_2',
        'lokasi_dan_jam3',
        'hu_hi_3',
        'jumlah_3',
        'operator_by1',
        'operator_at1',
        'validasi1',
        'operator_by2',
        'operator_at2',
        'validasi2',
        'operator_by3',
        'operator_at3',
        'validasi3',
        'operator_by4',
        'operator_at4',
        'validasi4',
    ];

    protected $with = [
        'workingreport',
        'checksheetday',
        'operator1',
        'operator2',
        'operator3',
        'operator4',
    ];

    public function workingreport()
    {
        return $this->belongsTo(WorkingReport::class, 'working_report_id');
    }

    public function checksheetday()
    {
        return $this->belongsTo(CheckSheetDay::class, 'check_sheet_day_id');
    }

    public function operator1()
    {
        return $this->belongsTo(User::class, 'operator_by1');
    }

    public function operator2()
    {
        return $this->belongsTo(User::class, 'operator_by2');
    }

    public function operator3()
    {
        return $this->belongsTo(User::class, 'operator_by3');
    }

    public function operator4()
    {
        return $this->belongsTo(User::class, 'operator_by4');
    }
}
