<?php

namespace App\Http\Livewire\Process;

use App\Models\Admin\Department;
use App\Models\Auth\User;
use App\Traits\Jobs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateProcess extends Component
{
    use Jobs;

    public $code;
    public $name;
    public $ownerId;
    public $departmentId;
    public $description;
    public $users;
    public $userDepartments;

    public function rules()
    {
        return [
            'code' => [
                'required',
                'max:5',
                'alpha_num',
                'alpha_dash',
                Rule::unique('processes')
                    ->where('deleted_at', null)
            ],
            'name' => 'required|max:200',
            'ownerId' => 'required',
            'departmentId' => 'required',
        ];
    }

    public function mount()
    {
        $this->userDepartments = Department::get();
    }

    public function render()
    {
        return view('livewire.process.create-process');
    }

    public function save()
    {
        $data = $this->validate();

        $data += [
            'name' => $this->name,
            'owner_id' => $this->ownerId,
            'code' => $this->code,
            'description' => $this->description,
            'department_id' => $this->departmentId,
        ];
        $response = $this->ajaxDispatch(new \App\Jobs\Process\CreateProcess($data));
        if ($response['success']) {
            flash(trans_choice('messages.success.added', 0, ['type' => trans_choice('general.module_process', 1)]))->success()->livewire($this);
            self::resetForm();
            $this->emit('toggleCreateProcess');
        } else {
            flash($response['message'])->error()->livewire($this);
        }
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset(['name', 'code', 'description', 'ownerId', 'departmentId']);
        $this->emit('processCreated');
    }

    public function updatedDepartmentId()
    {
        $this->ownerId='';
        $this->users = User::whereHas('departments', function ($q) {
            $q->where('id', $this->departmentId);
        })->get();
    }
}
