// Carga la navbar y gestiona el login/logout
function loadNavbar() {
    fetch('/src/navbar.html')
        .then(res => res.text())
        .then(html => {
            document.getElementById('navbar').innerHTML = html;
            updateLoginButton();
            updateControlPanelVisibility();
        });
}

// Actualiza la visibilidad del Control Panel basado en el rol del usuario
function updateControlPanelVisibility() {
    let navLinks = document.querySelector('.nav-links');
    let cp = document.getElementById('control-panel-link');
    
    // Siempre eliminar el enlace existente primero
    if (cp) cp.remove();
    
    // Verificar el rol del usuario
    fetch('/api/user_role.php', { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            // Solo mostrar para roles 'owner' o 'admin'
            if ((data.role === 'owner' || data.role === 'admin') && navLinks) {
                cp = document.createElement('a');
                cp.href = '/control-panel';
                cp.id = 'control-panel-link';
                cp.textContent = 'Control Panel';
                navLinks.appendChild(cp);
            }
        })
        .catch(err => {
            console.log('Error checking user role:', err);
        });
}


function updateLoginButton() {
    const btn = document.getElementById('login-btn');
    if (!btn) return;
    
    // Verificar el estado de login consultando al servidor
    fetch('/api/user_role.php', { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            if (data.role !== null) {
                // Usuario logueado
                btn.textContent = 'Logout';
                btn.onclick = function() {
                    fetch('/api/logout.php', { method: 'POST', credentials: 'include' })
                        .then(() => {
                            // Recargar navbar después del logout
                            loadNavbar();
                        });
                };
            } else {
                // Usuario no logueado
                btn.textContent = 'Login';
                btn.onclick = function() {
                    showLoginForm();
                };
            }
        })
        .catch(err => {
            console.log('Error checking login status:', err);
            // En caso de error, asumir que no está logueado
            btn.textContent = 'Login';
            btn.onclick = function() {
                showLoginForm();
            };
        });
}

function showLoginForm() {
    // Modal con login y registro
    let modal = document.createElement('div');
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.background = 'rgba(0,0,0,0.3)';
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.innerHTML = `
        <div id="auth-modal-content" style="background:#fff;padding:32px 24px;border-radius:8px;box-shadow:0 2px 16px #0002;min-width:260px;display:flex;flex-direction:column;gap:16px;">
            <form id="login-form" style="display:flex;flex-direction:column;gap:16px;">
                <h2 style="margin:0 0 8px 0;">Iniciar sesión</h2>
                <input type="text" name="username" placeholder="Usuario" required style="padding:8px;font-size:1rem;">
                <div style="position:relative;">
                    <input type="password" name="password" placeholder="Contraseña" required style="padding:8px 40px 8px 8px;font-size:1rem;width:100%;box-sizing:border-box;">
                    <button type="button" id="toggle-login-password" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;width:24px;height:24px;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <div id="login-error" style="color:#c00;font-size:0.95em;"></div>
                <button type="submit" style="background:#0078d4;color:#fff;border:none;padding:8px 0;border-radius:4px;font-size:1rem;">Entrar</button>
                <button type="button" id="show-register" style="background:none;border:none;color:#0078d4;font-size:0.95em;">¿No tienes cuenta? Regístrate</button>
                <button type="button" id="close-login" style="background:none;border:none;color:#0078d4;font-size:0.95em;">Cancelar</button>
            </form>
            <form id="register-form" style="display:none;flex-direction:column;gap:16px;">
                <h2 style="margin:0 0 8px 0;">Registro</h2>
                <input type="text" name="username" placeholder="Usuario" required style="padding:8px;font-size:1rem;">
                <div style="position:relative;">
                    <input type="password" name="password" placeholder="Contraseña" required style="padding:8px 40px 8px 8px;font-size:1rem;width:100%;box-sizing:border-box;">
                    <button type="button" id="toggle-register-password" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;width:24px;height:24px;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <div id="register-error" style="color:#c00;font-size:0.95em;"></div>
                <button type="submit" style="background:#0078d4;color:#fff;border:none;padding:8px 0;border-radius:4px;font-size:1rem;">Crear cuenta</button>
                <button type="button" id="show-login" style="background:none;border:none;color:#0078d4;font-size:0.95em;">¿Ya tienes cuenta? Inicia sesión</button>
                <button type="button" id="close-register" style="background:none;border:none;color:#0078d4;font-size:0.95em;">Cancelar</button>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
    // Alternar entre login y registro
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    document.getElementById('show-register').onclick = () => {
        loginForm.style.display = 'none';
        registerForm.style.display = 'flex';
    };
    document.getElementById('show-login').onclick = () => {
        registerForm.style.display = 'none';
        loginForm.style.display = 'flex';
    };
    document.getElementById('close-login').onclick = () => modal.remove();
    document.getElementById('close-register').onclick = () => modal.remove();
    
    // Funcionalidad del ojito para mostrar/ocultar contraseña
    const setupPasswordToggle = (toggleButtonId, formSelector) => {
        const toggleBtn = document.getElementById(toggleButtonId);
        const passwordInput = document.querySelector(formSelector + ' input[name="password"]');
        
        // SVG para ojo abierto (mostrar contraseña)
        const eyeOpenSVG = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        `;
        
        // SVG para ojo cerrado (ocultar contraseña)
        const eyeClosedSVG = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>
        `;
        
        toggleBtn.onclick = () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.innerHTML = eyeClosedSVG;
            } else {
                passwordInput.type = 'password';
                toggleBtn.innerHTML = eyeOpenSVG;
            }
        };
    };
    
    setupPasswordToggle('toggle-login-password', '#login-form');
    setupPasswordToggle('toggle-register-password', '#register-form');
    // Login
    loginForm.onsubmit = function(e) {
        e.preventDefault();
        const username = this.username.value;
        const password = this.password.value;
        fetch('/api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ username, password })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                modal.remove();
                loadNavbar(); // Esto actualizará tanto el botón como el control panel
            } else {
                document.getElementById('login-error').textContent = data.error || 'Error de login';
            }
        })
        .catch(() => {
            document.getElementById('login-error').textContent = 'Error de red';
        });
    };
    // Registro
    registerForm.onsubmit = function(e) {
        e.preventDefault();
        const username = this.username.value;
        const password = this.password.value;
        fetch('/api/register.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify({ username, password })
        })
        .then(async r => {
            let data;
            try {
                data = await r.json();
            } catch {
                data = { success: false, error: 'Error de red o respuesta inesperada' };
            }
            if (r.status === 409) {
                document.getElementById('register-error').textContent = 'Ese usuario ya está registrado. Prueba con otro.';
            } else if (data.success) {
                document.getElementById('register-error').style.color = '#080';
                document.getElementById('register-error').textContent = '¡Usuario creado! Redirigiendo a login...';
                setTimeout(() => {
                    registerForm.style.display = 'none';
                    loginForm.style.display = 'flex';
                    document.getElementById('register-error').textContent = '';
                    document.getElementById('register-error').style.color = '#c00';
                }, 1500);
            } else {
                document.getElementById('register-error').style.color = '#c00';
                document.getElementById('register-error').textContent = data.error || 'Error de registro';
            }
        })
        .catch(() => {
            document.getElementById('register-error').style.color = '#c00';
            document.getElementById('register-error').textContent = 'Error de red';
        });
    };
}

document.addEventListener('DOMContentLoaded', loadNavbar);
