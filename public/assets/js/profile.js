$(document).ready(function() {

    // Update Profile
    $('#updateProfile').on('submit', function(e) {
        e.preventDefault();

        const name      = $('#name').val();
        const lastname  = $('#lastname').val();

        // Validate that name is not empty
        if (name.length === 0) {
            showMessage('alert-danger', 'El nombre no puede estar vacío.');
            return;
        }

        // Send request
        $.post(baseUrl + `/profile`, { [csrfName]: csrfHash, name, lastname }, handleResponse)
          .fail(() => showMessage('alert-danger', 'Error en la solicitud.'));
    });
    
    // Update Password
    $('#updatePassword').on('submit', function(e) {
        e.preventDefault();

        const oldPassword   = $('#oldPassword').val();
        const password      = $('#password').val();
        const password_confirmation = $('#password_confirmation').val();

        // Validate that password has at least 6 characters
        if (password.length < 6) {
            showMessage('alert-danger', 'La nueva contraseña debe tener al menos 6 caracteres.');
            return;
        }

        // Validate if the passwords match
        if (password !== password_confirmation) {
            showMessage('alert-danger', 'Las nuevas contraseñas no coinciden.');
            return;
        }
        
        // Send request
        $.post(baseUrl + `/password`, { [csrfName]: csrfHash, oldPassword, password, password_confirmation }, handleResponse)
          .fail(() => showMessage('alert-danger', 'Error en la solicitud.'))
          .done(() => {
            $('#oldPassword').val('');
            $('#password').val('');
            $('#password_confirmation').val('');
          });
    });

    // Update Profile Photo Trigger
    $('#actualImage').on('click', function() {
        $('#photo').click();
    });

    // Update Profile Photo
    $('#photo').on('change', function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#actualImage').attr('src', e.target.result);
            $('#updatePhoto').submit();
        }
        reader.readAsDataURL(this.files[0]);
    });

    // Update Photo
    $('#updatePhoto').on('submit', function(e) {
        e.preventDefault();
        const photo = $('#photo').prop('files')[0];
        const formData = new FormData();
        formData.append('photo', photo);
        formData.append(csrfName, csrfHash);
        $.ajax({
            url: baseUrl + '/photo',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: handleResponse,
            error: () => showMessage('alert-danger', 'Error en la solicitud.')
        });
    });
});