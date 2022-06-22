<?php

namespace App\Http\Livewire\Process\NonConformities\Actions;

use App\Models\Process\NonConformitiesActions;
use Livewire\Component;
use function view;

class EditNonConformityActions extends Component
{
    public $action;

    protected $listeners = ['openEditAction'];


    public function render()
    {
        return view('livewire.process.non-conformities.actions.edit-non-conformity-actions');
    }

    public function openEditAction($id)
    {
        $this->action = NonConformitiesActions::find($id);
    }
    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['action']);
    }
}
