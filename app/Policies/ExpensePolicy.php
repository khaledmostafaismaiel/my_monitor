<?php

namespace App\Policies;

use App\NormalTransaction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\NormalTransaction  $expense
     * @return mixed
     */
    public function view(User $user, NormalTransaction $expense)
    {
        return $expense->user_id == $user->id ;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\NormalTransaction  $expense
     * @return mixed
     */
    public function update(User $user, NormalTransaction $expense)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\NormalTransaction  $expense
     * @return mixed
     */
    public function delete(User $user, NormalTransaction $expense)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\NormalTransaction  $expense
     * @return mixed
     */
    public function restore(User $user, NormalTransaction $expense)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\NormalTransaction  $expense
     * @return mixed
     */
    public function forceDelete(User $user, NormalTransaction $expense)
    {
        //
    }
}
