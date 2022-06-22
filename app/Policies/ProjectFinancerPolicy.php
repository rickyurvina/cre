<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\ProjectFinancer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectFinancerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Auth\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectFinancer  $projectFinancer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ProjectFinancer $projectFinancer)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Auth\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectFinancer  $projectFinancer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProjectFinancer $projectFinancer)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectFinancer  $projectFinancer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProjectFinancer $projectFinancer)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectFinancer  $projectFinancer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProjectFinancer $projectFinancer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectFinancer  $projectFinancer
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProjectFinancer $projectFinancer)
    {
        //
    }
}
