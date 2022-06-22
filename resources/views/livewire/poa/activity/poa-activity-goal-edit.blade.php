<div>
    <form wire:submit.prevent="submitGoals()" method="post" autocomplete="off">
        <div class="d-flex flex-wrap">
            @foreach($goals as $item)
                <div class="d-flex flex-wrap align-items-center justify-content-between w-30 mr-2">
                    <div class="form-group w-50 pr-1">
                        <label class="form-label fw-700" for="goals.{{ $loop->index }}.goal">{{ $item['monthName'] }}</label>
                        <input type="text" id="goals.{{ $loop->index }}.goal" class="form-control" placeholder="Planificado" value="{{$item['goal']}}"
                               wire:model="goals.{{$loop->index}}.goal" @if($readOnlyGoal) readonly="readonly" @endif>
                        <span class="help-block">
                           Planificado.
                        </span>
                    </div>
                    <div class="form-group w-50">
                        <input type="text" id="goals.{{ $loop->index }}.progress" class="form-control" placeholder="Ejecutado" value="{{$item['progress']}}"
                               wire:model="goals.{{$loop->index}}.progress"  @if($readOnlyProgress) readonly="readonly" @endif >
                        <span class="help-block">
                             Ejecutado.
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="w-30 mx-auto d-flex">
            <div class="d-flex justify-content-center w-50">
                <x-label-detail>{{ trans('general.goal') }}</x-label-detail>
                <x-content-detail>{{ $this->total }}</x-content-detail>
            </div>
            <div class="d-flex justify-content-center w-50">
                <x-label-detail>{{ trans('general.progress') }}</x-label-detail>
                <x-content-detail>{{ $this->progress }}</x-content-detail>
            </div>
        </div>
        <div class="modal-footer justify-content-center">
            <x-form.modal.footer wirecancelevent="resetForm"></x-form.modal.footer>
        </div>
    </form>
</div>
