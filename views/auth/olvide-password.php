<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Recupera tu contraseña escribiendo tu email</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="tu email">
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? inicia sesión</a>
    <a href="/olvide">¿Aún no tienes una cuenta? Crea una</a>
</div>