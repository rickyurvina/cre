<?php

namespace App\Jobs\Admin;

use App\Abstracts\Job;
use App\Models\Admin\Contact;
use App\Scopes\Company;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateContact extends Job
{
    public $request;

    /**
     * Create a new job instance.
     *
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Exception
     */
    public function handle()
    {
        try {
            DB::transaction(function () {
                $contact = Contact::withoutGlobalScope(Company::class)->find($this->request->id);
                $contact->update($this->request->except(['department']));
                $contact->departments()->sync($this->request->department);
            });
        } catch (Exception $e) {
            return $e;
        }
    }
}
