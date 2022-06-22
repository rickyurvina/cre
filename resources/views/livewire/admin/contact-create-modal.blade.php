<div wire:ignore.self class="modal fade" id="add_contact_modal" tabindex="-1" role="dialog" aria-hidden="true" style="height: 100%;">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div wire:ignore.self class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ trans('general.add_new_contact') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="far fa-times"></i></span>
                </button>
            </div>

            <div class="modal-body">
                <div class="panel-content">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#general" role="tab" aria-selected="true" wire:ignore.self>
                                <i class="fal fa-user text-primary"></i>
                                <span class="hidden-sm-down ml-1">Información General</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#aditional" role="tab" aria-selected="false" wire:ignore.self>
                                <i class="fal fa-address-card"></i>
                                <span class="hidden-sm-down ml-1">Datos Adicionales</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#files" role="tab" aria-selected="false" wire:ignore.self>
                                <i class="fal fa-address-card"></i>
                                <span class="hidden-sm-down ml-1">Archivos Adjuntos</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content border border-top-0 p-3">
                        <div class="card tab-pane fade show active" id="general" role="tabpanel" wire:ignore.self>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6 required">
                                        <label class="form-label" for="name">{{ trans('general.names') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                            </div>
                                            <input type="text" wire:model.defer="names"
                                                   class="form-control bg-transparent @error('names') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.names')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('names') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6 required">
                                        <label class="form-label" for="surnames">{{ trans('general.surnames') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-id-card"></i>
                                                    </span>
                                            </div>
                                            <input type="text" wire:model.defer="surnames"
                                                   class="form-control bg-transparent @error('surnames') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.surnames')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('surnames') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6 required">
                                        <label class="form-label" for="email">{{ trans('general.email') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                            </div>
                                            <input type="email" wire:model.defer="email"
                                                   class="form-control bg-transparent @error('email') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.email')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6 required">
                                        <label class="form-label"
                                               for="phone">{{ trans('general.personal_phone') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                            </div>
                                            <input type="text" wire:model.defer="phone"
                                                   class="form-control bg-transparent @error('phone') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.personal_phone')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="form-label" for="birth">{{ trans('general.date_birth') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                            </div>
                                            <input type="date" wire:model.defer="birth"
                                                   class="form-control bg-transparent @error('birth') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.date_birth')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('birth') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="form-label" for="gender">{{ trans('general.gender') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                        <span class="input-group-text bg-transparent border-right-0">
                                                            <i class="fas fa-venus-mars"></i>
                                                        </span>
                                            </div>
                                            <select wire:model.defer="gender"
                                                    class="custom-select bg-transparent @error('gender') is-invalid @enderror">
                                                <option value=""
                                                        selected>{{ trans('general.form.select.field', ['field' => trans('general.gender')]) }}</option>
                                                @foreach($genders as $item)
                                                    <option value="{{ $item->code }}">{{ $item->description }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="form-label"
                                               for="businessPhone">{{ trans('general.business_phone') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-fax"></i>
                                                    </span>
                                            </div>
                                            <input type="text" wire:model.defer="businessPhone"
                                                   class="form-control bg-transparent @error('businessPhone') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.business_phone')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('businessPhone') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="form-label" for="gender">{{ trans('general.department') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                        <span class="input-group-text bg-transparent border-right-0">
                                                            <i class="fas fa-building"></i>
                                                        </span>
                                            </div>
                                            <select wire:model.defer="department"
                                                    class="custom-select bg-transparent @error('department') is-invalid @enderror">
                                                <option value=""
                                                        selected>{{ trans('general.form.select.field', ['field' => trans('general.department')]) }}</option>
                                                @foreach($departments as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">{{ $errors->first('department') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-12">
                                        <label class="form-label" for="photo">{{ trans('general.photo') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent border-right-0">
                                                        <i class="fas fa-image"></i>
                                                    </span>
                                            </div>
                                            <input type="file" wire:model="photo"
                                                   class="form-control bg-transparent @error('photo') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.photo')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('photo') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-12">
                                        <label class="form-label"
                                               for="personalNotes">{{ trans('general.personal_notes') }}</label>
                                        <textarea wire:model.defer="personalNotes" rows="3"
                                                  class="form-control bg-transparent @error('personalNotes') is-invalid @enderror">
                                            </textarea>
                                        <div class="invalid-feedback">{{ $errors->first('personalNotes') }}</div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card tab-pane fade" id="aditional" role="tabpanel" wire:ignore.self>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label class="form-label"
                                               for="jobTitle">{{ trans('general.job_title') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="fas fa-user-graduate"></i>
                                                </span>
                                            </div>
                                            <input type="text" wire:model.defer="jobTitle"
                                                   class="form-control bg-transparent @error('jobTitle') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.job_title')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('JobTitle') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="form-label" for="employerCost">{{ trans('general.employer_cost') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="far fa-dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" wire:model.defer="employerCost"
                                                   class="form-control bg-transparent @error('employerCost') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.employer_cost')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('employerCost') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-6">
                                        <label class="form-label" for="contractType">{{ trans('general.contract_type') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="far fa-file-contract"></i>
                                                </span>
                                            </div>
                                            <input type="text" wire:model.defer="contractType"
                                                   class="form-control bg-transparent @error('contractType') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.contract_type')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('contractType') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-3">
                                        <label class="form-label" for="contractStart">{{ trans('general.contract_start') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="date" wire:model.defer="contractStart"
                                                   class="form-control bg-transparent @error('contractStart') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.contract_start')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('contractStart') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-3">
                                        <label class="form-label" for="contractEnd">{{ trans('general.contract_end') }}</label>
                                        <div class="input-group bg-white shadow-inset-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-right-0">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="date" wire:model.defer="contractEnd"
                                                   class="form-control bg-transparent @error('contractEnd') is-invalid @enderror"
                                                   placeholder="{{ trans('general.form.enter', ['field' => trans('general.contract_end')]) }}">
                                            <div class="invalid-feedback">{{ $errors->first('contractEnd') }}</div>
                                        </div>
                                    </div>

                                    <div class="form-group col-12">
                                        <label class="form-label"
                                               for="workExperience">{{ trans('general.work_experience') }}</label>
                                        <textarea wire:model.defer="workExperience" rows="3"
                                                  class="form-control bg-transparent @error('workExperience') is-invalid @enderror">
                                                </textarea>
                                        <div class="invalid-feedback">{{ $errors->first('workExperience') }}</div>
                                    </div>

                                    <div class="form-group col-4">
                                        <livewire:components.list-view title="{{ __('general.competencies') }}"
                                                                       event="competenceAdded"
                                        />
                                    </div>

                                    <div class="form-group col-4">
                                        <livewire:components.list-view title="{{ __('general.working_skills') }}"
                                                                       event="skillAdded"
                                        />
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card tab-pane fade" id="files" role="tabpanel" wire:ignore.self>
                            <div class="card-body pt-0">
                                <div class="w-75 mx-auto">
                                    {{-- <div class="form-group col-12 pl-6 pt-4">
                                        <x-fileupload
                                                wire:model.defer="file"
                                                allowRevert
                                                allowRemove
                                                allowFileSizeValidation
                                                maxFileSize="4mb"></x-fileupload>
                                        @error('file')
                                        <div class="alert alert-danger fade show" role="alert">
                                            {{__('general.file_required')}}
                                        </div>
                                        @enderror
                                    </div> --}}
                                    <div>
                                        <x-empty-content>
                                            <x-slot name="img">
                                                <i class="fas fa-cloud-upload-alt" style="color: #2582fd;"></i>
                                            </x-slot>
                                            <x-slot name="title">
                                                {{ trans('general.files') }}
                                            </x-slot>
                                            <x-slot name="info">
                                                {{ trans('messages.info.empty_attachment') }}
                                            </x-slot>
                            
                                            <div class="d-flex flex-column">
                                                <a href="#" x-on:click.prevent="$refs.input.click()"><i class="fas fa-paperclip"></i> {{ trans('general.add_attachments') }}</a>
                                                <!-- Progress Bar -->
                                                <div x-show="isUploading">
                                                    <progress max="100" x-bind:value="progress"></progress>
                                                </div>
                                            </div>
                                        </x-empty-content>
                                        <!-- File Input -->
                                        <input type="file" wire:model="files" style="display: none" x-ref="input" id="fileInput">
                                    </div>
                                    <div class="row">

                                        <div class="form-group col-6">
                                            <label class="form-label"
                                                   for="observation">{{ trans('general.observations') }}</label>
                                            <textarea wire:model.defer="observation" rows="1" id="filecoments" class="form-control bg-transparent"></textarea>
                                            <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('observation',':message') }} </div>
                                        </div>

                                        <div class="form-group col-4">
                                            <label class="form-label" for="date">{{ trans('general.date') }}</label>
                                            <div class="input-group bg-white shadow-inset-2">
                                                <input class="form-control @error($date) is-invalid @enderror" id="date" type="month" name="date" wire:model.defer="date">
                                            </div>
                                            <div style="color:#fd3995; font-size: 0.6875rem ">{{ $errors->first('date',':message') }} </div>
                                        </div>

                                        <div class="form-group col-2 pt-4">
                                            <button class="btn btn-primary" wire:click="addFile">
                                                <i class="fas fa-plus pr-2"></i> {{ trans('general.add') }}
                                            </button>
                                        </div>

                                    </div>
                                </div>

                                <div class="frame-wrap">
                                    <table class="table m-0">
                                        <thead>
                                        <tr>
                                            <th>{{ __('general.file_name') }}</th>
                                            <th>{{ __('general.observations') }}</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($files as $item)
                                            <tr>
                                                <td>{{ $item['file']->getClientOriginalName() }}</td>
                                                <td>{{ $item['observation'] }}</td>
                                                <td><span wire:click="removeFile('{{ $item['name'] }}')" class="cursor-pointer trash"><i class="fas fa-trash
                                                text-danger"></i></span></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if($errors)
                    @foreach ($errors->all() as $error)
                        <div style="color:#fd3995; font-size: 0.6875rem">{{ trans('general.error_message_create_contact') }}({{ $error }})</div>
                        @break;
                    @endforeach
                @endif
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i
                            class="fas fa-times"></i> {{ trans('general.cancel') }}</button>
                <button class="btn btn-primary" wire:click="submit">
                    <i class="fas fa-save pr-2"></i> {{ trans('general.save') }}
                </button>
            </div>
        </div>
    </div>
</div>