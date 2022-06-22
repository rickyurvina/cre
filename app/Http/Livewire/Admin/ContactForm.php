<?php

namespace App\Http\Livewire\Admin;

use App\Jobs\Admin\DeleteContact;
use App\Models\Admin\Contact;
use Livewire\Component;

class ContactForm extends Component
{
    public $idCompany;
    public $idContactEdit;

    protected $listeners = [
        'renderContactForm' => 'render'
    ];


    public function render()
    {
        $contacts = Contact::companyId($this->idCompany)->collect();
        return view('livewire.admin.contact-form', compact('contacts'), ['company' => $this->idCompany]);
    }

    public function delete($idContact)
    {
        dispatch_now(new DeleteContact([
            'id' => $idContact
        ]));
        flash(trans_choice('messages.success.deleted', 0, ['type' => trans_choice('general.contacts', 1)]))->success()->livewire($this);
        return redirect(route('companies.edit', ['company' => $this->idCompany]));
    }

    public function open()
    {
        $this->resetInputFields();
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->names = '';
        $this->surnames = '';
        $this->email = '';
        $this->phone = '';
        $this->birth = '';
        $this->gender = '';
        $this->businessPhone = '';
        $this->jobTitle = '';
        $this->photo = '';
        $this->personalNotes = '';
    }
}
