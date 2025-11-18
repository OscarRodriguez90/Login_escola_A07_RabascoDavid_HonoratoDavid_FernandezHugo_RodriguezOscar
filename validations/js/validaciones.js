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

        function validarConfirmar() {
            const password = document.getElementById('password').value.trim();
            const confirm = document.getElementById('confirm').value.trim();
            const error = document.getElementById('confirm-error');

            if (confirm !== password || confirm === '') {
                error.textContent = 'Las contraseñas no coinciden.';
                error.style.color = 'red';
                return false;
            } else {
                error.textContent = '';
                return true;
            }
        }function validarUsername() {
            const username = document.getElementById('username').value.trim();
            const error = document.getElementById('username-error');
            if (username.length < 3) {
                error.textContent = 'El nombre de usuario debe tener al menos 3 caracteres.';
                return false;
            } else {
                error.textContent = '';
                return true;
            }
        }

        function validarEmail() {
            const email = document.getElementById('email').value.trim();
            const error = document.getElementById('email-error');
            const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
            if (!emailRegex.test(email)) {
                error.textContent = 'Por favor ingresa un correo electrónico válido.';
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
                return false;
            } else {
                error.textContent = '';
                return true;
            }
        }