<?php

namespace App\Models\Indicators\Threshold;


use App\Abstracts\Model;
use App\Models\Indicators\Indicator\Indicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Threshold extends Model
{
    use HasFactory;

    const ASCENDING = 'Ascending';
    const DESCENDING = 'Descending';
    const DANGER = 'Danger';
    const  WARNING = 'Warning';
    const SUCCESS = 'Success';
    const TOLERANCE = 'Tolerance';
    protected $casts = [
        'properties' => 'array'
    ];

    protected $fillable = ['name', 'properties'];

    protected bool $tenantable = false;

    /**
     * Obtener el Indicador al que pertenece
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function indicator()
    {
        return $this->belongsToMany(Indicator::class, 'indicators_id');
    }

}
