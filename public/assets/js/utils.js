function showMessage(alertClass, message) {

  // Fallback: si no llega un mensaje (p. ej. una respuesta de error sin texto),
  // evitamos el texto por defecto de Toastify ("Toastify is awesome!").
  if (!message) {
    message = 'Se produjo un error, consulta con tu administrador.';
  }

  var style = {  background: "linear-gradient(to right, #1771C8,rgb(121, 190, 255))" };
  
  if(alertClass === 'alert-info'){
    style = { background: "#1E90FF" };
  }
  if(alertClass === 'alert-success'){
    style = { background: "#1771C8" };
  }
  if(alertClass === 'alert-danger'){
    style = {  background: "linear-gradient(to right, #f85032, #e73827)" };
  }
  if(alertClass === 'alert-warning'){
    style = { background: "#FF8C00" };
  }

  Toastify({
    text: message,
    duration: 5000,
    close: true,
    gravity: "top",
    position: "right",
    stopOnFocus: true,
    style: style
  }).showToast();
}

function hideMessage() {
  $('#message__response').hide();
}

function actualizarCsrfToken(csrfNameNuevo, csrfHashNuevo) {
    csrfName = csrfNameNuevo;
    csrfHash = csrfHashNuevo;
    $('meta[name="csrf-token-name"]').attr('content', csrfName);
    $('meta[name="csrf-token"]').attr('content', csrfHash);
    $('input[name="' + csrfNameNuevo + '"]').val(csrfHashNuevo);
}

function actualizarCsrfTokenAjax(csrfNameNuevo, csrfHashNuevo) {
    csrfName = csrfNameNuevo;
    csrfHash = csrfHashNuevo;
}

// Remove item
$(document).on('click', '.removeItem', function() {
    let itemId = $(this).attr('itemId');
    let deleteItem = confirm('¿Estás seguro de que deseas eliminar este elemento, este cambio no puede revertirse?');
    if (deleteItem) {
        $.post(deleteURL, { id: itemId, [csrfName]: csrfHash }, handleResponse)
          .fail(() => showMessage('alert-danger', 'Error en la solicitud.'))
          .done(() => { $(this).closest('tr').remove(); });

    }
});

function handleResponseWithReload(resp) {
  try {
    if (resp.ok) {
      window.location.reload();
    } else {
      showMessage('alert-danger', resp.error);
      $('.modal').modal('hide');
    }
    if (resp.csrf_name && resp.csrf_token) {
      actualizarCsrfTokenAjax(resp.csrf_name, resp.csrf_token);
    }
  } catch (e) {
    showMessage('alert-danger', 'Error en el procesamiento de la respuesta.');
  }
}

function handleResponse(resp) {
  try {
    if (resp.ok) {
      let message = resp.message || 'Actualizado correctamente';
      showMessage('alert-success', message);
    } else {
      showMessage('alert-danger', resp.error);
      $('.modal').modal('hide');
    }
    if (resp.csrf_name && resp.csrf_token) {
      actualizarCsrfToken(resp.csrf_name, resp.csrf_token);
    }
  } catch (e) {
    showMessage('alert-danger', 'Error en el procesamiento de la respuesta.');
  }
}

document.addEventListener("DOMContentLoaded", function() {
  setTimeout(hideLoader, 1200); 
});

// Function to show the loader
function showLoader() {
  var loader = document.getElementById('loader');
  if (loader) {
      loader.style.display = 'block';
      var spinner = loader.querySelector('.spinner');
      if (spinner) {
          spinner.style.width = '0';
          spinner.style.animation = 'load 1.5s forwards';
      }
  }
}

// Function to hide the loader
function hideLoader() {
  var loader = document.getElementById('loader');
  if (loader) {
      loader.style.display = 'none';
  }
}

// Show loader on page load
window.addEventListener('load', function() {
  setTimeout(hideLoader, 1200);
});

// Show loader on AJAX start and hide on AJAX stop
$(document).ajaxStart(function() {
  showLoader();
}).ajaxStop(function() {
  setTimeout(hideLoader, 1200);
});

// Collapse sidebar on button click
$(document).on('click', '#sidebar__collapse__btn', function(){
  $("#left__sidebar").addClass("left-sidebar-collapse");
  $("#body__wrapper").addClass("body-wrapper-collapse");
  $("#app__header").addClass("app-header-collapse");
  $("#sidebar__collapse__container").hide();
  $("#sidebar__expand__container").show();
  localStorage.setItem('sidebarCollapsed', 'true');
});

// Expand sidebar on button click
$(document).on('click', '#sidebar__expand__btn', function(){
  $("#left__sidebar").removeClass("left-sidebar-collapse");
  $("#body__wrapper").removeClass("body-wrapper-collapse");
  $("#app__header").removeClass("app-header-collapse");
  $("#sidebar__expand__container").hide();
  $("#sidebar__collapse__container").show();
  localStorage.setItem('sidebarCollapsed', 'false');
});

// On page load, set sidebar state from localStorage without animation
$(function() {
  const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

  // Temporarily disable transitions
  $("#left__sidebar, #body__wrapper, #app__header").addClass('no-transition');

  if (isCollapsed) {
    $("#left__sidebar").addClass("left-sidebar-collapse");
    $("#body__wrapper").addClass("body-wrapper-collapse");
    $("#app__header").addClass("app-header-collapse");
    $("#sidebar__collapse__container").hide();
    $("#sidebar__expand__container").show();
  } else {
    $("#left__sidebar").removeClass("left-sidebar-collapse");
    $("#body__wrapper").removeClass("body-wrapper-collapse");
    $("#app__header").removeClass("app-header-collapse");
    $("#sidebar__expand__container").hide();
    $("#sidebar__collapse__container").show();
  }

  // Force reflow and then remove the no-transition class to restore transitions
  setTimeout(function() {
    $("#left__sidebar, #body__wrapper, #app__header").removeClass('no-transition');
  }, 5);
});