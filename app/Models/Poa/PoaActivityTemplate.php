<?php

namespace App\Models\Poa;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoaActivityTemplate extends Model
{
    use HasFactory;

    protected $table = 'poa_activity_templates';

    protected $fillable = ['name', 'code', 'cost', 'impact', 'complexity', 'created_at', 'updated_at', 'deleted_at'];


    public function scopeIsDeleted($query){
        return $query->where('deleted_at',null);
    }

}
