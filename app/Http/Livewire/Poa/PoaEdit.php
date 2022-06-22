<?php

namespace App\Http\Livewire\Poa;

use App\Jobs\Poa\UpdatePoa;
use App\Models\Admin\Department;
use App\Models\Auth\User;
use App\Models\Poa\Poa;
use App\Traits\Jobs;
use Livewire\Component;

class PoaEdit extends Component
{
    use Jobs;

    public $poaId;
    public $poa;

    public $poaReviewed;

    public $departments = [];
    public $existingDepartments = [];
    public $departmentsSelect = [];
    public $aux = [];

    protected $listeners = [
        'loadForm',
        'setToXApproveState',
    ];

    public function render()
    {
        $users = User::get();
        return view('livewire.poa.poa-edit', compact('users'));
    }

    public function loadForm($id)
    {
        $this->poaId = $id;
        $this->poa = Poa::find($id);
        $this->poaReviewed = $this->poa->reviewed;
        $this->departments = Department::all();
        if (isset($this->poa->departments)) {
            $this->aux = $this->poa->departments->pluck('id')->toArray();
        }
        foreach ($this->departments as $item) {
            $element = [];
            $element['id'] = $item->id;
            $element['name'] = $item->name;
            if (in_array($item->id, $this->aux)) {
                $element['selected'] = true;
            }
            array_push($this->existingDepartments, $element);
        }
        $this->emit('refreshDropdown');
    }

    public function resetModal()
    {
        $this->reset();
        return redirect()->route('poa.poas', $this->poaId);
    }

    /**
     * Update reviewed state
     *
     */
    public function reviewed()
    {
        $this->poa->reviewed = $this->poaReviewed;
        $this->poa->save();
    }

    /**
     * Update departments selected
     *
     */
    public function updatedDepartmentsSelect()
    {
        $this->poa->departments()->sync($this->departmentsSelect);
        $this->existingDepartments = [];
        foreach ($this->departments as $item) {
            $element = [];
            $element['id'] = $item->id;
            $element['name'] = $item->name;
            if (in_array($item->id, $this->departmentsSelect)) {
                $element['selected'] = true;
            }
            array_push($this->existingDepartments, $element);
        }
        $this->dispatchBrowserEvent('alert', [
            'title' => trans_choice('messages.success.updated', 1, ['type' => __('poa.responsable_unit')]),
            'icon' => 'success'
        ]);
        $this->emit('refreshDropdown');
    }

    /**
     * Update POA state to be approved
     *
     */
    public function setToXApproveState($id)
    {
        $data = [
            'status' => Poa::STATUS_IN_PROGRESS,
        ];

        $response = $this->ajaxDispatch(new UpdatePoa($id, $data));

        if ($response['success']) {
            flash(trans_choice('messages.success.updated', 0, ['type' => __('general.poa')]))->success();
        } else {
            flash(trans('messages.error.poa_state'))->error();
        }
        return redirect()->route('poa.poas', $id);
    }

    public function complete($id)
    {
        $this->dispatchBrowserEvent('complete', ['id' => $id]);
    }
}
