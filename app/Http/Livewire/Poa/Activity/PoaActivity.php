<?php

namespace App\Http\Livewire\Poa\Activity;

use App\Abstracts\TableComponent;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaProgram;
use App\Models\Strategy\PlanDetail;
use App\Models\Strategy\PlanRegisteredTemplateDetails;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class PoaActivity extends TableComponent
{
    use WithPagination;

    public int $idPoa;

    public $search = '';

    public $programs;

    public array $selectedPrograms = [];

    public bool $showProgramPanel = true;

    protected $listeners = ['colorPaletteChanged' => 'loadPrograms', 'activityCreated' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
//        'sortField' => ['except' => ''],
//        'sortDirection' => ['except' => '']
    ];

    public function mount(int $idPoa)
    {
        $this->idPoa = $idPoa;
        $this->loadPrograms();
    }

    public function render()
    {
        $activities = \App\Models\Poa\PoaActivity::whereHas('program', function (Builder $query) {
            $query->where('poa_id', $this->idPoa);
        })
            ->when(!empty($this->search), function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('code', 'iLike', '%' . $this->search . '%')
                        ->orWhere('name', 'iLike', '%' . $this->search . '%');
                });
            })
            ->when(count($this->selectedPrograms) > 0, function (Builder $query) {
                $query->whereIn('poa_program_id', $this->selectedPrograms);
            })
            ->orderBy('plan_detail_id', 'asc')
            ->orderBy('indicator_id', 'asc')
            ->with(['responsible', 'indicator', 'planDetail', 'program'])
            ->withCount('comments')
            ->paginate(setting('default.list_limit', '25'));
        return view('livewire.poa.activity.poa-activities', compact('activities'));
    }

    public function loadPrograms()
    {
        $this->programs = PoaProgram::with(['planDetail'])->where('poa_id', $this->idPoa)->get();
    }

    public function cleanFilters()
    {
        $this->reset(['search','selectedPrograms']);
    }

    public function delete($id)
    {
        \App\Models\Poa\PoaActivity::find($id)->delete();
        flash(trans_choice('messages.success.deleted', 1, ['type' => trans_choice('general.activities', 1)]))->success()->livewire($this);
    }
}
