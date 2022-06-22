<?php

namespace App\Http\Livewire\Poa;

use App\Models\Poa\Poa;
use App\Traits\Uploads;
use Livewire\Component;
use Livewire\WithFileUploads;
use Plank\Mediable\Media;

class PoaApprovedEdit extends Component
{
    use WithFileUploads, Uploads;

    public Poa $poa;

    public $files = [];

    protected $listeners = ['loadPoa' => 'mount'];

    public function mount($id = null)
    {
        if ($id) {
            $this->poa = Poa::find($id);
        }
    }

    public function updatedFiles()
    {
        $media = [];
        if (count($this->files)) {
            foreach ($this->files as $file) {
                $media[] = $this->getMedia($file, 'poa_approve')->id;
            }
            $this->poa->attachMedia($media, 'file');
            $this->dispatchBrowserEvent('pondReset');
            $this->files = [];
            $this->poa->loadMedia(['file']);
            $this->emit('poaUpdated');
        }
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

    public function deleteMedia($id)
    {
        Media::find($id)->delete();
        $this->poa->loadMedia(['file']);
    }

    /**
     * Reset Form on Cancel
     *
     */
    public function resetForm()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.poa.poa-approved-edit');
    }
}
