<?php

namespace App\Http\Livewire\Process\NonConformities\Actions;

use App\Models\Auth\User;
use App\Models\Process\NonConformitiesActions;
use App\Traits\Jobs;
use Illuminate\Validation\Rule;
use Livewire\Component;
use function view;

class CreateNonConformityActions extends Component
{

    use Jobs;

    public $nonConformityId;
    public $name;
    public $user_id;
    public $implantation_date;
    public $start_date;
    public $end_date;
    public $status = NonConformitiesActions::STATUS_IN_PROCESS;
    public $users;

    public function rules()
    {
        return [
            'name' => 'required|max:200',
            'implantation_date' => 'required|date',
            'status' => 'required',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'user_id' => 'required',
            'nonConformityId' => 'required'
        ];
    }

    public function mount($nonConformityId)
    {
        $this->nonConformityId = $nonConformityId;
        $this->users = User::get();
    }

    public function render()
    {
        return view('livewire.process.non-conformities.actions.create-non-conformity-actions');
    }

    public function save()
    {
        $data = $this->validate();
        $data += [
            'processes_non_conformities_id' => $this->nonConformityId,
        ];
        $response = $this->ajaxDispatch(new \App\Jobs\Process\CreateNonConformityAction($data));
        if ($response['success']) {
            flash(trans_choice('messages.success.added', 0, ['type' => trans('general.action')]))->success()->livewire($this);
            self::resetForm();
        } else {
            flash($response['message'])->error()->livewire($this);
        }
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset([
            'name',
            'implantation_date',
            'start_date',
            'end_date',
            'user_id',
        ]);
        $this->emit('actionCreated');
        $this->emit('toggleCreateAction');
    }

    public function closeModal()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset([
            'name',
            'implantation_date',
            'start_date',
            'end_date',
            'user_id',
        ]);
    }
}
