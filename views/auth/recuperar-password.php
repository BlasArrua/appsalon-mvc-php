<h1 class="nombre-pagina"></h1>
<p class="descripcion-pagina">Coloca tu nuevo Password a continuacion:</p>

<?php include_once __DIR__ ."/../templates/alertas.php"; ?>

<?php if($error)return; ?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Nuevo Password">
    </div>
    <input class="boton" type="submit" value="Reestablecer Password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesion</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear</a>
</div>