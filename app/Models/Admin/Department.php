<?php

namespace App\Models\Admin;

use App\Abstracts\Model;
use App\Models\Auth\User;
use App\Models\Strategy\PlanDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

/**
 */
class Department extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
        'description',
        'responsible',
        'parent_id',
        'company_id',
        'enabled'
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public array $sortable = ['code', 'name', 'responsible', 'created_at', 'enabled'];

    /**
     * Scope to only include departments of a given enabled value.
     *
     * @param Builder $query
     * @param mixed $value
     *
     * @return Builder
     */


    public function scopeEnabled(Builder $query, $value = 1): Builder
    {
        return $query->where('enabled', $value);
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible');
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(PlanDetail::class, 'departments_programs', 'department_id', 'program_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_departments', 'department_id', 'user_id')->withoutGlobalScopes();
    }


}