<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPedoman extends Model
{
    use HasFactory;
    protected $table = 'master_pedoman';
    // Supaya bisa diisi massal (dgn Seeder/Controller)
    protected $guarded = [];

    /**
     * Relasi ke semua maintenance order yang menggunakan pedoman ini
     */
    public function maintenanceOrders()
    {
        return $this->hasMany(MaintenanceOrder::class);
    }
    public function categories()
    {
        return $this->hasMany(MasterPedomanCategory::class)->orderBy('order');
    }

    // Helper untuk mengambil semua items lewat kategori (HasManyThrough)
    public function items()
    {
        return $this->hasManyThrough(MasterPedomanItem::class, MasterPedomanCategory::class);
    }
}
