<?php

namespace App\Models\Strategy;

use App\Models\Admin\Department;
use App\Models\Indicators\Indicator\Indicator;
use App\Models\Poa\PoaProgram;
use App\Models\Projects\ProjectArticulations;
use App\Scopes\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static Builder|PlanDetail collect($sort = 'name')
 * @property int $id;
 */
class PlanDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var bool
     */
    public $timestamps = false;

    protected bool $tenantable = false;

    const PLAN_DETAIL_PROGRAM_LEVEL = 3;

    protected $dates = ['deleted_at'];

    /**
     * Fillable fields.
     *
     * @var string[]
     */
    protected $fillable = [
        'plan_id',
        'plan_registered_template_detail_id',
        'parent_id',
        'code',
        'name',
        'level',
        'mission_objective',
        'organizational_development',
        'perspective',
        'company_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_code'];

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($model) {
            $model->planArticulationSource->each->delete();
        });
        static::creating(function ($model) {
            $model->name = mb_strtoupper($model->name);
            $model->code = mb_strtoupper($model->code);
        });
        static::updating(function ($model){
            $model->name = mb_strtoupper($model->name);
            $model->code = mb_strtoupper($model->code);
        });
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('id', 'asc');
        });
    }

    /**
     * Parent element
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(PlanDetail::class, 'parent_id');
    }

    /**
     * Parent element
     *
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * Parent element
     *
     * @return BelongsTo
     */
    public function planRegistered(): BelongsTo
    {
        return $this->belongsTo(PlanRegisteredTemplateDetails::class, 'plan_registered_template_detail_id');
    }

    /**
     * Children elements
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(PlanDetail::class, 'parent_id', 'id');
    }

    public function planArticulationSource(){
        return $this->hasMany(PlanArticulations::class, 'plan_source_detail_id');
    }

    public function planArticulationTarget(){
        return $this->hasMany(PlanArticulations::class, 'plan_target_detail_id');
    }

    /**
     * Transform display name.
     *
     * @return string
     */
    public function getFullCodeAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->full_code . '.' . $this->code;
        }
        return $this->code ?? "";
    }

    /**
     * Get all of the plan details indicators.
     *
     * @return MorphMany
     */
    public function indicators(): MorphMany
    {
        return $this->morphMany(Indicator::class, 'indicatorable')->withoutGlobalScope(Company::class);
    }

    public function getIdsPrograms($name){
        return $this->where('name',$name)->get()->pluck('id','id');
    }

    public function getPrograms()
    {
        $childOfObjectives = $this->where('level', 1)->get();
        $i = 0;
        $idsPrograms = [];
        $groups = array();
        $groups2 = array();
        foreach ($childOfObjectives as $index => $child) {
            $childOfObjective1 = $child->children()->get();
            foreach ($childOfObjective1 as $child2) {
                foreach ($child2->children()->get()->pluck('name', 'id') as $index => $child3) {
                    $idsPrograms[$i] = [$child2->id, 'id' => $index, 'name' => $child3];
                    $i++;
                }
            }
        }

        foreach ($idsPrograms as $item) {
            $key = $item['name'];
            if (!array_key_exists($key, $groups)) {
                $groups[$key] = array(
                    'id' => $item['id'],
                    'name' => $item['name'],
                );
                $i++;
            } else {
                $groups2[$key]['id'] = $groups[$key]['id'];
                $groups[$key]['name'] = $groups[$key]['name'];
            }
        }
        foreach ($groups as $index => $group) {
            $key = $group['name'];
            $groups[$key]['name'] = $group['name'];
            $groups[$key]['id'] = $group['id'];
        }
        return [
            'idsPrograms' => $idsPrograms,
            'programsGrouped' => $groups
        ];
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'departments_programs', 'program_id', 'department_id');
    }

    public function articulations(){
        return $this->hasMany(ProjectArticulations::class,'plan_target_detail_id');
    }

    public function programs(){
        return $this->hasMany(PoaProgram::class, 'plan_detail_id');
    }

}
