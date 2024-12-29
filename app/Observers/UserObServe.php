<?php

namespace App\Observers;

use App\Jobs\OneSignalJob;
use App\Models\User;

class UserObServe
{
    /**
     * Handle the User "created" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function creating(User $user)
    {
        if ($user->is_affiliate) {
            $user->affiliate = \Str::random();
        }
    }


    public function updating(User $user)
    {
        $oldPrice = $user->getOriginal('force_reset_password');
        $newPrice = $user->getAttribute('force_reset_password');
        if ($newPrice == true && $oldPrice != $newPrice) {
            $data = ['body' => 'يرجى تغيير كلمة المرور لضرورات امنية'];
            $job = new OneSignalJob($user->email, $data);
            dispatch($job);
        }
        $oldValue = $user->getOriginal('affiliate');

        if ($user->is_affiliate && empty($oldValue)) {
            $user->affiliate = \Str::random();
        }
        if ($user->isDirty('password')) {
            $user->force_reset_password = 0;
            $user->tokens()->delete();
            $token = $user->createToken('user')->plainTextToken;
            $user->token = $token;
        }




    }

    /**
     * Handle the User "updated" event.
     *
     * @param \App\Models\User $user
     * @return void
     */

    public function updated(User $user)
    {



    }

    /**
     * Handle the User "deleted" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
