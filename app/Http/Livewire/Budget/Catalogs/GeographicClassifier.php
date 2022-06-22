<?php

namespace App\Http\Livewire\Budget\Catalogs;

use App\Models\Common\CatalogGeographicClassifier;
use Livewire\Component;

class GeographicClassifier extends Component
{
    public function render()
    {
        $geographicClassifier = CatalogGeographicClassifier::orderBy('id', 'asc')->collect();
        return view('livewire.budget.Catalogs.budget-classifier-geographic', compact('geographicClassifier'));
    }
}
