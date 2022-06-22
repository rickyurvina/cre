<?php

namespace App\Http\Livewire\Admin;

use App\Jobs\Admin\CreateContact;
use App\Models\Admin\Contact;
use App\Models\Admin\Department;
use App\Models\Common\Catalog;
use App\Traits\Jobs;
use App\Traits\Uploads;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use SebastianBergmann\Environment\Console;

class ContactCreateModal extends Component
{
    use WithFileUploads, Jobs, Uploads;

    public $file=null;
    public $files = [];
    public $observation=null;
    public $names=null;
    public $surnames=null;
    public $email=null;
    public $phone=null;
    public $birth=null;
    public $gender=null;
    public $businessPhone=null;
    public $jobTitle=null;
    public $photo=null;
    public $personalNotes=null;
    public $workExperience=null;
    public $contractStart=null;
    public $contractEnd=null;
    public $contractType=null;
    public $employerCost=null;
    public $department=null;
    public $idCompany;

    public $competenceItems = [];
    public $skillItems = [];
    public $date = null;

    protected $listeners = [
        'competenceAdded',
        'skillAdded',
        'resetForm'
    ];

    protected $rules = [
        'names' => 'required | max:255',
        'surnames' => 'required | max:255',
        'email' => 'required | email',
        'phone' => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/', 'min:7', 'max:13'],
        'employerCost' => 'numeric'
    ];

    public function render()
    {
        $genders = Catalog::catalogName('genders')->first()->details;
        $departments = Department::all();
        return view('livewire.admin.contact-create-modal', compact('genders', 'departments'));
    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(
            [
                'names',
                'surnames',
                'jobTitle',
                'email',
                'businessPhone',
                'phone',
                'gender',
                'birth',
                'photo',
                'personalNotes',
                'workExperience',
                'contractType',
                'employerCost',
                'contractStart',
                'contractEnd',
                'competenceItems',
                'skillItems',
                'department',
            ]
        );
    }

    public function submit()
    {
        $this->validate([
            'names' => 'required | max:255',
            'surnames' => 'required | max:255',
            'email' => 'required | email | max:255',
            'phone' => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/', 'min:7', 'max:13']
        ]);

        $data = [
            'names' => $this->names,
            'surnames' => $this->surnames,
            'job_title' => $this->jobTitle,
            'email' => $this->email,
            'business_phone' => $this->businessPhone,
            'personal_phone' => $this->phone,
            'gender' => $this->gender,
            'date_birth' => $this->birth,
            'photo' => $this->photo,
            'personal_notes' => $this->personalNotes,
            'enabled' => 1,
            'work_experience' => $this->workExperience,
            'contract_type' => $this->contractType,
            'employer_cost' => $this->employerCost != 0 ? $this->employerCost : null,
            'contract_start' => $this->contractStart,
            'contract_end' => $this->contractEnd,
            'competencies' => $this->competenceItems,
            'working_skills' => $this->skillItems,
            'company_id' => $this->idCompany?$this->idCompany:session('company_id'),
            'department' => $this->department
        ];

        $response = $this->ajaxDispatch(new CreateContact($data));
        $id = $response['data']->id;
        $contact = Contact::find($id);

        foreach ($this->files as $item) {
            $media = $this->getMedia($item['file'], 'contact', null, $item['observation'],$item['user_id'] , $item['date'])->id;
            $contact->attachMedia($media, 'file');
        }
        $this->date=null;

        flash(trans_choice('messages.success.added', 0, ['type' => trans_choice('general.contacts', 1)]))->success()->livewire($this);
        $this->resetForm();
        return redirect(route('companies.edit', ['company' => $this->idCompany]));
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->emit('toggleContactAddModal');
    }

    public function competenceAdded($elements)
    {
        $this->competenceItems = $elements;
    }

    public function skillAdded($elements)
    {
        $this->skillItems = $elements;
    }

    public function addFile()
    {
        $this->validate(
            [
                'file'=>'required',
                'observation'=>'required',
                'date'=>'required',
            ]
        );
        $fileElement = [];
        $fileElement['name'] = substr($this->file, 5);
        $fileElement['file'] = $this->file;
        $fileElement['observation'] = $this->observation;
        $fileElement['date'] = $this->date . "-01";
        $fileElement['user_id'] = Auth::id();
        array_push($this->files, $fileElement);
        $this->observation = '';
        $this->dispatchBrowserEvent('fileReset');
    }

    public function removeFile($name)
    {
        array_splice($this->files, array_search($name, array_column($this->files, 'name')), 1);
    }
}
