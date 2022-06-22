<?php

namespace App\Models\Indicators\GoalIndicator;

use Illuminate\Database\Eloquent\Model;
use App\Models\Indicators\Indicator\Indicator;
use App\Traits\Tenants;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;


class GoalIndicators extends Model
{
    use SoftDeletes, Sortable, Tenants, SoftCascadeTrait;

    /**
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = ['indicators_id', 'goal_value', 'min', 'max',
        'period', 'actual_value', 'user_updated', 'state', 'year', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'date:Y-m',
        'end_date' => 'date:Y-m',
    ];


    public static function boot()
    {
        parent::boot();
    }

    /**
     * Obtener el Indicador del Plan al que pertenece la Meta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicators_id');
    }

    public function reportDay(): bool
    {
        return Carbon::createFromFormat('Y-m-d', $this->end_date)->addDays(5) < now() || $this->actual_value != 0;
    }

    public function progress(): float
    {
        return $this->goal != 0 ? (float)number_format((($this->actual_value * 100) / $this->goal_value), 2) : 0.00;
    }
}
