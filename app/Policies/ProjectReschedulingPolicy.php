<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\ProjectRescheduling;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectReschedulingPolicy
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
     * @param  \App\Models\ProjectRescheduling  $projectRescheduling
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ProjectRescheduling $projectRescheduling)
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
     * @param  \App\Models\ProjectRescheduling  $projectRescheduling
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ProjectRescheduling $projectRescheduling)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectRescheduling  $projectRescheduling
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ProjectRescheduling $projectRescheduling)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectRescheduling  $projectRescheduling
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ProjectRescheduling $projectRescheduling)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Auth\User  $user
     * @param  \App\Models\ProjectRescheduling  $projectRescheduling
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ProjectRescheduling $projectRescheduling)
    {
        //
    }
}
