<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceOrderResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order() {
        return $this->belongsTo(MaintenanceOrder::class);
    }
    public function item() {
        return $this->belongsTo(MasterPedomanItem::class, 'master_pedoman_item_id');
    }
}
