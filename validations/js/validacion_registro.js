// Asignación de eventos
document.getElementById("username").onblur = validarNombre;
document.getElementById("email").onblur = validarEmail;
document.getElementById("password").onblur = validarPassword;
document.getElementById("confirma_password").onblur = validarConfirmacion;

document.getElementById("registerForm").onsubmit = function (event) {
    
    validarNombre();
    validarEmail();
    validarPassword();
    validarConfirmacion();

    // Obtenemos los errores
    let userError = document.getElementById("usernameError").innerText;
    let emailError = document.getElementById("emailError").innerText;
    let passError = document.getElementById("passwordError").innerText;
    let confirmError = document.getElementById("confirmError").innerText;

    // Si hay errores, bloqueamos el envío
    if (userError !== "" || emailError !== "" || passError !== "" || confirmError !== "") {
        let box = document.getElementById("clientErrorRegister");
        event.preventDefault();
        box.classList.add("active");
        box.innerText = "Por favor corrige los errores antes de continuar.";
    }
};


// ---------------- VALIDACIONES ----------------

// USERNAME
function validarNombre() {
    let input = document.getElementById("username");
    let error = document.getElementById("usernameError");
    let valor = input.value.trim();
    let regex = /^[a-zA-Z0-9]+$/;

    if (valor === "") {
        error.innerText = "El nombre de usuario no puede estar vacío";
        input.classList.add("invalid");
    } 
    else if (valor.length > 15) {
        error.innerText = "Máximo 15 caracteres";
        input.classList.add("invalid");
    }
    else if (!regex.test(valor)) {
        error.innerText = "Solo letras y números";
        input.classList.add("invalid");
    } else {
        error.innerText = "";
        input.classList.remove("invalid");
    }
}


// EMAIL
function validarEmail() {
    let input = document.getElementById("email");
    let error = document.getElementById("emailError");
    let valor = input.value.trim();
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (valor === "") {
        error.innerText = "El email no puede estar vacío";
        input.classList.add("invalid");
    }
    else if (!regex.test(valor)) {
        error.innerText = "Formato de email no válido";
        input.classList.add("invalid");
    } else {
        error.innerText = "";
        input.classList.remove("invalid");
    }
}


// PASSWORD
function validarPassword() {
    let input = document.getElementById("password");
    let error = document.getElementById("passwordError");
    let valor = input.value;

    if (valor === "") {
        error.innerText = "La contraseña no puede estar vacía";
        input.classList.add("invalid");
    }
    else if (valor.length > 15) {
        error.innerText = "Máximo 15 caracteres";
        input.classList.add("invalid");
    } else {
        error.innerText = "";
        input.classList.remove("invalid");
    }
}


// CONFIRM PASSWORD
function validarConfirmacion() {
    let pass = document.getElementById("password").value;
    let confirm = document.getElementById("confirma_password");
    let error = document.getElementById("confirmError");

    if (confirm.value !== pass) {
        error.innerText = "Las contraseñas no coinciden";
        confirm.classList.add("invalid");
    } else {
        error.innerText = "";
        confirm.classList.remove("invalid");
    }
}
