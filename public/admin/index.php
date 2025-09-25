<?php
require __DIR__ . '/inc/bootstrap.php';

$logged  = is_logged_in();
$expired = isset($_GET['expired']);
$err     = isset($_GET['err']);
$last_user = $_SESSION['last_user'] ?? '';
unset($_SESSION['last_user']); // solo se usa una vez

if (!$logged):
    // Vista login
    ?>
    <!doctype html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Panel • Login</title>
        <link rel="stylesheet" href="/admin/assets/styles.css">
    </head>
    <body>
        <div class="card">
            <h1>Panel • Login</h1>

            <form method="post" action="/admin/api/login.php">
                <label>Usuario</label>
                <input name="user" value="<?php echo htmlspecialchars($last_user); ?>" required>
                <label>Contraseña</label>
                <input type="password" name="pass" required>
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <button type="submit">Entrar</button>
            </form>

            <?php if ($expired): ?>
                <p class="msg-warning">⚠️ Sesión expirada</p>
            <?php endif; ?>

            <?php if ($err): ?>
                <p class="msg-error">❌ Usuario o contraseña incorrectos</p>
            <?php endif; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
endif;

// Vista dashboard
ensure_logged_in();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel</title>
    <link rel="stylesheet" href="/admin/assets/styles.css">
</head>
<body>
    <nav class="topbar">
        <strong>ServidorRasp</strong>
        <form method="post" action="/admin/api/logout.php" style="margin-left:auto">
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
            <button>Salir</button>
        </form>
    </nav>

    <main class="grid">
        <section class="card">
            <h2>Estado del sistema</h2>
            <pre>Temp: <?php echo sys_temp() ?? 'N/D'; ?></pre>
            <pre><?php echo htmlspecialchars(sys_cpu()); ?></pre>
            <pre><?php echo htmlspecialchars(sys_mem()); ?></pre>
            <pre><?php echo htmlspecialchars(sys_disk()); ?></pre>
        </section>

        <section class="card">
            <h2>Consola</h2>
            <form id="f-cmd">
                <label>Comando</label>
                <select name="cmd">
                    <?php foreach (ALLOWED_COMMANDS as $cmd): ?>
                        <option value="<?php echo htmlspecialchars($cmd); ?>"><?php echo htmlspecialchars($cmd); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <button>Ejecutar</button>
            </form>
            <pre id="cmd-out" class="terminal"></pre>
            <a href="/admin/consola.php">Consola avanzada →</a>
        </section>

        <section class="card">
            <h2>Editor rápido</h2>
            <form id="f-edit">
                <label>Archivo</label>
                <select name="path" id="file-path">
                    <?php foreach (ALLOWED_FILES as $f): ?>
                        <option value="<?php echo htmlspecialchars($f); ?>"><?php echo htmlspecialchars($f); ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="content" rows="12" id="file-content"></textarea>
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
                <button>Guardar</button>
            </form>
        </section>
    </main>

    <script>
        async function post(url, data) {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams(data)
            });
            const txt = await res.text();
            if (!res.ok) throw new Error(txt || 'Error');
            return txt;
        }

        document.getElementById('f-cmd').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);
            const out = document.getElementById('cmd-out');
            out.textContent = 'Ejecutando...';
            try {
                const r = await post('/admin/api/consola_exec.php', Object.fromEntries(fd));
                out.textContent = r;
            } catch (err) { out.textContent = '❌ ' + err.message; }
        });

        const sel = document.getElementById('file-path');
        const area = document.getElementById('file-content');

        async function loadFile() {
            const r = await fetch('/admin/editor.php?path=' + encodeURIComponent(sel.value));
            area.value = await r.text();
        }
        sel.addEventListener('change', loadFile);
        loadFile();

        document.getElementById('f-edit').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);
            try {
                await post('/admin/api/edit_file.php', Object.fromEntries(fd));
                alert('Guardado OK');
            } catch (err) { alert('❌ ' + err.message); }
        });
    </script>
</body>
</html>
