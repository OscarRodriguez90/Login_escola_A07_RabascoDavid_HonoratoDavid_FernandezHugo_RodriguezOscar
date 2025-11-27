
window.onload = function() {
    //Eventos para validaciones
    if (document.getElementById('username')) {
        document.getElementById('username').onblur = validarUsername;
    }
    if (document.getElementById('email')) {
        document.getElementById('email').onblur = validarEmail;
    }
    if (document.getElementById('password')) {
        document.getElementById('password').onblur = validarPassword;
    }
}

function validarUsername() {
    const username = document.getElementById('username').value.trim();
    const error = document.getElementById('username-error');
    if (username.length < 3) {
        error.textContent = 'El nombre de usuario debe tener al menos 3 caracteres.';
        error.style.color = 'red';
        return false;
    } else {
        error.textContent = '';
        return true;
    }
}

function validarEmail() {
    const email = document.getElementById('email').value.trim();
    const error = document.getElementById('email-error');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        error.textContent = 'Por favor ingresa un correo electrónico válido.';
        error.style.color = 'red';
        return false;
    } else {
        error.textContent = '';
        return true;
    }
}

function validarPassword() {
    const password = document.getElementById('password').value.trim();
    const error = document.getElementById('password-error');
    if (password.length < 6) {
        error.textContent = 'La contraseña debe tener al menos 6 caracteres.';
        error.style.color = 'red';
        return false;
    } else {
        error.textContent = '';
        return true;
    }
}