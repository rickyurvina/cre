<?php

namespace App\Models\Auth;

use App\Traits\Tenants;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Permission\Models\Permission as BasePermission;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Auth\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $title
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Permission action($action = 'read')
 * @method static Builder|Permission collect($sort = 'name')
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission permission($permissions)
 * @method static Builder|Permission query()
 * @method static Builder|Permission role($roles, $guard = null)
 * @method static Builder|Permission sortable($defaultParameters = null)
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereDescription($value)
 * @method static Builder|Permission whereDisplayName($value)
 * @method static Builder|Permission whereGuardName($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Permission extends BasePermission
{

    use Sortable, Tenants;

    protected $table = 'permissions';

    protected $tenantable = false;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['title'];

    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param Builder $query
     * @param $sort
     *
     * @return Builder
     */
    public function scopeCollect($query, $sort = 'name'): Builder
    {
        $request = request();

        $limit = $request->get('limit', setting('default.list_limit', '25'));

        return $query->sortable($sort)->paginate($limit);
    }

    /**
     * Scope to only include by action.
     *
     * @param Builder $query
     * @param string $action
     *
     * @return Builder
     */
    public function scopeAction($query, $action = 'read'): Builder
    {
        return $query->where('name', 'like', $action . '-%');
    }

    /**
     * Transform display name.
     *
     * @return string
     */
    public function getTitleAttribute(): string
    {
        $replaces = [
            'Create ' => '',
            // 'Read ' => '',
            'Update ' => '',
            'Delete ' => '',
            'Project ' => '',
            'Strategy ' => '',
            'Budget ' => '',
            'Poa ' => '',
            'Admin ' => '',
        ];

        return str_replace(array_keys($replaces), array_values($replaces), $this->display_name);
    }

    public function scopeIsSuperAdminProject(Builder $query)
    {
        return $query->where('name',  'project-super-admin-project');
    }
}
