<?php

namespace App\Abstracts;

use App\Models\Admin\Company;
use App\Scopes\Company as CompanyScope;
use App\Traits\Auditable;
use App\Traits\Tenants;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;
use Spatie\Activitylog\Traits\LogsActivity;

abstract class Model extends Eloquent
{
    use Cachable, SoftDeletes, SearchString, Sortable, Tenants, LogsActivity, Auditable;

    protected bool $tenantable = true;

    protected $dates = ['deleted_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);

        static::creating(function ($model) {
            if (method_exists($model, 'isNotTenantable') && $model->isNotTenantable()) {
                return;
            }
            $model->company_id = session('company_id');
        });
    }

    /**
     * Global company relation.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope to only include company data.
     *
     * @param Builder $query
     * @param $company_id
     *
     * @return Builder
     */
    public function scopeCompanyId($query, $company_id): Builder
    {
        return $query->where($this->table . '.company_id', '=', $company_id);
    }

    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param Builder $query
     *
     * @return mixed
     */
    public function scopeCollect(Builder $query)
    {
        $request = request();

        $search = $request->get('search');
        $limit = $request->get('limit', setting('default.list_limit', '25'));

        return $query->usingSearchString($search)->sortable()->paginate($limit);
    }

    /**
     * Scope to only include active models.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('enabled', 1);
    }

    /**
     * Scope to only include passive models.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where('enabled', 0);
    }
}
