<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input 
        type="email"
        id="email"
        placeholder="tu email"
        name="email"
        value="<?php echo s($auth->email);?>"
        >
        
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
        type="password"
        id="password"
        placeholder="tu password"
        name="password">
    </div>

    <input type="submit" class="boton" value="Iniciar sesion">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? crea una</a>
    <a href="/olvide">¿Olvidaste la contraseña?</a>
</div>