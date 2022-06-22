<?php

namespace App\Http\Livewire\Risks;

use App\Abstracts\TableComponent;
use App\Models\Common\Catalog;
use App\Models\Projects\Catalogs\ProjectRiskClassification;
use App\Models\Risk\Risk;
use Illuminate\Support\Facades\App;
use function view;

class IndexRisks extends TableComponent
{
    public string $search = '';

    public $scale_of_impacts;
    public $messages;
    public $model;
    public $modelId;
    public $class;

    protected $listeners = ['riskCreated' => 'render', 'riskUpdated' => 'render'];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => ''],
        'sortDirection' => ['except' => '']
    ];

    public function mount($modelId, $class)
    {
        $this->modelId = $modelId;
        $this->class = $class;
        $this->model = App::make($this->class)::withoutGlobalScope(\App\Scopes\Company::class)->find($this->modelId);
        $this->scale_of_impacts = Catalog::catalogName('risk_impact_probability_catalog')->first()->details;
        $this->messages = Catalog::CatalogName('help_messages')->first()->details;

    }

    public function render()
    {
        $search = $this->search;

        $risks = Risk::where('riskable_id', $this->modelId)->where('riskable_type', $this->class)
            ->when($this->sortField, function ($q) {
                $q->orderBy($this->sortField, $this->sortDirection);
            })->when($search, function ($q, $search) {
                $q->where('name', 'iLIKE', '%' . $search . '%');
            })->collect();
        foreach ($this->scale_of_impacts[0]->properties as $item) {
            $countRisk = $risks->where('probability', $item['probability'])->where('impact', $item['impact'])->count();
            $data[] = array_merge($item, ['radius' => $countRisk > 0 ? $countRisk : '']);
        }
        return view('livewire.risks.index-risks', ['risks' => $risks, 'scales' => $data]);
    }
}
