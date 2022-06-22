<?php

namespace App\Models\Indicators\Observations;

use App\Models\Auth\User;
use App\Models\Indicators\Indicator\Indicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicatorObservations extends Model
{
    use HasFactory;

    protected bool $tenantable = false;



    protected $fillable = ['observation', 'user_id','indicators_id'];

    public $sortable = ['observation', 'user_id','indicator_id'];

    /**
     * Obtener el Indicador al que pertenece
     *
     * @return BelongsTo
     */
    public static function boot()
    {
        parent::boot();
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicators_id');
    }

    /**
     * Obtener el usuario que creó el indicador
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
