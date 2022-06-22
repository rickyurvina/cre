<?php

namespace App\Http\Livewire\Poa;

use App\Http\Livewire\Components\Modal;
use App\Jobs\Poa\UpdatePoaActivity;
use App\Models\Common\Catalog;
use App\Models\Poa\PoaActivity;
use App\Traits\Jobs;
use App\Traits\Uploads;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Plank\Mediable\Media;

class PoaActivityEdit extends Modal
{
    use WithFileUploads, Uploads, Jobs;

    public $activity;

    public $poaActivityLocationId;

    public $poaActivityLocationDescription;

    public $fileEdit = null;
    public $filesEdit = [];
    public $beneficiariesSelected = [];
    public $existingBeneficiaires = [];
    public array $auxBeneficiaries;
    public $beneficiaries = null;


    public $observationEdit;
    public $date = null;

    protected $rules = [
        'name'=>'required|max:255',
        'observationEdit' => 'required',
        'date' => 'required',
        'fileEdit' => 'file'
    ];

    protected $listeners = [
        'show' => 'show',
        'commentAdded' => 'init',
        'locationUpdated'
    ];

    public function show(...$arg)
    {
        $this->activity = PoaActivity::find($arg[0]);
        $this->poaActivityLocationId = $this->activity->location_id;
        $this->poaActivityLocationDescription = $this->activity->location ? $this->activity->location->getPath() : '';
        $this->beneficiaries = Catalog::catalogName('beneficiaries')->first()->details;
        if (isset($this->activity->beneficiaries)) {
            $this->existingBeneficiaires = $this->activity->beneficiaries->pluck('id');
            $this->auxBeneficiaries = array();
            foreach ($this->existingBeneficiaires as $index => $ind) {
                $this->auxBeneficiaries[$index] = $ind;
            }
        }
        $this->init();
        parent::show();
    }

    public function init()
    {
        $this->filesEdit = [];

        if ($this->activity) {
            $this->activity->loadMedia(['file']);
            $this->activity->load(['indicator.indicatorable', 'comments','catalog']);
            $this->dispatchBrowserEvent('loadLocation', ['id' => $this->poaActivityLocationId, 'description' => $this->poaActivityLocationDescription]);
            foreach ($this->activity->media as $item) {
                $fileElement = [];
                $fileElement['id'] = $item->id;
                $fileElement['name'] = $item->filename;
                $fileElement['file'] = null;
                $fileElement['observation'] = $item->comments;
                $fileElement['user_id'] = Auth::id();
                $fileElement['date'] = $item->date;
                $fileElement['delete'] = false;
                array_push($this->filesEdit, $fileElement);
            }
            $this->render();
        }
    }


    public function deleteActivity($id)
    {
        PoaActivity::find($id)->delete();
        $this->show = false;
        $this->activity = null;
        $this->emit('activityDeleted');
    }

    public function updatedBeneficiariesSelected()
    {
        $this->activity->beneficiaries()->sync($this->beneficiariesSelected);
        flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.poa_activity_beneficiaries', 1)]))->success()->livewire($this);
    }

    public function deleteMedia($id)
    {
        Media::find($id)->delete();
        $this->activity->loadMedia(['file']);
        $this->init();
        $this->emit('activityUpdated');
    }

    public function download($id)
    {
        $media = Media::find($id);
        if ($media->fileExists()) {
            return response()->streamDownload(
                function () use ($media) {
                    $stream = $media->stream();
                    while ($bytes = $stream->read(1024)) {
                        echo $bytes;
                    }
                },
                $media->basename,
                [
                    'Content-Type' => $media->mime_type,
                    'Content-Length' => $media->size
                ]
            );
        } else {
            flash(trans('general.file_not_exist'))->error()->livewire($this);
        }
    }

    public function locationUpdated()
    {
        $data = [
            'location_id' => $this->poaActivityLocationId,
        ];

        $response = $this->ajaxDispatch(new UpdatePoaActivity($this->activity->id, $data));
        $this->dispatchBrowserEvent('alert', [
            'title' => trans_choice('messages.success.updated', 1, ['type' => __('general.poa_activity_location')]),
            'icon' => 'success'
        ]);
    }

    public function render()
    {
        $months_list = array(
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        );
        $files = [
            '01' => [],
            '02' => [],
            '03' => [],
            '04' => [],
            '05' => [],
            '06' => [],
            '07' => [],
            '08' => [],
            '09' => [],
            '10' => [],
            '11' => [],
            '12' => [],
        ];
        foreach ($this->filesEdit as $index => $item_) {
            $month = date('m', strtotime($item_['date']));
            $key = $month;
            if (!array_key_exists($key, $files)) {
                $files[$key][] = $item_;
            } else {
                $files[$key][] = $item_;
            }
        }

        $catalogs=Catalog::catalogName('beneficiaries')->first()->details;
        return view('livewire.poa.poa-activity-edit', compact('files', 'months_list','catalogs'));
    }

    public function addEditFile()
    {
        $this->validate();
        $fileElement = [];
        $fileElement['id'] = substr($this->fileEdit, 5);
        $fileElement['name'] = $this->fileEdit->getClientOriginalName();
        $fileElement['file'] = $this->fileEdit;
        $fileElement['observation'] = $this->observationEdit;
        $fileElement['user_id'] = Auth::id();
        $fileElement['date'] = $this->date . "-01";
        $fileElement['delete'] = false;
        array_push($this->filesEdit, $fileElement);
        $this->observationEdit = '';

        foreach ($this->filesEdit as $item) {
            if ($item['file']) {
                $media = $this->getMedia($item['file'], 'poa_activities', null, $item['observation'], $item['user_id'], $item['date'])->id;
                $this->activity->attachMedia($media, 'file');
            } else {
                if ($item['delete']) {
                    $this->activity->detachMedia($item['id']);
                }
            }
        }
        $this->date = null;
        $this->fileEdit = null;

        $this->dispatchBrowserEvent('pondReset');
        $this->activity->loadMedia(['file']);
        $this->dispatchBrowserEvent('alert', [
            'title' => trans_choice('messages.success.added', 0, ['type' => __('general.file_name')]),
            'icon' => 'success'
        ]);
        $this->emit('activityUpdated');
    }

    public function removeEditFile($name)
    {
        $key = array_search($name, array_column($this->filesEdit, 'name'));
        if ($this->filesEdit[$key]['file']) {
            array_splice($this->filesEdit, array_search($name, array_column($this->filesEdit, 'id')), 1);
        } else {
            $this->filesEdit[$key]['delete'] = true;
            $this->deleteMedia($this->filesEdit[$key]['id']);
        }
        $this->dispatchBrowserEvent('alert', [
            'title' => trans_choice('messages.success.deleted', 0, ['type' => __('general.file_name')]),
            'icon' => 'success'
        ]);
    }

}
