<?php

namespace App\Http\Livewire\Poa;

use App\Models\Admin\Company;
use App\Models\Poa\Poa;
use App\Models\Poa\PoaActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class PoaReports extends Component
{
    use WithPagination;

    public $companyId = null;

    public Collection $poas, $companies, $years, $provinces, $cantones;

    public $company;

    public array $selectedCanton = [];

    public array $selectedYears = [];

    public array $selectedProvince = [];

    public array $filtersSelected = [];

    public array $states = [];

    public array $selectedStates = [];

    public $search = '';

    protected $paginationTheme = 'bootstrap';

    public $programs;


    public function updatedSearch()
    {
        $this->filter();
    }

    public function cleanAllFilters()
    {
        $this->filtersSelected = [];
        $this->selectedProvince = [];
        $this->selectedStates = [];
        $this->mount();
    }

    public function mount()
    {
        $this->company = new Company;
        $this->companyId = session('company_id');
        $this->company = Company::find($this->companyId);
        $this->states = [PoaActivity::STATUS_WARNING, PoaActivity::STATUS_RISK, PoaActivity::STATUS_ON_TIME];
        if (is_null($this->company->parent)) {
            $this->companies = Company::whereIn('level', [2, 1])->get();
            $this->provinces = $this->companies;
            $arr = $this->companies->pluck('id', 'id');
            $this->load($arr->toArray(), [date("Y")]);
        } else {
            if (count($this->company->children) > 0) {
                $this->cantones = $this->company->children;
                $this->provinces = Company::where('id', $this->companyId)->get();
                $arr = $this->cantones->pluck('id', 'id');
                $arr[$this->companyId] = $this->companyId;
                $this->load($arr->toArray(), [date("Y")]);
            } else {
                $this->provinces = $this->company->parent()->get();
                $this->cantones = Company::where('id', $this->companyId)->get();
                $this->load([$this->companyId], [date("Y")]);
            }
        }
        $this->years = $this->poas->pluck('year')->unique();

    }

    public function updatedSelectedProvince()
    {
        $this->cantones = Company::whereIn('parent_id', $this->selectedProvince)->get();
    }

    public function filter()
    {
        $filters = array_merge($this->selectedCanton, $this->selectedProvince);
        if (count($filters) == 0 && count($this->selectedStates) == 0) {
            $this->filtersSelected = [];
            $this->mount();
        } else {
            if (count($filters) > 0) {
                if (count($this->selectedYears) > 0) {
                    $this->load($filters, $this->selectedYears);
                } else {
                    $this->load($filters, [date("Y")]);
                }
            }
            $this->filtersSelected = [];
            if (count($this->selectedProvince) > 0) {
                $arr_ = Company::whereIn('id', $this->selectedProvince)->get();
                foreach ($arr_ as $item) {
                    $this->filtersSelected[] =
                        [
                            'name' => $item->name,
                            'type' => 'province'
                        ];
                }
            }
            if (count($this->selectedCanton) > 0) {
                $arr_ = Company::whereIn('id', $this->selectedCanton)->get();
                foreach ($arr_ as $item) {
                    $this->filtersSelected[] =
                        [
                            'name' => $item->name,
                            'type' => 'canton'
                        ];
                }
            }
            if (count($this->selectedYears) > 0) {
                foreach ($this->selectedYears as $item) {
                    $this->filtersSelected[] =
                        [
                            'name' => $item,
                            'type' => 'year'
                        ];
                }
            }
            if (count($this->selectedStates) > 0) {
                foreach ($this->selectedStates as $item) {
                    $this->filtersSelected[] =
                        [
                            'name' => $item,
                            'type' => 'state'
                        ];
                }
            }
        }
        $this->emit('toggleDropDownFilter');

    }

    public function cleanFilter($type)
    {
        switch ($type) {
            case 'province':
                $this->selectedProvince = [];
                break;
            case 'canton':
                $this->selectedCanton = [];
                break;
            case 'year':
                $this->selectedYears = [];
                break;
            case 'state':
                $this->selectedStates = [];
                break;
        }
        $this->filter();
    }

    private function load(array $companies, array $years)
    {
        $search = $this->search;
        $this->poas = Poa::with([
            'programs.planDetail',
            'programs.poaActivities' => function ($query) use ($search) {
                $query->when($search != '', function ($query) use ($search) {
                    $query->where('poa_activities.name', 'iLIKE', '%' . $search . '%');
                });
            }
        ])
            ->whereHas('programs.poaActivities', function ($query) use ($search) {
                $query->when($search != '', function ($query) use ($search) {
                    $query->where('poa_activities.name', 'iLIKE', '%' . $search . '%');
                });
            })
            ->whereIn('company_id', $companies)
            ->whereIn('year', $years)
            ->orderBy('company_id', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.poa.poa-reports');
    }
}
