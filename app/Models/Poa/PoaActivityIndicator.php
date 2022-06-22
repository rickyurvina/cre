<?php

namespace App\Models\Poa;

use App\Models\Indicators\GoalIndicator\GoalIndicators;
use App\Models\Indicators\Indicator\Indicator;
use App\Traits\Tenants;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class PoaActivityIndicator extends Eloquent
{
    use SoftDeletes, Sortable, Tenants, SoftCascadeTrait;

    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'poa_activity_id',
        'indicator_id',
        'goal_indicator_id',
        'period',
        'goal',
        'progress',
        'men_progress',
        'women_progress',
        'company_id',
        'year',
        'start_date',
        'end_date',
    ];

    public static function boot()
    {
        parent::boot();
    }

    /**
     * Activity Indicator related goal
     *
     * @return BelongsTo
     */
    public function goalIndicator()
    {
        return $this->belongsTo(GoalIndicators::class);
    }

    /**
     * Activity Indicator related goal
     *
     * @return BelongsTo
     */
    public function poaActivity()
    {
        return $this->belongsTo(PoaActivity::class,'poa_activity_id');
    }

    /**
     * Activity Indicator related indicator
     *
     */
    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }

    public function progress()
    {
        if ($this->goal > 0) {
            return number_format($this->progress / $this->goal * 100, 1);
        } else {
            return 0;
        }
    }

    /**
     * Obtiene el numero de hombres capacitados
     *
     */
    public function getTotalHommbresAlcanzados($activities, $filter = null)
    {
        return $this->getProgressGoals($activities, 'men_progress', $filter);

    }

    /**
     * Obtiene el numero de hombres capacitados
     *
     */
    public function getTotalMujeresAlcanzadas($activities, $filter = null)
    {
        return $this->getProgressGoals($activities, 'women_progress', $filter);
    }

    /**
     * Obtiene el goal de personas capacitadas
     *
     */
    public function getGoalMenWomen($activities, $filter = null)
    {
        return $this->whereIn('poa_activity_id', $activities)->get()->sum('goal');
    }

    public function getProgressGoals($activities, $target, $filter = null)
    {
        $poaActivity = $this->whereIn('poa_activity_id', $activities)->orderBy('id')->get();
        $months = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];
        $total_actual = 0;
        switch ($filter) {
            case "semester":
                $i = date("m") - 5;
                break;
            case "quarterly":
                $i = date("m") - 2;
                break;
            case "last-month":
                $i = date("m");
                break;
        }
        if (is_null($filter)) {
            return $poaActivity->sum($target);
        } else {
            foreach ($poaActivity as $index => $p) {
                if (in_array($filter, $months)) {
                    if ($p->period <= $filter) {
                        $total_actual += $p->$target;
                    }
                } else {
                    if ($p->period >= $i) {
                        $total_actual += $p->$target;
                    }
                }
            }
            return $total_actual;
        }
    }

    public function scopeFindActivityId($activityId)
    {
        return $this->where('poa_activity_id', $activityId)->get();
    }
}
