<?php

namespace App\Http\Livewire\Poa;

use App\Models\Poa\Poa;
use App\Traits\Uploads;
use Livewire\Component;
use Livewire\WithFileUploads;
use Plank\Mediable\Media;

class PoaApproved extends Component
{
    use WithFileUploads, Uploads;

    public Poa $poa;

    public $files;

    protected $listeners = ['loadPoa' => 'mount'];

    public function mount($id = null)
    {
        if ($id) {
            $this->poa = Poa::find($id);
        }
    }

    public function deleteMedia($id)
    {
        Media::find($id)->delete();
        $this->poa->loadMedia(['file']);
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

    public function render()
    {
        return view('livewire.poa.poa-approved');
    }

    public function submit()
    {
        $this->validate([
            'files' => 'required',
        ]);

        $media = $this->getMedia($this->files, 'poa')->id;
        $this->poa->attachMedia($media, 'file');

        $this->poa->status = Poa::STATUS_APPROVED;

        $this->poa->save();
        flash(trans_choice('messages.success.updated', 0, ['type' => trans_choice('general.poa', 0)]))->success();
        return redirect()->route('poa.poas', $this->poa->id);
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
}
