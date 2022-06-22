<?php

namespace App\Models\Indicators\Sources;

use App\Abstracts\Model;
use App\Models\Indicators\Indicator\Indicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IndicatorSource extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = true;

    protected bool $tenantable = false;

    const TYPE_SURVEY = 'Survey';
    const TYPE_ADMINISTRATIVE_RECORD = 'Administrative_record';
    const TYPE_TRANSACTIONAL = 'transactional';

    protected $fillable = ['institution', 'name', 'description', 'type'];

    protected array $sortable = ['institution', 'name', 'description', 'type'];


    /**
     * Obtener el Indicador al que pertenece
     *
     * @return BelongsToMany
     */
    public function indicator(): BelongsToMany
    {
        return $this->belongsToMany(Indicator::class, 'indicators_id');
    }

}
