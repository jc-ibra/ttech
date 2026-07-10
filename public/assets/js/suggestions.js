$(document).ready(function() {
    
    // Get Suggestions
    function getSuggestions(){
        $.get(base_url + '/suggestions/get',  function(resp){
            if(resp.ok){
                $('#s__list__wrapper').html(resp.suggestions);
                var count = Array.isArray(resp.suggestionsObj) ? resp.suggestionsObj.length : 0;
                $('#s__count').text(count);
            }
        }
        ).fail(() => showMessage('alert-danger', 'Ocurrio un error al cargar la página, intente de nuevo.'));
    }
    getSuggestions();

    // Show empty-state placeholder / hide message detail
    function showEmptyDetail(){
        $('#suggestion__wrapper').hide();
        $('#suggestion__empty').show();
    }

    // Suggestion List Item Click
    $(document).on('click', '.s__list_item', function(){
        if($(window).width() < 768){
            $('#suggestion__list_main').hide();
            $('#suggestion__wrapper_main').show();
        }
        $('.s__list_item').removeClass('active');
        $(this).addClass('active');
        $(this).removeClass('new');
        let id = $(this).attr('suggestionId');
        $.get(base_url + '/suggestions/get/' + id,  function(resp){
            if(resp.ok){
                const { suggestion } = resp;
                $('#suggestion__empty').hide();
                $('#suggestion__wrapper').show();
                $('#s__title').text(suggestion.title);
                $('#s__name').text(suggestion.name + ' | ' + suggestion.email);
                $('#s__date').text(suggestion.created_at);
                $('#s__photo').attr('src', base_url + suggestion.author_photo);
                $('#s__message').text(suggestion.message);
                $('#s__markUnread').attr('suggestionId', suggestion.id);
                $('#s__delete').attr('suggestionId', suggestion.id);

                var isNew = suggestion.status === 'new';
                $('#s__status')
                    .text(isNew ? 'No leído' : 'Leído')
                    .removeClass('sg-status--new sg-status--open')
                    .addClass(isNew ? 'sg-status--new' : 'sg-status--open');
            }
        }
        ).fail(() => showMessage('alert-danger', 'Ocurrio un error al cargar la página, intente de nuevo.'));
    }); 

    // Mark Unread
    $('#s__markUnread').click(function(){
        let id = $(this).attr('suggestionId');
        $.post(base_url + '/suggestions/unread', { id, [csrfName]: csrfHash }, handleResponse)
            .fail(() => showMessage('alert-danger', 'Error en la solicitud.'))
            .done(() => {
                getSuggestions();
                showEmptyDetail();
                if($(window).width() < 768){
                    $('#suggestion__list_main').show();
                }
            }
        );
    });

    // Delete Suggestion
    $('#s__delete').click(function(){
        let id = $(this).attr('suggestionId');
        if(!confirm('¿Está seguro de eliminar esta sugerencia?')) return;
        $.post(base_url + '/suggestions/delete', { id, [csrfName]: csrfHash }, handleResponse)
            .fail(() => showMessage('alert-danger', 'Error en la solicitud.'))
            .done(() => {
                getSuggestions();
                showEmptyDetail();
                if($(window).width() < 768){
                    $('#suggestion__list_main').show();
                }
            }
        );
    });

    $('#s__back').on('click', function(){
        showEmptyDetail();
        $('#suggestion__list_main').show();
    });
});