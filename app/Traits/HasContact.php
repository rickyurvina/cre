<?php

namespace App\Traits;

use App\Models\Admin\Contact;

trait HasContact
{
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
