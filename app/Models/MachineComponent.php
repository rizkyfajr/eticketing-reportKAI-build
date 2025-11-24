<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineComponent extends Model
{
    protected $fillable = [
        'master_machine_id',
        'machine_type',
        'parent_id',
        'code',
        'name',
        'level',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
