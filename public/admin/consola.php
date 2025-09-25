<?php
require __DIR__ . '/inc/bootstrap.php';
ensure_logged_in();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Consola</title>
    <link rel="stylesheet" href="/admin/assets/style.css">
</head>

<body>
    <h1>Consola</h1>
    <form id="f-cmd">
        <select name="cmd">
            <?php foreach (ALLOWED_COMMANDS as $cmd): ?>
                <option value="<?php echo htmlspecialchars($cmd); ?>"><?php echo htmlspecialchars($cmd); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(csrf_token()); ?>">
        <button>Ejecutar</button>
    </form>
    <pre id="out" class="terminal"></pre>

    <script>
        document.getElementById('f-cmd').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);
            const res = await fetch('/admin/api/consola_exec.php', {
                method: 'POST',
                body: new URLSearchParams(Object.fromEntries(fd))
            });
            document.getElementById('out').textContent = await res.text();
        });
    </script>
</body>

</html>