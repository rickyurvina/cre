<?php

namespace App\Models\Poa;

use App\Abstracts\Model;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Strategy\PlanDetail;
use Illuminate\Database\Eloquent\Builder;

class PoaIndicatorConfig extends Model
{
    protected $table = 'poa_indicator_configs';

    protected bool $tenantable = false;

    protected $fillable = [
        'poa_id',
        'indicator_id',
        'program_id',
        'selected',
        'reason',
    ];


    public function indicator()
    {
        return $this->belongsTo(Indicator::class,'indicator_id')->withoutGlobalScopes();;
    }

    public function program()
    {
        return $this->belongsTo(PoaProgram::class);
    }

    public function poa()
    {
        return $this->belongsTo(Poa::class, 'poa_id');
    }

    public function scopeSelected(Builder $query)
    {
        return $query->where('selected', true);
    }
}
