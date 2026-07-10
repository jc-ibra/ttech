$(document).ready(function() {
  
    // JSTree Configuration
    const jstreeConfig = {
        core: {
            animation: 0,
            check_callback: true,
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
        plugins: [ "dnd", "search", "state", "types", "wholerow" ],
    };

    // jsTree initialization
    $('#folderTree').jstree(jstreeConfig);

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

    // Hide delete button
    // Hide delete button initially to prevent accidental deletions
    $('.delete__file').hide();
});