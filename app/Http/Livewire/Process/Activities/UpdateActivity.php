<?php

namespace App\Http\Livewire\Process\Activities;

use App\Http\Livewire\Components\Modal;
use App\Models\Process\Activity;
use App\Models\Process\Catalogs\GeneratedService;
use Livewire\Component;
use function view;

class UpdateActivity extends Modal
{
    public $activity;
    protected $listeners = ['openEditActivity'];

    public function render()
    {
        return view('livewire.process.activities.update-activity');
    }

    public function openEditActivity($id)
    {
        $this->activity = Activity::find($id);
    }

    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
