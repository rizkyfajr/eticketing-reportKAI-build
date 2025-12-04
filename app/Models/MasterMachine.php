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
        'classification_id',
        'name',
        'type',
        'nomor',
        'tahun_md',
        'hierarchy_code',
        'umur',
        'no_sarana',
        'keterangan',
        'qr_code',
    ];

    protected $with = [
        'region',
        'classification',
    ];


    public function region()
    {
        return $this->belongsTo(MasterRegion::class);
    }

    public function classification()
    {
        return $this->belongsTo(MasterClassification::class);
    }
}
