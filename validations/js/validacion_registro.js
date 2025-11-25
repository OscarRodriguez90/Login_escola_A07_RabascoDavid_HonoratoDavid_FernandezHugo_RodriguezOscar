// ==========================
// Validaciones del lado del cliente
// ==========================

// Validar email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Validar longitud mínima
function validarLongitud(texto, minimo) {
    return texto.length >= minimo;
}

// Mostrar error debajo del input
function mostrarError(input, mensaje) {
    const errorBox = document.getElementById(input.id + '-error');
    if (mensaje) {
        errorBox.textContent = mensaje;
        errorBox.style.color = '#e74c3c';
    } else {
        errorBox.textContent = '';
    }
}

// ==========================
// Validaciones específicas de cada campo
// ==========================
function validarUsername() {
    const input = document.getElementById('username');
    const valor = input.value.trim();
    if (valor === '') {
        mostrarError(input, 'El nombre de usuario no puede estar vacío');
        return false;
    } else if (!/^[a-zA-Z0-9]+$/.test(valor)) {
        mostrarError(input, 'Solo se permiten letras y números');
        return false;
    } else if (!validarLongitud(valor, 3)) {
        mostrarError(input, 'Debe tener al menos 3 caracteres');
        return false;
    } else {
        mostrarError(input, '');
        return true;
    }
}

function validarEmailInput() {
    const input = document.getElementById('email');
    const valor = input.value.trim();
    if (valor === '') {
        mostrarError(input, 'El email no puede estar vacío');
        return false;
    } else if (!validarEmail(valor)) {
        mostrarError(input, 'Formato de email inválido');
        return false;
    } else {
        mostrarError(input, '');
        return true;
    }
}

function validarPasswordInput() {
    const input = document.getElementById('password');
    const valor = input.value;
    if (valor === '') {
        mostrarError(input, 'La contraseña no puede estar vacía');
        return false;
    } else if (!validarLongitud(valor, 6)) {
        mostrarError(input, 'Debe tener al menos 6 caracteres');
        return false;
    } else {
        mostrarError(input, '');
        return true;
    }
}

function validarConfirmPassword() {
    const input = document.getElementById('confirm');
    const valor = input.value;
    const password = document.getElementById('password').value;
    if (valor === '') {
        mostrarError(input, 'Confirme su contraseña');
        return false;
    } else if (valor !== password) {
        mostrarError(input, 'Las contraseñas no coinciden');
        return false;
    } else {
        mostrarError(input, '');
        return true;
    }
}

// ==========================
// Validación completa del formulario
// ==========================
function validarFormularioRegistro(event) {
    const validoUsername = validarUsername();
    const validoEmail = validarEmailInput();
    const validoPassword = validarPasswordInput();
    const validoConfirm = validarConfirmPassword();

    if (!validoUsername || !validoEmail || !validoPassword || !validoConfirm) {
        event.preventDefault();
        return false;
    }
    return true;
}

// ==========================
// Eventos de blur para validación en tiempo real
// ==========================
document.getElementById('username').addEventListener('blur', validarUsername);
document.getElementById('email').addEventListener('blur', validarEmailInput);
document.getElementById('password').addEventListener('blur', validarPasswordInput);
document.getElementById('confirm').addEventListener('blur', validarConfirmPassword);

// ==========================
// Prevenir envío duplicado
// ==========================
let formularioEnviado = false;
document.querySelector('form').addEventListener('submit', function (event) {
    if (!validarFormularioRegistro(event)) return false;

    if (formularioEnviado) {
        event.preventDefault();
        return false;
    }

    formularioEnviado = true;
    const botonEnviar = this.querySelector('button[type="submit"]');
    if (botonEnviar) {
        botonEnviar.disabled = true;
        botonEnviar.textContent = 'Enviando...';
    }
});
