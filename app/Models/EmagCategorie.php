<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmagCategorie extends Model
{
    use HasFactory;

    protected $table = 'emag_categorii';
    protected $guarded = [];

    public function subcategorii()
    {
        return $this->hasMany(EmagCategorie::class, 'parent_id');
    }
}
