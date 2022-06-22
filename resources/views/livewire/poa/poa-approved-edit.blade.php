<div wire:ignore.self class="modal fade in" id="poa-approve-poa-edit" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-right modal-lg">
        <div class="modal-content">
            <div class="modal-header w-75 mx-auto">
                <h5>
                    @if($poa)
                        {{ $poa->name }}
                    @endif
                </h5>
                <button wire:click="resetForm" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fal fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body pt-0">
                    <div class="w-75 mx-auto">
                        <div class="d-flex align-items-center">
                            <span class="fs-2x w-40px"><i class="fal fa-paperclip"></i></span>
                            <span class="fs-2x fw-700">Archivos adjuntos</span>
                        </div>

                        <div class="d-flex align-items-center pl-6">
                            @if($poa)
                                <ul class="list-group list-group-files list-group-flush w-100">
                                    @foreach($poa->media as $media)
                                        <li class="list-group-item d-flex align-items-center justify-content-between show-child-on-hover"
                                            wire:key="{{ $loop->index }}">
                                            <a href="#" wire:click="download({{ $media->id }})">{{ $media->filename }}</a>
                                            <span class="text-danger cursor-pointer show-on-hover-parent"
                                                  wire:click="deleteMedia({{ $media->id }})"
                                                  wire:loading.remove
                                            ><i class="fas fa-trash"></i></span>
                                            <div class="spinner-border spinner-border-sm text-danger" role="status"
                                                 wire:loading
                                                 wire:target="deleteMedia({{ $media->id }})"
                                            >
                                                <span class="sr-only"></span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="pl-6">
                            <x-fileupload
                                    wire:model="files"
                                    multiple
                                    allowFileSizeValidation
                                    maxFileSize="4mb"></x-fileupload>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

