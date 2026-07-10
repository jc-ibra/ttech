$(document).ready(function() {
  
    // FilePond configuration
    FilePond.registerPlugin(FilePondPluginImagePreview);
    FilePond.registerPlugin(FilePondPluginFileValidateType);
    FilePond.registerPlugin(FilePondPluginFileValidateSize);

    // FilePond configuration
    var pond = FilePond.create(document.getElementById('fileInput'), {
        credits: false, 
        acceptedFileTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/zip'],
        allowMultiple: false,
        maxFiles: 1,
        server: {
            url: base_url + '/files',
            process: {
                url: '/upload/file',
                method: 'POST',
            },
            revert:{
                url: '/revert',
                method: 'DELETE',
            },
        }
    });

    // JSTree Configuration
    const jstreeConfig = {
        core: {
            animation: 0,
            check_callback: true,
            // themes: { stripes: true },
            data: {
                url: base_url + '/documents/folder',
                dataType: 'json', 
            }
        },
        types: {
            "#": {
                max_children: 1,
                max_depth: 4,
                valid_children: ["root"]
            },
            default: {
                icon: "ti ti-folder",
                valid_children: ["default", "file"]
            },
        },
        plugins: [ "contextmenu", "dnd", "search", "state", "types", "wholerow" ],
        contextmenu: {
            items: function(node) {
                return {
                    create: {
                        label: 'Crear carpeta',
                        action: function() {
                            $('#folderTree').jstree().create_node(node, { text: 'Nueva carpeta' });
                        }
                    },
                    rename: {
                        label: 'Renombrar carpeta',
                        action: function() {
                            $('#folderTree').jstree().edit(node);
                        }
                    },
                    delete: {
                        label: 'Eliminar carpeta',
                        action: function() {
                            $('#folderTree').jstree().delete_node(node);
                        }
                    }
                };
            }
        }
    };

    // jsTree initialization
    $('#folderTree').jstree(jstreeConfig);

    // Update node name
    $('#folderTree').on('rename_node.jstree', function(e, data) {
        var nodeId = data.node.id;
        var newText = data.text;
        $.post(base_url + '/documents/folder/rename', { id: nodeId, name: newText, [csrfName]: csrfHash  }, function(resp){
            handleResponse(resp);
            if(resp.ok) $('#title__documents').html(newText)
            $('#folderTree').jstree().refresh();
        }).fail(() => showMessage('alert-danger', 'Error en la solicitud.'));
    });

    // Move node
    $('#folderTree').on('move_node.jstree', function(e, data) {
        var nodeId = data.node.id;
        var parentId = data.parent;
        $.post(base_url + '/documents/folder/move', { id: nodeId, parent: parentId, [csrfName]: csrfHash }, function(resp){
            handleResponse(resp);
            $('#folderTree').jstree().refresh();
        }).fail(() => showMessage('alert-danger', 'Error en la solicitud.'));
        }
    );

    // Create a new node
    $('#folderTree').on('create_node.jstree', function(e, data) {
        var parentId = data.node.parent;
        var newNode = data.node.text;
        $.post(base_url + '/documents/folder/create', { parent: parentId, name: newNode, [csrfName]: csrfHash }, function(resp){
            handleResponse(resp);
            $('#folderTree').jstree().refresh();
        }).fail(() => showMessage('alert-danger', 'Error en la solicitud.'));
    });

    // Delete a node
    $('#folderTree').on('delete_node.jstree', function(e, data) {
        var nodeId = data.node.id;
        if( confirm('¿Estás seguro de que deseas eliminar esta carpeta?, esto no puede revertirse') ){
            if(data.node.children.length > 0){
                showMessage('alert-danger', 'No puedes eliminar una carpeta con subcarpetas.');
            }else if(data.node.original.type == 'root'){
                showMessage('alert-danger', 'No puedes eliminar la carpeta raíz.');
            }else{
                $.post(base_url + '/documents/folder/delete', { id: nodeId, [csrfName]: csrfHash }, function(resp){
                    handleResponse(resp);
                    $('#folderTree').jstree().refresh();
                }
                ).fail(() => showMessage('alert-danger', 'Error en la solicitud.'));
            }
        }
        $('#folderTree').jstree().refresh();
    });

    // Select a node
    $('#folderTree').on('select_node.jstree', function(e, data) {

        // Get values
        var folderId = data.node.id;
        var folderTitle = data.node.text;
        $('#title__documents').text(folderTitle);
        $('#createDocumentText').text('Crear documento en ' + folderTitle);
        $('#parentInput').val(folderId);

        // Get files
        $.get(base_url + '/documents/file/' + folderId,  function(resp){
            if(resp.ok){
                $('#document__list__container').html(resp.files);
                var n = Array.isArray(resp.files_object) ? resp.files_object.length : 0;
                $('#doc__count').text(n + (n === 1 ? ' archivo' : ' archivos')).show();
            }
        }
        ).fail(() => showMessage('alert-danger', 'Error en la solicitud.'));

        // Toggle node open/close
        if(data.node.state.opened) {
            $('#folderTree').jstree().close_node(data.node);
        } else {
            $('#folderTree').jstree().open_node(data.node);
        }
    });

    // Block or unblock nameInput button
    $('#nameInput').on('input', function() {
        let nameInput = $('#nameInput').val();
        let createFolderButton = $('#handleCreateFolder');
        if (nameInput.trim().length > 0) {
            createFolderButton.removeAttr('disabled');
            $('#nameInputContainer').removeClass('input__error'); 
            $('#error__indicator').hide(); 
        }
        else {
            createFolderButton.attr('disabled', 'disabled');
            $('#nameInputContainer').addClass('input__error');  
            $('#error__indicator').show();
        }
    });

    // Create File
    $('#handleCreateFolder').on('click', function(e) {
        e.preventDefault();

        // Get values
        var name        = $('#nameInput').val();
        var document    = $('#parentInput').val();
        var file        = $('#documentForm input[name="file"]').val();
        var publish     = $('#publishCheck').is(':checked');

        // Validate values
        if(name.trim().length == 0){
            showMessage('alert-danger', 'El nombre de la carpeta no puede estar vacío.');
            return;
        }

        // Validate values
        if(!file){
            showMessage('alert-danger', 'Debes cargar un archivo');
            return;
        }

        // Send request
        $.post(base_url + '/documents/file/create', { publish: publish, file: file, document: document, name: name, [csrfName]: csrfHash }, function(resp){
            handleResponse(resp);
            if(resp.ok){
                $('#folderTree').jstree().refresh();
                $('#documentForm input[name="file"]').val('');
                $('#nameInput').val('');
                pond.removeFiles();
                $('#handleCreateFolder').attr('disabled', 'disabled');
                $('#createDocument').modal('hide');
            }
        }).fail(() => showMessage('alert-danger', 'Error en la solicitud.'));
    });

    // Delete file
    $('#document__list__container').on('click', '.delete__file', function(e){
        e.preventDefault();
        var fileId = $(this).attr('fileId');
        if( confirm('¿Estás seguro de que deseas eliminar este archivo?, esto no puede revertirse') ){
            $.post(base_url + '/documents/file/delete', { id: fileId, [csrfName]: csrfHash }, function(resp){
                handleResponse(resp);
                $('#folderTree').jstree().refresh();
            }).fail(() => showMessage('alert-danger', 'Error en la solicitud.'));
        }
    });

    // Show options
    $(document).on('click', '.document__list_item', function(e){
        
    });
});