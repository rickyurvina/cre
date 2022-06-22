<?php

namespace App\Http\Livewire\Process;

use App\Http\Livewire\Components\Modal;
use App\Models\Admin\Department;
use App\Models\Auth\User;
use App\Models\Process\Process;
use App\Traits\Jobs;
use Illuminate\Validation\Rule;

class UpdateProcess extends Modal
{
    use  Jobs;

    public $process;
    public $processId;
    public $name;
    public $code;
    public $description;
    public $owner_id;
    public $users;
    public $userDepartments;
    public $departmentId;
    protected $listeners = ['openEditProcess'];

    public function rules()
    {
        return [
            'name' => 'required',
            'code' => [
                'required',
                'max:5',
                'alpha_num',
                'alpha_dash',
                Rule::unique('processes')
                    ->where('deleted_at', null)->ignore($this->processId)
            ],
            'owner_id' => 'required',
        ];
    }

    public function mount()
    {
        $this->userDepartments = Department::get();

    }

    public function render()
    {
        return view('livewire.process.update-process');
    }

    public function openEditProcess($id)
    {
        $this->process = Process::find($id);
        $this->processId = $id;
        $this->name = $this->process->name;
        $this->code = $this->process->code;
        $this->description = $this->process->description;
        $this->departmentId = $this->process->department_id;
        $this->owner_id = $this->process->owner_id;
        $this->users = User::whereHas('departments', function ($q) {
            $q->where('id', $this->departmentId);
        })->get();
    }

    public function save()
    {
        $data = $this->validate();
        $data += [
            'id' => $this->process->id,
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            'code' => $this->code,
            'description' => $this->description,
        ];
        $response = $this->ajaxDispatch(new \App\Jobs\Process\UpdateProcess($data, $this->process));
        if ($response['success']) {
            flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.module_process', 1)]))->success()->livewire($this);
            self::resetForm();
        } else {
            flash($response['message'])->error()->livewire($this);
        }
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset(['name', 'code', 'description', 'owner_id']);
        $this->emit('processCreated');
        $this->emit('toggleUpdateProcess');
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset(['name', 'code', 'description', 'owner_id']);
        $this->emit('toggleUpdateProcess');
    }

    public function updatedDepartmentId()
    {
        $this->owner_id='';
        $this->users = User::whereHas('departments', function ($q) {
            $q->where('id', $this->departmentId);
        })->get();
    }
}