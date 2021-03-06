<?php

namespace App\Jobs\Auth;

use App\Abstracts\Job;
use App\Models\Auth\User;
use DB;
use Exception;
use Throwable;

class UpdateUser extends Job
{
    protected $user;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $user
     * @param  $request
     */
    public function __construct($user, $request)
    {
        $this->user = $user;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return User
     * @throws Throwable
     */
    public function handle()
    {
        $this->authorize();

        // Do not reset password if not entered/changed
        if (empty($this->request['password'])) {
            unset($this->request['password']);
            unset($this->request['password_confirmation']);
        }

        DB::transaction(function () {
            $this->user->update($this->request->input());

            // Upload picture
            if ($this->request->file('picture')) {
                $media = $this->getMedia($this->request->file('picture'), 'users');

                $this->user->attachMedia($media, 'picture');
            }

            if ($this->request->has('roles')) {
                $this->user->roles()->sync($this->request->get('roles'));
            }

            if ($this->request->has('companies')) {
                $this->user->companies()->sync($this->request->get('companies'));
            }
        });

        return $this->user;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     * @throws Exception
     */
    public function authorize()
    {
        // Can't disable yourself
        if (($this->request->get('enabled', 1) == 0) && ($this->user->id == user()->id)) {
            $message = trans('auth.error.self_disable');

            throw new Exception($message);
        }
    }
}
