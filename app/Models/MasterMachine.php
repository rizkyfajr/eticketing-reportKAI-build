<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMachine extends Model
{
    use HasFactory;

    protected $table = 'master_machines';

    protected $fillable = [
        'region_id',
        'name',
        'type',
        'nomor',
        'tahun_md',
        'hierarchy_code',
        'umur',
        'no_sarana',
        'keterangan',
    ];

    protected $with = [
        'region',
    ];


    public function region()
    {
        return $this->belongsTo(MasterRegion::class);
    }
}
