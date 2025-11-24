<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MasterPedomanItem extends Model
{
    protected $guarded = [];

    // Casting JSON agar otomatis jadi Array di Laravel
    protected $casts = [
        'extra_config' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(MasterPedomanCategory::class, 'master_pedoman_category_id');
    }
}
