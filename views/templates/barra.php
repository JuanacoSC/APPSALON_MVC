<div class="barra">
    <p>Hola: <?php echo $nombre ?? '' ?></p>
    <a href="/logout" class="boton">Cerrar sesión</a>
</div>
<div id="app">

<?php
if(isset($_SESSION['admin'])){ ;?>
    <div class="barra-servicios">
        <a class="boton "href="/admin">Ver citas</a>
        <a class="boton "href="/servicios">Ver Servicios</a>
        <a class="boton "href="/servicios/crear">Nuevos servicios</a>
    </div>

<?php } ;?>
