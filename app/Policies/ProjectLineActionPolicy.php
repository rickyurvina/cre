<?php

namespace App\Policies;

use App\Models\Auth\User;
use App\Models\Projects\Catalogs\ProjectLineAction;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectLineActionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param  \App\Models\ProjectLineAction  $projectLineAction
     * @return Response|bool
     */
    public function view(User $user, ProjectLineAction $projectLineAction)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param  \App\Models\ProjectLineAction  $projectLineAction
     * @return Response|bool
     */
    public function update(User $user, ProjectLineAction $projectLineAction)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param  \App\Models\ProjectLineAction  $projectLineAction
     * @return Response|bool
     */
    public function delete(User $user, ProjectLineAction $projectLineAction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param  \App\Models\ProjectLineAction  $projectLineAction
     * @return Response|bool
     */
    public function restore(User $user, ProjectLineAction $projectLineAction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param  \App\Models\ProjectLineAction  $projectLineAction
     * @return Response|bool
     */
    public function forceDelete(User $user, ProjectLineAction $projectLineAction)
    {
        //
    }
}
