<?php

namespace App\Http\Livewire\Admin;

use App\Jobs\Admin\UpdateContact;
use App\Models\Admin\Contact;
use App\Models\Admin\Department;
use App\Models\Common\Catalog;
use App\Scopes\Company;
use App\Traits\Jobs;
use App\Traits\Uploads;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class ContactEditModal extends Component
{
    use WithFileUploads, Uploads, Jobs;

    public $fileEdit;
    public $filesEdit = [];
    public $observationEdit;

    public $idCompany;
    public $idContactEdit;
    public $contact;

    public $names;
    public $surnames;
    public $email;
    public $phone;
    public $birth;
    public $gender;
    public $department;
    public $businessPhone;
    public $jobTitle;
    public $photo;
    public $personalNotes;
    public $workExperience;
    public $contractStart;
    public $contractEnd;
    public $contractType;
    public $employerCost;
    public ?Collection $genders = null;
    public ?Collection $departments = null;

    public $competenceItems = [];
    public $skillItems = [];

    public $date = null;

    protected $listeners = [
        'competenceEdited',
        'skillEdited',
        'openContactEditModal' => 'edit',
    ];

    protected $rules = [
        'names' => 'required',
        'surnames' => 'required',
        'email' => 'required|email',
        'phone' => 'required'
    ];

    public function edit($idContact = null)
    {
        $this->resetForm();
        if ($idContact) {
            $this->idContactEdit = $idContact;
            $contact = Contact::withoutGlobalScope(Company::class)->find($idContact);
            $this->names = $contact->names;
            $this->surnames = $contact->surnames;
            $this->email = $contact->email;
            $this->phone = $contact->personal_phone;
            $this->birth = $contact->date_birth;
            $this->businessPhone = $contact->business_phone;
            $this->jobTitle = $contact->job_title;
            $this->photo = $contact->photo;
            $this->personalNotes = $contact->personal_notes;
            $this->gender = $contact->gender;
            $this->department = $contact->departments->first()->id ?? null;
            $this->employerCost = $contact->employer_cost;
            $this->workExperience = $contact->work_experience;
            $this->contractType = $contact->contract_type;
            $this->contractStart = $contact->contract_start;
            $this->contractEnd = $contact->contract_end;
            $this->competenceItems = $contact->competencies;
            $this->skillItems = $contact->working_skills;
            $this->emit('loadEditedData', 'competence', $this->competenceItems);
            $this->emit('loadEditedData', 'skill', $this->skillItems);
            $this->genders = Catalog::catalogName('genders')->first()->details;
            $this->departments = Department::all();
            $this->filesEdit = [];
            $this->idCompany = $this->idCompany?$this->idCompany:session('company_id');
            foreach ($contact->media as $item) {
                $fileElement = [];
                $fileElement['id'] = $item->id;
                $fileElement['name'] = $item->filename;
                $fileElement['file'] = null;
                $fileElement['observation'] = $item->comments;
                $fileElement['date'] = $item->date;
                $fileElement['delete'] = false;
                array_push($this->filesEdit, $fileElement);
            }
        }
    }

    public function update()
    {
        $this->validate();
        if ($this->employerCost == '') {
            $this->employerCost = null;
        }

        dispatch_now(new UpdateContact([
            'id' => $this->idContactEdit,
            'names' => $this->names,
            'surnames' => $this->surnames,
            'job_title' => $this->jobTitle,
            'email' => $this->email,
            'business_phone' => $this->businessPhone,
            'personal_phone' => $this->phone,
            'gender' => $this->gender,
            'department' => $this->department,
            'date_birth' => $this->birth,
            'photo' => $this->photo,
            'personal_notes' => $this->personalNotes,
            'work_experience' => $this->workExperience,
            'contract_type' => $this->contractType,
            'employer_cost' => $this->employerCost,
            'contract_start' => $this->contractStart,
            'contract_end' => $this->contractEnd,
            'competencies' => $this->competenceItems,
            'working_skills' => $this->skillItems,
        ]));

        $contact = Contact::find($this->idContactEdit);

        foreach ($this->filesEdit as $item) {
            if ($item['file']) {
                $media = $this->getMedia($item['file'], 'contact', null, $item['observation'])->id;
                $contact->attachMedia($media, 'file');
            } else {
                if ($item['delete']) {
                    $contact->detachMedia($item['id']);
                }
            }
        }
        $this->date = null;

        flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.contacts', 1)]))->success()->livewire($this);
        $this->resetForm();
        return redirect(route('companies.edit', ['company' => $this->idCompany]));
    }

    public function render()
    {
        return view('livewire.admin.contact-edit-modal');
    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function competenceEdited($elements)
    {
        $this->competenceItems = $elements;
    }

    public function skillEdited($elements)
    {
        $this->skillItems = $elements;
    }

    public function removeEditFile($name)
    {
        $key = array_search($name, array_column($this->filesEdit, 'name'));
        if ($this->filesEdit[$key]['file']) {
            $removedElement = array_splice($this->filesEdit, array_search($name, array_column($this->filesEdit, 'id')), 1);
        } else {
            $this->filesEdit[$key]['delete'] = true;
        }
    }

    public function addEditFile()
    {
        $this->validate(
            [
                'fileEdit' => 'required',
                'observationEdit' => 'required',
                'date' => 'required',
            ]
        );
        $fileElement = [];
        $fileElement['id'] = substr($this->fileEdit, 5);
        $fileElement['name'] = $this->fileEdit->getClientOriginalName();
        $fileElement['file'] = $this->fileEdit;
        $fileElement['observation'] = $this->observationEdit;
        $fileElement['delete'] = false;
        array_push($this->filesEdit, $fileElement);
        $this->observationEdit = '';
        $this->dispatchBrowserEvent('fileReset');
    }
}
