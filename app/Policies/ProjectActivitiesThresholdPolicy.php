<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\ProjectActivitiesThreshold;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectActivitiesThresholdPolicy
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
     * @param  \App\Models\ProjectActivitiesThreshold  $projectActivitiesThreshold
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ProjectActivitiesThreshold $projectActivitiesThreshold)
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
     * @param  \App\Models\ProjectActivitiesThreshold  $projectActivitiesThreshold
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProjectActivitiesThreshold $projectActivitiesThreshold)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectActivitiesThreshold  $projectActivitiesThreshold
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProjectActivitiesThreshold $projectActivitiesThreshold)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectActivitiesThreshold  $projectActivitiesThreshold
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProjectActivitiesThreshold $projectActivitiesThreshold)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectActivitiesThreshold  $projectActivitiesThreshold
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProjectActivitiesThreshold $projectActivitiesThreshold)
    {
        //
    }
}
