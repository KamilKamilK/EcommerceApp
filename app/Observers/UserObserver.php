<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Request;

class UserObserver
{

    /**
     * Handle the User "creating" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {

//        dd('Creating...');


        $user->number_of_orders = $user->number_of_orders +1;
        $user->save();
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
