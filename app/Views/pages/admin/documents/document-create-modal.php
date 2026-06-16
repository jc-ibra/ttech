<div class="modal" id="createDocument" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createDocument" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createDocumentText">Carga Documento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body feed">
                <div class="tinf__header">
                    <div class="tinf__profile">
                        <img src="<?= base_url(session('user')->photo) ?>" alt="<?= session('user')->name ?>">
                        <div class="tinf_profile_info">
                            <div>
                                <p><?= session('user')->name ?> <?= session('user')->lastname ?></p>
                                <span>Activo ahora</span>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="documentForm" enctype="multipart/form-data">
                    <div class="tinf__body">
                        <div class="mb-3">
                            <div class="input-group input__error" id="nameInputContainer">
                                <span class="input-group-text"><i class="ti ti-file"></i></span>
                                <input 
                                    placeholder="Nombre archivo"
                                    type="text" 
                                    id="nameInput" 
                                    name="name" 
                                    class="form-control" 
                                    required=""
                                    >
                            </div>
                            <small id="error__indicator" class="field-required">Coloca un nombre al archivo</small>
                        </div>
                        <div class="mb-3 form-check">
                            <input 
                                type="checkbox" 
                                class="form-check-input" 
                                id="publishCheck" 
                                name="publish" 
                                checked
                            >
                            <label class="form-check-label" for="publishCheck">Publicar en Trantor Informa</label>
                        </div>
                        <input 
                            type="hidden" 
                            id="parentInput" 
                            name="parent" 
                            required=""
                        >
                    </div>
                    <div class="tinf__body" id="uploadFileContainer">
                        <small>
                            Sube un archivo de hasta 5MB
                        </small>
                        <input type="file" 
                            class="filepond"
                            name="file" 
                            id="fileInput" 
                            data-allow-reorder="true"
                            data-max-file-size="5MB"
                            data-max-files="1"
                            accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation, application/zip"
                        >
                    </div>
                    <div class="modal-footer p-0 mt-2">
                        <button id="handleCreateFolder" disabled type="button" class="btn btn-primary w-100">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>