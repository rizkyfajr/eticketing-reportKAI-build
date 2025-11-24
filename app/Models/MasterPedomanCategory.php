<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MasterPedomanCategory extends Model
{
    protected $guarded = [];

    public function masterPedoman()
    {
        return $this->belongsTo(MasterPedoman::class);
    }

    public function items()
    {
        return $this->hasMany(MasterPedomanItem::class)->orderBy('urutan');
    }
}
