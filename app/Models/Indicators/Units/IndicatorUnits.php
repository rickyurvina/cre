<?php

namespace App\Models\Indicators\Units;

use App\Abstracts\Model;
use App\Models\Indicators\Indicator\Indicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicatorUnits extends Model
{
    use HasFactory;


    const PEOPLE_REACHED = "PA";
    const TRAINED_PEOPLE = 'PCap';
    const EVALUATION = 'Eva';
    const DOCUMENTS = 'Doc';

    protected bool $tenantable = false;

    protected $casts = ['information_sources' => 'array'];

    protected $fillable = ['name', 'abbreviation'];

    public $sortable = ['name', 'abbreviation'];

    /**
     * Obtener el Indicador al que pertenece
     *
     * @return BelongsTo
     */


    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicators_id');
    }

    public function getUnits()
    {
        return
            [
                0 => $this->where('abbreviation', IndicatorUnits::PEOPLE_REACHED)->first(),
                1 => $this->where('abbreviation', IndicatorUnits::TRAINED_PEOPLE)->first(),
                2 => $this->where('abbreviation', IndicatorUnits::EVALUATION)->first(),
                3 => $this->where('abbreviation', IndicatorUnits::DOCUMENTS)->first()
            ];
    }
}
